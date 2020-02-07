<?php
use MediaWiki\MediaWikiServices;
if (!defined("MEDIAWIKI"))
{exit("This is not a valid entry point.");}

class UserPageAccessHooks
{public static function ongetUserPermissionsErrors($title,$user,$action,&$result)
  {global $wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["UserPageAccess"]["enable"])
    {if ($action=="edit"&&$title->getNamespace()==NS_USER)
      {if (!($title->getRootText()==$user->getName()||MediaWikiServices::getInstance()->getPermissionManager()->userHasRight($user,"editotheruserpages")))
        {$result=["userpageaccess-cannotedituserpage"];
        return false;}
      }
    }
  }
public static function onTitleQuickPermissions($title,$user,$action,&$errors,$doExpensiveQueries,$short)
  {global $wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["UserPageAccess"]["enable"])
    {$PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();
    if ($title->getNamespace()==NS_USER&&$title->getRootText()==$user->getName())
      {if ($action=="delete"&&!$PermissionManager->userHasRight($user,"delete")&&$PermissionManager->userHasRight($user,"deleteownuserpages")&&$PermissionManager->userCan("edit",$user,$title,"quick"))
        {return false;}
      if ($action=="move"&&!$PermissionManager->userHasRight($user,"move")&&$PermissionManager->userHasRight($user,"moveownuserpages")&&$PermissionManager->userCan("edit",$user,$title,"quick"))
        {return false;}
      }
    }
  }
}
?>
