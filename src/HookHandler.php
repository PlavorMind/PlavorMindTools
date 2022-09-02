<?php
namespace PlavorMind\PlavorMindToolsNew;
use MediaWiki\Hook\MediaWikiServicesHook;

class HookHandler implements MediaWikiServicesHook {
  // MediaWikiServices type should not be specified for $services parameter.
  public function onMediaWikiServices($services) {
    global $wgAddGroups, $wgGroupPermissions, $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf, $wgRemoveGroups, $wgRevokePermissions;
    $groups = $services->getMainConfig()->get('PMTDisabledUserGroups');

    foreach ($groups as $group) {
      unset($wgAddGroups[$group], $wgGroupPermissions[$group], $wgGroupsAddToSelf[$group], $wgGroupsRemoveFromSelf[$group], $wgRemoveGroups[$group], $wgRevokePermissions[$group]);
    }
  }
}
