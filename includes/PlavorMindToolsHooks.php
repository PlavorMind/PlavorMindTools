<?php
use MediaWiki\MediaWikiServices;
if (!defined("MEDIAWIKI"))
{exit("This is not a valid entry point.");}

class PlavorMindToolsHooks
{public static function ongetUserPermissionsErrors($title,$user,$action,&$result)
  {global $wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["NoActionsOnNonEditable"]["enable"])
    {//$action should be checked first to avoid "Allowed memory size of N bytes exhausted" error
    if ($action=="delete"&&!MediaWikiServices::getInstance()->getPermissionManager()->userCan("edit",$user,$title,"quick"))
      {$result=["noactionsonnoneditable-cannotdeletecannotedit"];
      return false;}
    if ($wgPMTFeatureConfig["NoActionsOnNonEditable"]["HideMoveTab"]&&$action=="move"&&!MediaWikiServices::getInstance()->getPermissionManager()->userCan("edit",$user,$title,"quick"))
      {$result=["noactionsonnoneditable-cannotmovecannotedit"];
      return false;}
    }
  if ($wgPMTFeatureConfig["ManageOwnUserPages"]["enable"])
    {if ($action=="edit"&&$title->getNamespace()==NS_USER)
      {if (!($title->getRootText()==$user->getName()||MediaWikiServices::getInstance()->getPermissionManager()->userHasRight($user,"editotheruserpages")))
        {$result=["manageownuserpages-cannotedituserpage"];
        return false;}
      }
    }
  }
public static function onMessageCache_get(&$lckey)
  {global $wgLanguageCode,$wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["ReplaceInterfaceMessages"]["enable"])
    {$messages=
    [//babel-englishonly
    "babel-footer",
    "babel-footer-url",
    "babel-url",

    //core
    "allmessages",
    "badaccess-group0",
    "badaccess-groups",
    "block-log-flags-anononly",
    "block-log-flags-noemail",
    "block-log-flags-nousertalk",
    "blocked-notice-logextract",
    "blockedtext",
    "blocklist-nousertalk",
    "cascadeprotected",
    "cascadeprotectedwarning",
    "contribslink",
    "contributions-userdoesnotexist",
    "customcssprotected",
    "customjsprotected",
    "customjsonprotected",
    "editinginterface",
    "editingold",
    "excontent",
    "excontentauthor",
    "infiniteblock",
    "listfiles-userdoesnotexist",
    "listgrouprights",
    "logentry-move-move",
    "logentry-move-move-noredirect",
    "logentry-protect-modify",
    "logentry-protect-modify-cascade",
    "logentry-protect-protect",
    "logentry-protect-protect-cascade",
    "logentry-protect-unprotect",
    "modifiedarticleprotection-comment",
    "moveddeleted-notice",
    "moveddeleted-notice-recent",
    "namespaceprotected",
    "newarticletext",
    "newsectionheaderdefaultlevel",
    "noarticletext",
    "noarticletext-nopermission",
    "nocreate-loggedin",
    "nocreatetext",
    "nstab-help",
    "nstab-mediawiki",
    "nstab-project",
    "permissionserrorstext",
    "permissionserrorstext-withaction",
    "protect-default",
    "protect-fallback",
    "protect-legend",
    "protect-summary-cascade",
    "protect-summary-desc",
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
    "privacypage",
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
public static function onTitleIsAlwaysKnown($title,&$result)
  {global $wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["BlueCategoryLinks"]["enable"])
    {if ($title->getNamespace()==NS_CATEGORY)
      {$result=true;}
    }
  }
public static function onTitleQuickPermissions($title,$user,$action,&$errors,$doExpensiveQueries,$short)
  {global $wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["ManageOwnUserPages"]["enable"])
    {$PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();
    if ($title->getNamespace()==NS_USER&&$title->getRootText()==$user->getName())
      {//Moving own user pages cannot be allowed in this way
      if ($action=="delete"&&!$PermissionManager->userHasRight($user,"delete")&&$PermissionManager->userHasRight($user,"deleteownuserpages")&&$PermissionManager->userCan("edit",$user,$title,"quick"))
        {return false;}
      }
    }
  }
}
?>
