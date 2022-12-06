<?php
namespace PlavorMind\PlavorMindTools\ReplaceInterfaceMessages;
use MediaWiki\Cache\Hook\MessageCache__getHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;

class HookHandlers implements MessageCache__getHook, TitleQuickPermissionsHook {
  private $rimMsgKeys = [];

  public function __construct() {
    $directories = [
      'core',
      'core-en-only',
      'extensions',
      'extensions-en-only'
    ];

    foreach ($directories as $directory) {
      $msgFileContent = file_get_contents(__DIR__ . "/../i18n/$directory/en.json");
      $msgArray = json_decode($msgFileContent, true);
      $this->rimMsgKeys = array_merge($this->rimMsgKeys, array_keys($msgArray));
    }
  }

  public function onMessageCache__get(&$lckey) {
    $MediaWikiServices = MediaWikiServices::getInstance();
    $settings = $MediaWikiServices->getMainConfig();

    // $wgRIMPlavorMindSpecificMessages is not defined in extension.json.
    if (in_array("rim-plavormind-$lckey", $this->rimMsgKeys) && $settings->has('RIMPlavorMindSpecificMessages') && $settings->get('RIMPlavorMindSpecificMessages')) {
      $rimKey = "rim-plavormind-$lckey";
    }
    elseif (in_array("rim-$lckey", $this->rimMsgKeys)) {
      $rimKey = "rim-$lckey";
    }
    else {
      return;
    }

    $forcedKeys = [];

    if (in_array($lckey, $forcedKeys)) {
      $lckey = $rimKey;
      return;
    }

    $systemUserKeys = [
      // core-en-only
      'autochange-username',
      'double-redirect-fixer',
      'spambot_username',
      'usermessage-editor',

      // extensions-en-only
      'abusefilter-blocker',
      'babel-autocreate-user'
    ];

    if (in_array($lckey, $systemUserKeys)) {
      if ($settings->get('RIMEnglishSystemUsers')) {
        $lckey = $rimKey;
      }

      return;
    }

    $msgCache = $MediaWikiServices->getMessageCache();

    // getMsgFromNamespace() can return a string.
    if ($msgCache->getMsgFromNamespace(ucfirst($lckey), $settings->get('LanguageCode')) === false) {
      $lckey = $rimKey;
    }
  }

  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short) {
    if (!($action === 'edit' && $title->getNamespace() === NS_MEDIAWIKI && str_starts_with($title->getRootText(), 'Rim-'))) {
      return;
    }

    // Messages which start with "rim-" should never be edited on-wiki, so just override any other errors.
    $errors = [
      ['replaceinterfacemessages-cannot-edit-rim-msgs']
    ];
    return false;
  }
}
