<?php
namespace PlavorMind\PlavorMindTools\BlueCategoryLinks;
use MediaWiki\MediaWikiServices;

class Hooks
{public static function onTitleIsAlwaysKnown($title,&$result)
  {$config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");
  if ($config->get("PMTFeatureConfig")["BlueCategoryLinks"]["enable"])
    {if ($title->getNamespace() === NS_CATEGORY)
      {$result=true;}
    }
  }
}
