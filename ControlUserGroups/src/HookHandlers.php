<?php
namespace PlavorMind\PlavorMindTools\ControlUserGroups;
use ExtensionRegistry;
use MediaWiki\Extension\CentralAuth\User\CentralAuthUser;
use MediaWiki\Hook\BlockIpHook;
use MediaWiki\Hook\MediaWikiServicesHook;
use MediaWiki\MediaWikiServices;

class HookHandlers implements BlockIpHook, MediaWikiServicesHook {
  private $MediaWikiServices;

  public function __construct() {
    $this->MediaWikiServices = MediaWikiServices::getInstance();
  }

  /**
   * @param User|UserIdentity $user
   */
  private function getUserHierarchy($user, $groupHierarchies) {
    // isAnon() does not exist in UserIdentity objects.
    if (!$user->isRegistered()) {
      return 0;
    }

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
    $settings = $this->MediaWikiServices->getMainConfig();

    if (!$settings->get('CUGEnable')) {
      return;
    }

    $groupHierarchies = $settings->get('CUGHierarchies');

    if (count($groupHierarchies) === 0) {
      return;
    }

    $enforcerHierarchy = $this->getUserHierarchy($user, $groupHierarchies);
    $targetHierarchy = $this->getUserHierarchy($block->getTargetUserIdentity(), $groupHierarchies);

    if ($enforcerHierarchy > $targetHierarchy) {
      return;
    }

    $reason = ['controlusergroups-cannot-block-hierarchy'];
    return false;
  }

  public function onMediaWikiServices($services) {
    global $wgAddGroups, $wgGroupPermissions, $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf, $wgRemoveGroups, $wgRevokePermissions;
    $settings = $services->getMainConfig();

    if (!$settings->get('CUGEnable')) {
      return;
    }

    $groups = $settings->get('CUGDisableGroups');

    foreach ($groups as $group) {
      unset($wgAddGroups[$group], $wgGroupPermissions[$group], $wgGroupsAddToSelf[$group], $wgGroupsRemoveFromSelf[$group], $wgRemoveGroups[$group], $wgRevokePermissions[$group]);
    }
  }
}
