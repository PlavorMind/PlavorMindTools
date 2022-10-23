<?php
namespace PlavorMind\PlavorMindTools\UserPageAccess;
use MediaWiki\Hook\MovePageCheckPermissionsHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;
use MediaWiki\Permissions\Hook\UserGetAllRightsHook;

class HookHandler implements MovePageCheckPermissionsHook, TitleQuickPermissionsHook, UserGetAllRightsHook {
  private $enabled;

  public function __construct() {
    $this->enabled = MediaWikiServices::getInstance()->getMainConfig()->get('UPAEnable');
  }

  public function onMovePageCheckPermissions($oldTitle, $newTitle, $user, $reason, $status) {
    if (!$this->enabled) {
      return;
    }

    $newNamespace = $newTitle->getNamespace();
    $oldNamespace = $oldTitle->getNamespace();

    if ($oldNamespace === $newNamespace || !($oldNamespace === NS_USER || $newNamespace === NS_USER) || $user->isAllowed('movetousernamespace')) {
      return;
    }

    $status->fatal('userpageaccess-cannot-move-user-namespace');
    return false;
  }

  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short) {
    if (
      !($this->enabled && $action === 'edit' && $title->getNamespace() === NS_USER)
      || $title->getRootText() === $user->getName()
      || $title->isUserConfigPage()
      || $user->isAllowed('editotheruserpages')
    ) {
      return;
    }

    $errors[] = ['userpageaccess-cannot-edit-other-user-pages'];
    return false;
  }

  public function onUserGetAllRights(&$rights) {
    if (!$this->enabled) {
      return;
    }

    $rights = array_merge($rights, [
      'editotheruserpages',
      'movetousernamespace'
    ]);
  }
}
