<?php
namespace PlavorMind\PlavorMindTools\NoActionsOnNonEditable;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;

class Hooks implements \MediaWiki\Permissions\Hook\GetUserPermissionsErrorsHook
  {private $PermissionManager;

  public function __construct(PermissionManager $PermissionManager)
    {$this->PermissionManager=$PermissionManager;}

  public function onGetUserPermissionsErrors($title, $user, $action, &$result)
    {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('plavormindtools');

    if (!$pmt_config->get('PMTFeatureConfig')['NoActionsOnNonEditable']['enable'])
      {return;}

    $config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('noactionsonnoneditable');

    $denied_actions=['delete'];

    if ($config->get('PMTFeatureConfig')['NoActionsOnNonEditable']['HideMoveTab'])
      {$denied_actions[]='move';}

    if (in_array($action, $denied_actions) && !$this->PermissionManager->userCan('edit', $user, $title, 'quick'))
      {switch ($action)
        {default:
        $result=["noactionsonnoneditable-cannot{$action}cannotedit"];}
      return false;}
    }
  }
