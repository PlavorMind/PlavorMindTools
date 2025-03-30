<?php
declare(strict_types = 1);

namespace PlavorMind\PlavorMindTools\UploadHTTPHeaders;
use MediaWiki\Config\Config;
use MediaWiki\Hook\ImgAuthModifyHeadersHook;
use MediaWiki\SpecialPage\Hook\SpecialPageBeforeExecuteHook;
use MediaWiki\Title\Title;

class HookHandlers implements ImgAuthModifyHeadersHook, SpecialPageBeforeExecuteHook {
  private Config $settings;

  public function __construct(Config $settings) {
    $this->settings = $settings;
  }

  public function onImgAuthModifyHeaders($title, &$headers) {
    if (!$this->settings->get('UHHEnable')) {
      return;
    }

    $CSPs = $this->settings->get('UHHCSPs');

    if ($CSPs['enforced'] !== null) {
      $headers['Content-Security-Policy'] = $CSPs['enforced'];
    }

    if ($CSPs['report-only'] !== null) {
      $headers['Content-Security-Policy-Report-Only'] = $CSPs['report-only'];
    }
  }

  public function onSpecialPageBeforeExecute($special, $subPage) {
    $settings = $special->getConfig();

    if (!($settings->get('UHHEnable') && $special->getName() === 'Undelete')) {
      return;
    }

    $request = $special->getRequest();

    if ($request->getMethod() !== 'GET') {
      return;
    }

    $fileParameter = $request->getRawVal('file');

    if ($fileParameter === null || $fileParameter === '') {
      return;
    }

    $token = $request->getRawVal('token');

    if ($token === null || $token === '') {
      return;
    }

    $titleString = ($subPage === null) || ($subPage === '') ? $request->getText('target') : $subPage;

    if ($titleString === '') {
      return;
    }

    $title = Title::newFromText($titleString);

    if ($title === null || !$title->inNamespace(NS_FILE)) {
      return;
    }

    $CSPs = $settings->get('UHHCSPs');
    $response = $request->response();

    if ($CSPs['enforced'] !== null) {
      $response->header('Content-Security-Policy: ' . $CSPs['enforced']);
    }

    if ($CSPs['report-only'] !== null) {
      $response->header('Content-Security-Policy-Report-Only: ' . $CSPs['report-only']);
    }
  }
}
