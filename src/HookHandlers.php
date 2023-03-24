<?php
namespace PlavorMind\PlavorMindTools;
use MediaWiki\MediaWikiServices;

class HookHandlers {
  /*
  1.40+
  Declaring the type for &$useMobileLayout as bool-only causes type error.
  */
  public static function onPreferencesGetLayout(?bool &$useMobileLayout, string $skinName, array $skinProperties) {
    if (MediaWikiServices::getInstance()->getMainConfig()->get('PMTNewPreferencesLayout')) {
      $useMobileLayout = true;
    }
  }
}
