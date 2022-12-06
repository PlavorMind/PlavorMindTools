<?php
namespace PlavorMind\PlavorMindTools;
use MediaWiki\Hook\MediaWikiServicesHook;
use MediaWiki\Hook\PreferencesGetLayoutHook;
use MediaWiki\MediaWikiServices;

class HookHandlers implements MediaWikiServicesHook, PreferencesGetLayoutHook {
  public function onMediaWikiServices($services) {
    global $wgAddGroups, $wgGroupPermissions, $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf, $wgRemoveGroups, $wgRevokePermissions;
    $groups = $services->getMainConfig()->get('PMTDisableUserGroups');

    foreach ($groups as $group) {
      unset($wgAddGroups[$group], $wgGroupPermissions[$group], $wgGroupsAddToSelf[$group], $wgGroupsRemoveFromSelf[$group], $wgRemoveGroups[$group], $wgRevokePermissions[$group]);
    }
  }

  // 1.40+
  public function onPreferencesGetLayout(&$useMobileLayout, $skinName, $skinProperties = []) {
    if (MediaWikiServices::getInstance()->getMainConfig()->get('PMTNewPreferencesLayout')) {
      $useMobileLayout = true;
    }
  }
}
