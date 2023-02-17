<?php
namespace PlavorMind\PlavorMindTools;
use MediaWiki\Hook\ImgAuthModifyHeadersHook;
use MediaWiki\Hook\PreferencesGetLayoutHook;
use MediaWiki\MediaWikiServices;

class HookHandlers implements ImgAuthModifyHeadersHook, PreferencesGetLayoutHook {
  private $settings;

  public function __construct() {
    $this->settings = MediaWikiServices::getInstance()->getMainConfig();
  }

  public function onImgAuthModifyHeaders($title, &$headers) {
    $policies = $this->settings->get('PMTImgAuthCSPs');

    if ($policies['enforced'] !== null) {
      $headers['Content-Security-Policy'] = $policies['enforced'];
    }

    if ($policies['report-only'] !== null) {
      $headers['Content-Security-Policy-Report-Only'] = $policies['report-only'];
    }
  }

  // 1.40+
  public function onPreferencesGetLayout(&$useMobileLayout, $skinName, $skinProperties = []) {
    if ($this->settings->get('PMTNewPreferencesLayout')) {
      $useMobileLayout = true;
    }
  }
}
