<?php
declare(strict_types = 1);

namespace PlavorMind\PlavorMindTools\UploadHTTPHeaders;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\SpecialPage\Hook\SpecialPageBeforeExecuteHook;

class HookHandlers implements SpecialPageBeforeExecuteHook {
  public function onSpecialPageBeforeExecute($special, $subPage) {
    if (!($special->getConfig()->get('UHHEnable') && $special->getName() === 'Undelete')) {
      return;
    }

    $request = $special->getRequest();
    $filename = Utils::getDeletedUploadName($request, $subPage);

    if ($filename === null) {
      return;
    }

    $CSP = ContentSecurityPolicy::getMediaHeader($filename);

    if ($CSP === null) {
      return;
    }

    $CSPheader = 'Content-Security-Policy: ' . $CSP;
    $request->response()->header($CSPheader);
  }
}
