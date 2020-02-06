<?php
if (!defined("MEDIAWIKI"))
{exit("This is not a valid entry point.");}

class BlueCategoryLinksHooks
{public static function onTitleIsAlwaysKnown($title,&$result)
  {global $wgPMTFeatureConfig;
  if ($wgPMTFeatureConfig["BlueCategoryLinks"]["enable"])
    {if ($title->getNamespace()==NS_CATEGORY)
      {$result=true;}
    }
  }
}
?>