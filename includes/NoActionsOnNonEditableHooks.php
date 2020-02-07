<?php
use MediaWiki\MediaWikiServices;
if (!defined("MEDIAWIKI"))
{exit("This is not a valid entry point.");}

class NoActionsOnNonEditableHooks
{public static function ongetUserPermissionsErrors($title,$user,$action,&$result)
  {global $wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["NoActionsOnNonEditable"]["enable"])
    {$PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();
    //$action should be checked first to avoid "Allowed memory size of N bytes exhausted" error
    if ($action=="delete"&&!$PermissionManager->userCan("edit",$user,$title,"quick"))
      {$result=["noactionsonnoneditable-cannotdeletecannotedit"];
      return false;}
    if ($wgPMTFeatureConfig["NoActionsOnNonEditable"]["HideMoveTab"]&&$action=="move"&&!$PermissionManager->userCan("edit",$user,$title,"quick"))
      {$result=["noactionsonnoneditable-cannotmovecannotedit"];
      return false;}
    }
  }
}
?>
