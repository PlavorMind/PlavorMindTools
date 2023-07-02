<?php
namespace PlavorMind\PlavorMindTools;
use MediaWiki\Hook\PreferencesGetLayoutHook;

class HookHandlers implements PreferencesGetLayoutHook {
  private $settings;

  public function __construct($settings) {
    $this->settings = $settings;
  }

  public function onPreferencesGetLayout(&$useMobileLayout, $skinName, $skinProperties = []) {
    if ($this->settings->get('PMTNewPreferencesLayout')) {
      $useMobileLayout = true;
    }
  }
}
