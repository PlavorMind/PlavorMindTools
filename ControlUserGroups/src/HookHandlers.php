<?php
namespace PlavorMind\PlavorMindTools\ControlUserGroups;
use MediaWiki\Hook\BlockIpHook;

class HookHandlers implements BlockIpHook {
  private $settings;

  public function __construct($settings) {
    $this->settings = $settings;
  }

  public function onBlockIp($block, $user, &$reason) {
    if (!$this->settings->get('CUGEnable')) {
      return;
    }

    $centralAuthGroupHierarchies = $this->settings->get('CUGCentralAuthHierarchies');
    $groupHierarchies = $this->settings->get('CUGHierarchies');

    if ($centralAuthGroupHierarchies === null && $groupHierarchies === null) {
      return;
    }

    $targetIdentity = $block->getTargetUserIdentity();

    if ($user->equals($targetIdentity)) {
      $reason = ['controlusergroups-cannot-block-hierarchy'];
      return false;
    }

    $userGroupHierarchies = new UserGroupHierarchies($groupHierarchies, $centralAuthGroupHierarchies);
    $enforcerHierarchy = $userGroupHierarchies->getUserHierarchy($user);
    $targetHierarchy = $userGroupHierarchies->getUserHierarchy($targetIdentity);

    if ($enforcerHierarchy > $targetHierarchy) {
      return;
    }

    $reason = ['controlusergroups-cannot-block-hierarchy'];
    return false;
  }
}
