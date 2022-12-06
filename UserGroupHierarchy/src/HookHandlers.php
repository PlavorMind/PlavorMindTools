<?php
namespace PlavorMind\PlavorMindTools\UserGroupHierarchy;
use ExtensionRegistry;
use MediaWiki\Extension\CentralAuth\User\CentralAuthUser;
use MediaWiki\Hook\BlockIpHook;
use MediaWiki\MediaWikiServices;

class HookHandlers implements BlockIpHook {
  private $MediaWikiServices;
  private $settings;

  public function __construct() {
    $this->MediaWikiServices = MediaWikiServices::getInstance();
    $this->settings = $this->MediaWikiServices->getMainConfig();
  }

  /**
   * @param User|UserIdentity $user
   */
  private function getUserHierarchy($user) {
    // isAnon() does not exist in UserIdentity objects.
    if (!$user->isRegistered()) {
      return 0;
    }

    $groupHierarchies = $this->settings->get('UGHHierarchies');
    $groups = $this->MediaWikiServices->getUserGroupManager()->getUserEffectiveGroups($user);
    $hierarchies = [0];

    if (ExtensionRegistry::getInstance()->isLoaded('CentralAuth')) {
      $centralAuthGlobalGroups = CentralAuthUser::getInstance($user)->getGlobalGroups();
      $groups = array_merge($groups, $centralAuthGlobalGroups);
    }

    foreach ($groups as $group) {
      if (isset($groupHierarchies[$group])) {
        $hierarchies[] = $groupHierarchies[$group];
      }
    }

    return max($hierarchies);
  }

  public function onBlockIp($block, $user, &$reason) {
    if (!$this->settings->get('UGHEnable')) {
      return;
    }

    $enforcerHierarchy = $this->getUserHierarchy($user);
    $targetHierarchy = $this->getUserHierarchy($block->getTargetUserIdentity());

    if ($enforcerHierarchy > $targetHierarchy) {
      return;
    }

    $reason = ['usergrouphierarchy-cannot-block-hierarchy'];
    return false;
  }
}
