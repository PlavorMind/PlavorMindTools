<?php
namespace PlavorMind\PlavorMindTools\UserPageAccess;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;

class Hooks implements \MediaWiki\Permissions\Hook\GetUserPermissionsErrorsHook,\MediaWiki\Hook\MovePageCheckPermissionsHook,\MediaWiki\Permissions\Hook\TitleQuickPermissionsHook
  {private $PermissionManager;

  public function __construct(PermissionManager $PermissionManager)
    {$this->PermissionManager=$PermissionManager;}

  public function onGetUserPermissionsErrors($title, $user, $action, &$result)
    {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('plavormindtools');

    if (!$pmt_config->get('PMTFeatureConfig')['UserPageAccess']['enable'])
      {return;}

    if ($action === 'edit' && $title->getNamespace() === NS_USER && !($title->getRootText() === $user->getName() || $this->PermissionManager->userHasRight($user, 'editotheruserpages')))
      {$result=['userpageaccess-cannotedituserpage'];
      return false;}
    }

  public function onMovePageCheckPermissions($oldTitle, $newTitle, $user, $reason, $status)
    {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('plavormindtools');

    if (!$pmt_config->get('PMTFeatureConfig')['UserPageAccess']['enable'])
      {return;}

    if ($oldTitle->getNamespace() === NS_USER
    && $oldTitle->getRootText() === $user->getName()
    && !$this->PermissionManager->userHasRight($user, 'move')
    && $this->PermissionManager->userHasRight($user, 'moveownuserpages')
    && $status->hasMessage('movenotallowed')
    && count($status->getErrorsArray()) === 1)
      {$status->setOK(true);}
    }

  public function onTitleQuickPermissions($title, $user, $action, &$errors, $doExpensiveQueries, $short)
    {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('plavormindtools');

    if (!$pmt_config->get('PMTFeatureConfig')['UserPageAccess']['enable'])
      {return;}

    $allowed_actions=['delete', 'move'];

    if (in_array($action, $allowed_actions) && $title->getNamespace() === NS_USER && $title->getRootText() === $user->getName() && $this->PermissionManager->userCan('edit', $user, $title, 'quick'))
      {switch ($action)
        {default:
        if (!$this->PermissionManager->userHasRight($user, $action) && $this->PermissionManager->userHasRight($user, "{$action}ownuserpages"))
          {return false;}
        }
      }
    }
  }
