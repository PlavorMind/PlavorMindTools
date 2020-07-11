<?php
namespace PlavorMind\PlavorMindTools\ReplaceInterfaceMessages;
use MediaWiki\MediaWikiServices;

class Hooks implements \MediaWiki\Cache\Hook\MessageCache__getHook
{public function onMessageCache__get(&$key)
  {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if (!$pmt_config->get("PMTFeatureConfig")["ReplaceInterfaceMessages"]["enable"])
    {return;}

  $config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("replaceinterfacemessages");

  $messages=
  [//babel-englishonly
  "babel-footer",
  "babel-footer-url",
  "babel-url",

  //core
  "allmessages",
  "anononlyblock",
  "badaccess-group0",
  "badaccess-groups",
  "block-log-flags-anononly",
  "block-log-flags-noemail",
  "blocked-notice-logextract",
  "blockedtext",
  "cascadeprotected",
  "cascadeprotectedwarning",
  "contribslink",
  "contributions-userdoesnotexist",
  "customcssprotected",
  "customjsprotected",
  "customjsonprotected",
  "editinginterface",
  "editingold",
  "emailblock",
  "excontent",
  "excontentauthor",
  "infiniteblock",
  "listfiles-userdoesnotexist",
  "listgrouprights",
  "logentry-move-move",
  "logentry-move-move-noredirect",
  "logentry-protect-modify",
  "logentry-protect-modify-cascade",
  "logentry-protect-move_prot",
  "logentry-protect-protect",
  "logentry-protect-protect-cascade",
  "logentry-protect-unprotect",
  "modifiedarticleprotection-comment",
  "moveddeleted-notice",
  "moveddeleted-notice-recent",
  "namespaceprotected",
  "newarticletext",
  "noarticletext",
  "noarticletext-nopermission",
  "nocreate-loggedin",
  "nocreatetext",
  "nstab-help",
  "nstab-mediawiki",
  "permissionserrorstext",
  "permissionserrorstext-withaction",
  "protect-cascade",
  "protect-default",
  "protect-fallback",
  "protect-summary-cascade",
  "protect-unchain-permissions",
  "protectedarticle-comment",
  "protectedinterface",
  "protectedpagemovewarning",
  "protectedpagetext",
  "protectedpagewarning",
  "recentchanges",
  "recreate-moveddeleted-warn",
  "revertpage",
  "right-editinterface",
  "semiprotectedpagemovewarning",
  "semiprotectedpagewarning",
  "sp-contributions-blocked-notice",
  "sp-contributions-blocked-notice-anon",
  "talk",
  "templatesused",
  "titleprotectedwarning",
  "undo-summary",
  "unprotect",
  "unprotectedarticle-comment",
  "userpage-userdoesnotexist",
  "userpage-userdoesnotexist-view",
  "viewsource",

  //core-englishonly
  "aboutpage",
  "disclaimerpage",
  "grouppage-autoconfirmed",
  "grouppage-bot",
  "grouppage-bureaucrat",
  "grouppage-interface-admin",
  "grouppage-sysop",
  "grouppage-user",
  "nstab-project",
  "privacypage",
  "protect-summary-desc",
  "translateinterface",

  //titleblacklist
  "titleblacklist-warning"];
  $systemusers=
  [//abusefilter-englishonly
  "abusefilter-blocker",

  //babel-englishonly
  "babel-autocreate-user",

  //core-englishonly
  "autochange-username",
  "double-redirect-fixer",
  "proxyblocker",
  "spambot_username",
  "usermessage-editor"];
  $cache=MediaWikiServices::getInstance()->getMessageCache();
  if ($config->get("PMTFeatureConfig")["ReplaceInterfaceMessages"]["EnglishSystemUsers"] && in_array($key,$systemusers))
    {$key="rim-systemuser-".$key;}
  elseif (in_array($key,$messages) && !$cache->getMsgFromNamespace(ucfirst($key),$config->get("LanguageCode")))
    {$key="rim-".$key;}
  }
}
