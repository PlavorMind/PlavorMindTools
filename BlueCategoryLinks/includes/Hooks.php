<?php
namespace PlavorMind\PlavorMindTools\BlueCategoryLinks;
use MediaWiki\MediaWikiServices;

class Hooks implements \MediaWiki\Hook\TitleIsAlwaysKnownHook
  {public function onTitleIsAlwaysKnown($title, &$isKnown)
    {$pmt_config=MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('plavormindtools');

    if (!$pmt_config->get('PMTFeatureConfig')['BlueCategoryLinks']['enable'])
      {return;}

    if ($title->getNamespace() === NS_CATEGORY)
      {$isKnown=true;}
    }
  }
