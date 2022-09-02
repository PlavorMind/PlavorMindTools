<?php
namespace PlavorMind\PlavorMindTools;
use MediaWiki\Hook\MediaWikiServicesHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\UserGetAllRightsHook;

class HookHandler implements MediaWikiServicesHook, UserGetAllRightsHook {
  // MediaWikiServices type should not be specified for $services parameter.
  public function onMediaWikiServices($services) {
    global $wgAddGroups, $wgGroupPermissions, $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf, $wgRemoveGroups, $wgRevokePermissions;
    $groups = $services->getMainConfig()->get('PMTDisabledUserGroups');

    foreach ($groups as $group) {
      unset($wgAddGroups[$group], $wgGroupPermissions[$group], $wgGroupsAddToSelf[$group], $wgGroupsRemoveFromSelf[$group], $wgRemoveGroups[$group], $wgRevokePermissions[$group]);
    }
  }

  public function onUserGetAllRights(&$rights) {
    $settings = MediaWikiServices::getInstance()->getMainConfig();

    if (!$settings->get('PMTFeatureConfig')['UserPageAccess']['enable']) {
      return;
    }

    $rights = array_merge($rights, [
      'deleteownuserpages',
      'editotheruserpages',
      'moveownuserpages'
    ]);
  }
}
