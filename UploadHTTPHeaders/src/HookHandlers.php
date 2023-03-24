<?php
namespace PlavorMind\PlavorMindTools\UploadHTTPHeaders;
use MediaWiki\Hook\ImgAuthModifyHeadersHook;
use MediaWiki\SpecialPage\Hook\SpecialPageBeforeExecuteHook;
use MediaWiki\Title\Title;

class HookHandlers implements ImgAuthModifyHeadersHook, SpecialPageBeforeExecuteHook {
  private $enabled;
  private $settings;

  public function __construct($settings) {
    $this->enabled = $settings->get('UHHEnable');
    $this->settings = $settings;
  }

  public function onImgAuthModifyHeaders($title, &$headers) {
    if (!$this->enabled) {
      return;
    }

    $CSPs = $this->settings->get('UHHImgAuthCSPs');

    if ($CSPs['enforced'] !== null) {
      $headers['Content-Security-Policy'] = $CSPs['enforced'];
    }

    if ($CSPs['report-only'] !== null) {
      $headers['Content-Security-Policy-Report-Only'] = $CSPs['report-only'];
    }
  }

  public function onSpecialPageBeforeExecute($special, $subPage) {
    if (!($this->enabled && $special->getName() === 'Undelete')) {
      return;
    }

    $request = $special->getRequest();

    if ($request->getMethod() !== 'GET' || $request->getRawVal('file', '') === '' || $request->getRawVal('token', '') === '') {
      return;
    }

    $titleString = ($subPage === null) || ($subPage === '') ? $request->getRawVal('target', '') : $subPage;

    if ($titleString === '') {
      return;
    }

    $title = Title::newFromText($titleString);

    if ($title === null || !$title->inNamespace(NS_FILE)) {
      return;
    }

    $CSPs = $this->settings->get('UUHDeletedFileCSPs');
    $response = $request->response();

    if ($CSPs['enforced'] !== null) {
      $response->header('Content-Security-Policy: ' . $CSPs['enforced']);
    }

    if ($CSPs['report-only'] !== null) {
      $response->header('Content-Security-Policy-Report-Only: ' . $CSPs['report-only']);
    }
  }
}
