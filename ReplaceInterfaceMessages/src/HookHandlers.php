<?php
declare(strict_types = 1);

namespace PlavorMind\PlavorMindTools\ReplaceInterfaceMessages;
use MediaWiki\Cache\Hook\MessageCache__getHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;

class HookHandlers implements MessageCache__getHook, TitleQuickPermissionsHook {
  private readonly bool $enabled;
  private readonly bool $newHookExists;
  private array $newMsgKeys = [];
  private $settings;

  public function __construct($settings) {
    $this->enabled = $settings->get('RIMEnable');
    $this->newHookExists = interface_exists('MediaWiki\\Cache\\Hook\\MessageCacheFetchOverridesHook');
    $this->settings = $settings;

    if (!$this->enabled || $this->newHookExists) {
      return;
    }

    $directories = $settings->get('MessagesDirs')['ReplaceInterfaceMessages'];

    foreach ($directories as $directory) {
      $msgFileContent = file_get_contents("$directory/en.json");
      $msgArray = json_decode($msgFileContent, true);
      $this->newMsgKeys = array_merge($this->newMsgKeys, array_keys($msgArray));
    }
  }

  public function onMessageCache__get(&$lckey) {
    if (!$this->enabled || $this->newHookExists) {
      return;
    }
    elseif (in_array("rim-plavormind-$lckey", $this->newMsgKeys) && $this->settings->has('RIMPlavorMindSpecificMessages') && $this->settings->get('RIMPlavorMindSpecificMessages')) {
      $newKey = "rim-plavormind-$lckey";
    }
    elseif (in_array("rim-$lckey", $this->newMsgKeys)) {
      $newKey = "rim-$lckey";
    }
    else {
      return;
    }

    $forcedKeys = [];

    if (in_array($lckey, $forcedKeys)) {
      $lckey = $newKey;
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
        $lckey = $newKey;
      }

      return;
    }

    $msgCache = MediaWikiServices::getInstance()->getMessageCache();

    if ($msgCache->getMsgFromNamespace(ucfirst($lckey), $this->settings->get('LanguageCode')) === false) {
      $lckey = $newKey;
    }
  }

  // 1.41+
  public function onMessageCacheFetchOverrides(array &$keys): void {
    if (!$this->enabled) {
      return;
    }

    $directories = $this->settings->get('MessagesDirs')['ReplaceInterfaceMessages'];
    $newKeys = [];

    foreach ($directories as $directory) {
      $msgFileContent = file_get_contents("$directory/en.json");
      $msgArray = json_decode($msgFileContent, true);
      $newKeys = array_merge($newKeys, array_keys($msgArray));
    }

    $forcedKeys = [];
    $language = $this->settings->get('LanguageCode');
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
    $useEnglishSystemUsers = $this->settings->get('RIMEnglishSystemUsers');
    // $wgRIMPlavorMindSpecificMessages is not defined in extension.json.
    $usePlavorMindMsgs = $this->settings->has('RIMPlavorMindSpecificMessages') && $this->settings->get('RIMPlavorMindSpecificMessages');

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

      $keys[$oldKey] = function (string $key, $cache) use ($language, $newKey): string {
        $uppercaseFirstKey = ucfirst($key);
        // getMsgFromNamespace() can return a string.
        return ($cache->getMsgFromNamespace($uppercaseFirstKey, $language) === false) ? $newKey : $key;
      };
    }
  }

  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short) {
    if (!($this->enabled && $action === 'edit' && $title->inNamespace(NS_MEDIAWIKI) && str_starts_with($title->getRootText(), 'Rim-'))) {
      return;
    }

    // Messages that start with "rim-" should never be edited on-wiki, so just override any other errors.
    $errors = [
      ['replaceinterfacemessages-cannot-edit-rim-msgs']
    ];
    return false;
  }
}
