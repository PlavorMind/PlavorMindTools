<?php
namespace PlavorMind\PlavorMindTools\ControlUserGroups;
use MediaWiki\Hook\BlockIpHook;
use MediaWiki\Hook\MediaWikiServicesHook;
use MediaWiki\MediaWikiServices;

class HookHandlers implements BlockIpHook, MediaWikiServicesHook {
  public function onBlockIp($block, $user, &$reason) {
    $settings = MediaWikiServices::getInstance()->getMainConfig();

    if (!$settings->get('CUGEnable')) {
      return;
    }

    $centralAuthHierarchiesSetting = $settings->get('CUGCentralAuthHierarchies');
    $hierarchiesSetting = $settings->get('CUGHierarchies');

    if ($centralAuthHierarchiesSetting === null && $hierarchiesSetting === null) {
      return;
    }

    $targetIdentity = $block->getTargetUserIdentity();

    if ($user->equals($targetIdentity)) {
      $reason = ['controlusergroups-cannot-block-hierarchy'];
      return false;
    }

    $centralAuthGroupHierarchies = $centralAuthHierarchiesSetting ?? [];
    $groupHierarchies = $hierarchiesSetting ?? [];
    $enforcerHierarchy = UserGroupHierarchies::getUserHierarchy($user, $groupHierarchies, $centralAuthGroupHierarchies);
    $targetHierarchy = UserGroupHierarchies::getUserHierarchy($targetIdentity, $groupHierarchies, $centralAuthGroupHierarchies);

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
