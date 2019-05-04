<?php
if (!defined("MEDIAWIKI"))
{die("You don't have permission to do that.");}

class PlavorMindToolsHooks
{public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
  {$out->addModuleStyles(["bulma_notification"]);}
public static function onBeforePageDisplayMobile(OutputPage $out,Skin $sk)
  {$out->addModuleStyles(["bulma_notification"]);}
public static function onMessageCache_get(&$lckey)
  {global $wgLanguageCode;
  //main
  $msgtoreplace=
  ["aboutpage",
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
  "disclaimerpage",
  "editinginterface",
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
  "right-editinterface",
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
  "viewsource"];
  //permissions
  $msgtoreplace=array_merge($msgtoreplace,
  ["group-bot",
  "group-bot-member",
  "group-interface-admin",
  "group-interface-admin-member",
  "group-sysop",
  "group-sysop-member"]);
  if (in_array($lckey,$msgtoreplace))
    {$cache=MessageCache::singleton();
    if (!($cache->getMsgFromNamespace(ucfirst($lckey),$wgLanguageCode)))
      {$lckey="pmtmsg-{$lckey}";}
    }
  }
}
?>