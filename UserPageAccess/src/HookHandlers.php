<?php
declare(strict_types = 1);

namespace PlavorMind\PlavorMindTools\UserPageAccess;
use MediaWiki\Hook\MovePageCheckPermissionsHook;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;
use MediaWiki\Permissions\Hook\UserGetAllRightsHook;

class HookHandlers implements MovePageCheckPermissionsHook, TitleQuickPermissionsHook, UserGetAllRightsHook {
  private readonly bool $enabled;

  public function __construct($settings) {
    $this->enabled = $settings->get('UPAEnable');
  }

  public function onMovePageCheckPermissions($oldTitle, $newTitle, $user, $reason, $status) {
    if (!$this->enabled) {
      return;
    }

    $newNamespace = $newTitle->getNamespace();
    $oldNamespace = $oldTitle->getNamespace();

    if ($oldNamespace === $newNamespace || !($oldNamespace === NS_USER || $newNamespace === NS_USER) || $user->isAllowed('move-user-namespace')) {
      return;
    }

    $status->fatal('userpageaccess-cannot-move-user-namespace');
    return false;
  }

  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short) {
    if (
      !($this->enabled && $action === 'edit' && $title->inNamespace(NS_USER))
      || $title->getRootText() === $user->getName()
      || $title->isUserConfigPage()
      || $user->isAllowed('edit-other-user-pages')
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
      'edit-other-user-pages',
      'move-user-namespace'
    ]);
  }
}
