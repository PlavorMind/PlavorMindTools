<?php
namespace PlavorMind\PlavorMindTools\ReplaceInterfaceMessages;
use MediaWiki\Cache\Hook\MessageCache__getHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;

class HookHandlers implements MessageCache__getHook, TitleQuickPermissionsHook {
  // 1.41+
  public static function onMessageCacheFetchOverrides(array &$keys) {
    $settings = MediaWikiServices::getInstance()->getMainConfig();

    if (!$settings->get('RIMEnable')) {
      return;
    }

    $directories = glob(__DIR__ . '/../i18n/*',  GLOB_ERR | GLOB_ONLYDIR);
    $newKeys = [];

    foreach ($directories as $directory) {
      $msgFileContent = file_get_contents("$directory/en.json");
      $msgArray = json_decode($msgFileContent, true);
      $newKeys = array_merge($newKeys, array_keys($msgArray));
    }

    $forcedKeys = [];
    $language = $settings->get('LanguageCode');
    $oldKeysWithDuplicates = preg_replace('/^rim-(?:plavormind-)?/', '', $newKeys);
    $oldKeys = array_values(array_unique($oldKeysWithDuplicates, SORT_REGULAR));
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
    $useEnglishSystemUsers = $settings->get('RIMEnglishSystemUsers');
    $usePlavorMindMsgs = $settings->has('RIMPlavorMindSpecificMessages') && $settings->get('RIMPlavorMindSpecificMessages');

    foreach ($oldKeys as $oldKey) {
      $newKey = $usePlavorMindMsgs && in_array("rim-plavormind-$oldKey", $newKeys) ? "rim-plavormind-$oldKey" : "rim-$oldKey";

      if (in_array($oldKey, $forcedKeys)) {
        $keys[$oldKey] = $newKey;
        continue;
      }
      elseif (in_array($oldKey, $systemUserKeys)) {
        if ($useEnglishSystemUsers) {
          $keys[$oldKey] = $newKey;
        }

        continue;
      }

      $keys[$oldKey] = function (string $key, $cache) use ($language, $newKey, $oldKey): string {
        $uppercaseFirstKey = ucfirst($oldKey);
        // getMsgFromNamespace() can return a string.
        return ($cache->getMsgFromNamespace($uppercaseFirstKey, $language) === false) ? $newKey : $oldKey;
      };
    }
  }

  private $enabled;
  private $newHookAvailable;
  private $rimMsgKeys = [];
  private $settings;

  public function __construct($settings) {
    $this->enabled = $settings->get('RIMEnable');
    $this->newHookAvailable = interface_exists('MediaWiki\\Cache\\Hook\\MessageCacheFetchOverridesHook');
    $this->settings = $settings;

    if (!$this->enabled || $this->newHookAvailable) {
      return;
    }

    $directories = glob(__DIR__ . '/../i18n/*',  GLOB_ERR | GLOB_ONLYDIR);

    foreach ($directories as $directory) {
      $msgFileContent = file_get_contents("$directory/en.json");
      $msgArray = json_decode($msgFileContent, true);
      $this->rimMsgKeys = array_merge($this->rimMsgKeys, array_keys($msgArray));
    }
  }

  public function onMessageCache__get(&$lckey) {
    if (!$this->enabled || $this->newHookAvailable) {
      return;
    }
    // $wgRIMPlavorMindSpecificMessages is not defined in extension.json.
    elseif (in_array("rim-plavormind-$lckey", $this->rimMsgKeys) && $this->settings->has('RIMPlavorMindSpecificMessages') && $this->settings->get('RIMPlavorMindSpecificMessages')) {
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
      if ($this->settings->get('RIMEnglishSystemUsers')) {
        $lckey = $rimKey;
      }

      return;
    }

    $msgCache = MediaWikiServices::getInstance()->getMessageCache();

    // getMsgFromNamespace() can return a string.
    if ($msgCache->getMsgFromNamespace(ucfirst($lckey), $this->settings->get('LanguageCode')) === false) {
      $lckey = $rimKey;
    }
  }

  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short) {
    if (!($this->enabled && $action === 'edit' && $title->inNamespace(NS_MEDIAWIKI) && str_starts_with($title->getRootText(), 'Rim-'))) {
      return;
    }

    // Messages which start with "rim-" should never be edited on-wiki, so just override any other errors.
    $errors = [
      ['replaceinterfacemessages-cannot-edit-rim-msgs']
    ];
    return false;
  }
}
