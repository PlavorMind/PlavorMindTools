<?php
namespace PlavorMind\PlavorMindTools\UserPageAccess;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;
use MediaWiki\Permissions\Hook\UserGetAllRightsHook;

class HookHandler implements TitleQuickPermissionsHook, UserGetAllRightsHook {
  private $enabled;

  public function __construct() {
    $this->enabled = MediaWikiServices::getInstance()->getMainConfig()->get('UPAEnable');
  }

  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short) {
    if (!($this->enabled && $action === 'edit' && $title->getNamespace() === NS_USER) || (!$user->isAnon() && $title->getRootText() === $user->getName()) || $title->isUserConfigPage() || $user->isAllowed('editotheruserpages')) {
      return;
    }

    $errors[] = ['userpageaccess-cannot-edit-other-user-pages'];
    return false;
  }

  public function onUserGetAllRights(&$rights) {
    if ($this->enabled) {
      $rights[] = 'editotheruserpages';
    }
  }
}
