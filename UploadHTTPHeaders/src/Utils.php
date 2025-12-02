<?php
declare(strict_types = 1);

namespace PlavorMind\PlavorMindTools\UploadHTTPHeaders;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\Title;

class Utils {
  public static function getDeletedUploadName(WebRequest $request, ?string $specialSubpage): ?string {
    if ($request->getMethod() !== 'GET') {
      return null;
    }

    $fileParameter = $request->getRawVal('file');

    if ($fileParameter === null || $fileParameter === '') {
      return null;
    }

    $token = $request->getRawVal('token');

    if ($token === null || $token === '') {
      return null;
    }

    $titleString = ($specialSubpage === null) || ($specialSubpage === '') ? $request->getText('target') : $specialSubpage;

    if ($titleString === '') {
      return null;
    }

    $title = Title::newFromText($titleString);
    return $title !== null && $title->inNamespace(NS_FILE) ? $title->getText() : null;
  }
}
