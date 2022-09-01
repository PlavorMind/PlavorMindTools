<?php
namespace PlavorMind\PlavorMindToolsNew;
use MediaWiki\Hook\MediaWikiServicesHook;
use MediaWiki\MediaWikiServices;

class HookHandler implements MediaWikiServicesHook {
  // MediaWikiServices type should not be specified for $services parameter.
  public function onMediaWikiServices($services) {
    global $wgAddGroups, $wgGroupPermissions, $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf, $wgRemoveGroups, $wgRevokePermissions;
    $settings = MediaWikiServices::getInstance()->getMainConfig();

    foreach ($settings->get('PMTDisabledUserGroups') as $group) {
      unset($wgAddGroups[$group], $wgGroupPermissions[$group], $wgGroupsAddToSelf[$group], $wgGroupsRemoveFromSelf[$group], $wgRemoveGroups[$group], $wgRevokePermissions[$group]);
    }
  }
}
