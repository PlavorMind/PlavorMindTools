<?php
declare(strict_types = 1);

namespace PlavorMind\PlavorMindTools\ReplaceInterfaceMessages;
use MediaWiki\Cache\Hook\MessageCacheFetchOverridesHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\TitleQuickPermissionsHook;

class HookHandlers implements MessageCacheFetchOverridesHook, TitleQuickPermissionsHook {
  private readonly bool $enabled;
  private $settings;

  public function __construct($settings) {
    $this->enabled = $settings->get('RIMEnable');
    $this->settings = $settings;
  }

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
