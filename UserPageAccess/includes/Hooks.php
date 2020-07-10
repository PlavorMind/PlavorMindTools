<?php
namespace PlavorMind\PlavorMindTools\UserPageAccess;
use MediaWiki\MediaWikiServices;

class Hooks implements \MediaWiki\Permissions\Hook\GetUserPermissionsErrorsHook,\MediaWiki\Hook\MovePageCheckPermissionsHook,\MediaWiki\Permissions\Hook\TitleQuickPermissionsHook
{public function onGetUserPermissionsErrors($title,$user,$action,&$result)
  {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if (!$pmt_config->get("PMTFeatureConfig")["UserPageAccess"]["enable"])
    {return;}

  $PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();

  if ($action === "edit" && $title->getNamespace() === NS_USER && !($title->getRootText() === $user->getName() || $PermissionManager->userHasRight($user,"editotheruserpages")))
    {$result=["userpageaccess-cannotedituserpage"];
    return false;}
  }
public function onMovePageCheckPermissions($oldTitle,$newTitle,$user,$reason,$status)
  {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if (!$pmt_config->get("PMTFeatureConfig")["UserPageAccess"]["enable"])
    {return;}

  $PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();

  if ($oldTitle->getNamespace() === NS_USER
  && $oldTitle->getRootText() === $user->getName()
  && !$PermissionManager->userHasRight($user,"move")
  && $PermissionManager->userHasRight($user,"moveownuserpages")
  && $status->hasMessage("movenotallowed")
  && count($status->getErrorsArray()) === 1)
    {$status->setOK(true);}
  }
public function onTitleQuickPermissions($title,$user,$action,&$errors,$doExpensiveQueries,$short)
  {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if (!$pmt_config->get("PMTFeatureConfig")["UserPageAccess"]["enable"])
    {return;}

  $PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();

  $allowed_actions=["delete","move"];
  //$action should be checked before $PermissionManager->userCan to avoid "Allowed memory size of N bytes exhausted" error
  if (in_array($action,$allowed_actions) && $title->getNamespace() === NS_USER && $title->getRootText() === $user->getName() && $PermissionManager->userCan("edit",$user,$title,"quick"))
    {switch ($action)
      {default:
      if (!$PermissionManager->userHasRight($user,$action) && $PermissionManager->userHasRight($user,$action."ownuserpages"))
        {return false;}
      }
    }
  }
}
