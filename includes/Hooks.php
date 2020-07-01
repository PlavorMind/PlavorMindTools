<?php
namespace PlavorMind\PlavorMindTools;
use MediaWiki\MediaWikiServices;

class Hooks
{public static function onUserGetAllRights(&$rights)
  {$config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if ($config->get("PMTFeatureConfig")["UserPageAccess"]["enable"])
    {$rights=array_merge($rights,
    ["deleteownuserpages",
    "editotheruserpages",
    "moveownuserpages"]);}
  }
}
