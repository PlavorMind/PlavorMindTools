<?php
namespace PlavorMind\PlavorMindTools\ControlUserGroups;
use MediaWiki\Hook\MediaWikiServicesHook;

class MediaWikiServicesHookHandler implements MediaWikiServicesHook {
  // MediaWikiServices hook does not allow MainConfig service to be injected into a class with its handler.
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
