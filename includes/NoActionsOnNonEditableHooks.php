<?php
use MediaWiki\MediaWikiServices;

class NoActionsOnNonEditableHooks
{public static function ongetUserPermissionsErrors($title,$user,$action,&$result)
  {$config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if ($config->get("PMTFeatureConfig")["NoActionsOnNonEditable"]["enable"])
    {$PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();

    $denied_actions=["delete"];
    if ($config->get("PMTFeatureConfig")["NoActionsOnNonEditable"]["HideMoveTab"])
      {$denied_actions[]="move";}
    //$action should be checked before $PermissionManager->userCan to avoid "Allowed memory size of N bytes exhausted" error
    if (in_array($action,$denied_actions) && !$PermissionManager->userCan("edit",$user,$title,"quick"))
      {switch ($action)
        {default:
        $result=["noactionsonnoneditable-cannot".$action."cannotedit"];}
      return false;}
    }
  }
}
