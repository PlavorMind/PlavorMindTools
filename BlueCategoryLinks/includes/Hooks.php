<?php
namespace PlavorMind\PlavorMindTools\BlueCategoryLinks;
use MediaWiki\MediaWikiServices;

class Hooks
{public static function onTitleIsAlwaysKnown($title,&$result)
  {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig("plavormindtools");

  if (!$pmt_config->get("PMTFeatureConfig")["BlueCategoryLinks"]["enable"])
    {return;}

  if ($title->getNamespace() === NS_CATEGORY)
    {$result=true;}
  }
}