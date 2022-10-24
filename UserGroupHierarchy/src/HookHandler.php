<?php
namespace PlavorMind\PlavorMindTools\UserGroupHierarchy;
use MediaWiki\Block\Block;
use MediaWiki\Hook\BlockIpHook;
use MediaWiki\MediaWikiServices;

class HookHandler implements BlockIpHook {
  private $MediaWikiServices;
  private $settings;

  public function __construct() {
    $this->MediaWikiServices = MediaWikiServices::getInstance();
    $this->settings = $this->MediaWikiServices->getMainConfig();
  }

  private function getUserHierarchy($userIdentity) {
    $groupHierarchies = $this->settings->get('UGHHierarchies');
    $groups = $this->MediaWikiServices->getUserGroupManager()->getUserEffectiveGroups($userIdentity);
    $hierarchies = [0];

    foreach ($groups as $group) {
      if (isset($groupHierarchies[$group])) {
        $hierarchies[] = $groupHierarchies[$group];
      }
    }

    return max($hierarchies);
  }

  public function onBlockIp($block, $user, &$reason) {
    if (!($this->settings->get('UGHEnable') && $block->getType() === Block::TYPE_USER)) {
      return;
    }
    elseif ($user->isAnon()) {
      $reason = ['usergrouphierarchy-cannot-block-hierarchy'];
      return false;
    }

    $enforcerIdentity = $this->MediaWikiServices->getUserIdentityLookup()->getUserIdentityByUserId($user->getId());
    $targetIdentity = $block->getTargetUserIdentity();

    if ($this->getUserHierarchy($enforcerIdentity) > $this->getUserHierarchy($targetIdentity)) {
      return;
    }

    $reason = ['usergrouphierarchy-cannot-block-hierarchy'];
    return false;
  }
}
