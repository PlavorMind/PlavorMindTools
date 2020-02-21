<?php
use MediaWiki\MediaWikiServices;

class UserPageAccessHooks
{public static function ongetUserPermissionsErrors($title,$user,$action,&$result)
  {$config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");
  if ($config->get("PMTFeatureConfig")["UserPageAccess"]["enable"])
    {if ($action=="edit" && $title->getNamespace()==NS_USER)
      {if (!($title->getRootText()==$user->getName() || MediaWikiServices::getInstance()->getPermissionManager()->userHasRight($user,"editotheruserpages")))
        {$result=["userpageaccess-cannotedituserpage"];
        return false;}
      }
    }
  }
public static function onMovePageCheckPermissions(Title $oldTitle,Title $newTitle,User $user,$reason,Status $status)
  {$config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");
  if ($config->get("PMTFeatureConfig")["UserPageAccess"]["enable"])
    {$PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();
    if ($oldTitle->getNamespace()==NS_USER
    && $oldTitle->getRootText()==$user->getName()
    && !$PermissionManager->userHasRight($user,"move")
    && $PermissionManager->userHasRight($user,"moveownuserpages")
    && $status->hasMessage("movenotallowed")
    && count($status->getErrorsArray())==1)
      {$status->setOK(true);}
    }
  }
public static function onTitleQuickPermissions($title,$user,$action,&$errors,$doExpensiveQueries,$short)
  {$config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");
  if ($config->get("PMTFeatureConfig")["UserPageAccess"]["enable"])
    {$PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();
    if ($title->getNamespace()==NS_USER && $title->getRootText()==$user->getName())
      {//$action should be checked before $PermissionManager->userCan to avoid "Allowed memory size of N bytes exhausted" error
      if ($action=="delete" && !$PermissionManager->userHasRight($user,"delete") && $PermissionManager->userHasRight($user,"deleteownuserpages") && $PermissionManager->userCan("edit",$user,$title,"quick"))
        {return false;}
      if ($action=="move" && !$PermissionManager->userHasRight($user,"move") && $PermissionManager->userHasRight($user,"moveownuserpages") && $PermissionManager->userCan("edit",$user,$title,"quick"))
        {return false;}
      }
    }
  }
}
?>
