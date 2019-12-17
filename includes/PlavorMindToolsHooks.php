<?php
use MediaWiki\MediaWikiServices;
if (!defined("MEDIAWIKI"))
{exit("This is not a valid entry point.");}

class PlavorMindToolsHooks
{public static function onBeforePageDisplay(OutputPage $out,Skin $skin)
  {if ($skin->getSkinName()=="liberty")
    {$out->addModuleStyles("plavormindtools-liberty-fix");}
  }
public static function ongetUserPermissionsErrors($title,$user,$action,&$result)
  {global $wgPMTEnableTools;
  if ($wgPMTEnableTools["protectuserpages"])
    {if ($title->getNamespace()==NS_USER&&$action=="edit")
      {if (!($title->getRootText()==$user->getName()||MediaWikiServices::getInstance()->getPermissionManager()->userHasRight($user,"editotheruserpages")))
        {$result=["plavormindtools-cannotedituserpage"];
        return false;}
      }
    }
  }
public static function onMessageCache_get(&$lckey)
  {global $wgLanguageCode,$wgPMTEnableTools,$wgPMTEnglishSystemUsers,$wgPMTPlavorMindMessages;
  if ($wgPMTEnableTools["pmtmsg"])
    {$pmtmsg=
    [//babel
    "babel-footer",
    "babel-footer-url",
    "babel-url",

    //core
    "aboutpage",
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
    "disclaimerpage",
    "editinginterface",
    "editingold",
    "excontent",
    "excontentauthor",
    "grouppage-autoconfirmed",
    "grouppage-bot",
    "grouppage-bureaucrat",
    "grouppage-interface-admin",
    "grouppage-sysop",
    "grouppage-user",
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
    "privacypage",
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
    "translateinterface",
    "undo-summary",
    "unprotect",
    "unprotectedarticle-comment",
    "userpage-userdoesnotexist",
    "userpage-userdoesnotexist-view",
    "viewsource",
    
    //titleblacklist
    "titleblacklist-warning"];
    if (in_array($lckey,$pmtmsg))
      {$cache=MessageCache::singleton();
      if (!($cache->getMsgFromNamespace(ucfirst($lckey),$wgLanguageCode)))
        {$lckey="pmtmsg-".$lckey;}
      }

    $plavormindmsg=
    [//plavormindtools
    "grouppage-steward"];
    if ($wgPMTPlavorMindMessages&&in_array($lckey,$plavormindmsg))
      {$cache=MessageCache::singleton();
      if (!($cache->getMsgFromNamespace(ucfirst($lckey),$wgLanguageCode)))
        {$lckey="plavormind-".$lckey;}
      }

    $plavormindmsg_force=[];
    if ($wgPMTPlavorMindMessages&&in_array($lckey,$plavormindmsg_force))
      {$lckey="plavormind-".$lckey;}

    $systemusers=
    [//abusefilter-systemusers
    "abusefilter-blocker",

    //babel-systemusers
    "babel-autocreate-user",

    //core-systemusers
    "autochange-username",
    "double-redirect-fixer",
    "proxyblocker",
    "spambot_username",
    "usermessage-editor"];
    if ($wgPMTEnglishSystemUsers&&in_array($lckey,$systemusers))
      {$lckey="pmtmsg-".$lckey;}
    }
  }
public static function onTitleIsAlwaysKnown($title,&$result)
  {global $wgPMTEnableTools;
  if ($wgPMTEnableTools["bluecategorylinks"])
    {if ($title->getNamespace()==NS_CATEGORY)
      {$result=true;}
    }
  }
}
?>
