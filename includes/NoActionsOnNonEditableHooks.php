<?php
use MediaWiki\MediaWikiServices;

class NoActionsOnNonEditableHooks
{public static function ongetUserPermissionsErrors($title,$user,$action,&$result)
  {$config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");
  if ($config->get("PMTFeatureConfig")["NoActionsOnNonEditable"]["enable"])
    {$PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();
    //$action should be checked before $PermissionManager->userCan to avoid "Allowed memory size of N bytes exhausted" error
    if ($action=="delete" && !$PermissionManager->userCan("edit",$user,$title,"quick"))
      {$result=["noactionsonnoneditable-cannotdeletecannotedit"];
      return false;}
    if ($config->get("PMTFeatureConfig")["NoActionsOnNonEditable"]["HideMoveTab"] && $action=="move" && !$PermissionManager->userCan("edit",$user,$title,"quick"))
      {$result=["noactionsonnoneditable-cannotmovecannotedit"];
      return false;}
    }
  }
}
?>
