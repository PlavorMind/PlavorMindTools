<?php
namespace PlavorMind\PlavorMindTools\NoActionsOnNonEditable;
use MediaWiki\MediaWikiServices;

class Hooks implements \MediaWiki\Permissions\Hook\GetUserPermissionsErrorsHook
{public function onGetUserPermissionsErrors($title,$user,$action,&$result)
  {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if (!$pmt_config->get("PMTFeatureConfig")["NoActionsOnNonEditable"]["enable"])
    {return;}

  $config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("noactionsonnoneditable");
  $PermissionManager=MediaWikiServices::getInstance()->getPermissionManager();

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
