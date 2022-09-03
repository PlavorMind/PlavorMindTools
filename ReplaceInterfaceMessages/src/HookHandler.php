<?php
namespace PlavorMind\PlavorMindTools\ReplaceInterfaceMessages\Src;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;

class HookHandler implements TitleQuickPermissionsHook {
  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short) {
    if (!($action === 'edit' && $title->getNamespace() === NS_MEDIAWIKI && strpos($title->getRootText(), 'Rim-') === 0)) {
      return;
    }

    // Messages that start with "rim-" should never be edited on-wiki, so just override any other errors.
    $errors = [
      ['replaceinterfacemessages-cannot-edit-rim-msgs']
    ];
    return false;
  }
}
