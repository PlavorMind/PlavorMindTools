<?php
namespace PlavorMind\PlavorMindTools;
use MediaWiki\Hook\PreferencesGetLayoutHook;
use MediaWiki\MediaWikiServices;

class HookHandlers implements PreferencesGetLayoutHook {
  // 1.40+
  public function onPreferencesGetLayout(&$useMobileLayout, $skinName, $skinProperties = []) {
    if (MediaWikiServices::getInstance()->getMainConfig()->get('PMTNewPreferencesLayout')) {
      $useMobileLayout = true;
    }
  }
}
