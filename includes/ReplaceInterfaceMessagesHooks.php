<?php
if (!defined("MEDIAWIKI"))
{exit("This is not a valid entry point.");}

class ReplaceInterfaceMessagesHooks
{public static function onMessageCache_get(&$lckey)
  {global $wgLanguageCode,$wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["ReplaceInterfaceMessages"]["enable"])
    {$messages=
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
    "newsectionheaderdefaultlevel",
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
    $cache=MessageCache::singleton();
    if ($wgPMTFeatureConfig["ReplaceInterfaceMessages"]["EnglishSystemUsers"]&&in_array($lckey,$systemusers))
      {$lckey="rim-systemuser-".$lckey;}
    elseif (in_array($lckey,$messages)&&!$cache->getMsgFromNamespace(ucfirst($lckey),$wgLanguageCode))
      {$lckey="rim-".$lckey;}
    }
  }
}
?>
