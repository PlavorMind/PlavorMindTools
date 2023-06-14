<?php
namespace PlavorMind\PlavorMindTools\ControlUserGroups;
use ExtensionRegistry;
use MediaWiki\Extension\CentralAuth\User\CentralAuthUser;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;

class UserGroupHierarchies {
  private readonly array $centralAuthHierarchies;
  private readonly bool $centralAuthLoaded;
  private readonly array $hierarchies;
  private $userGroupManager;

  public function __construct(?array $hierarchies, ?array $centralAuthHierarchies) {
    $this->centralAuthHierarchies = $centralAuthHierarchies ?? [];
    $this->centralAuthLoaded = ExtensionRegistry::getInstance()->isLoaded('CentralAuth');
    $this->hierarchies = $hierarchies ?? [];
    $this->userGroupManager = MediaWikiServices::getInstance()->getUserGroupManager();
  }

  public function getUserHierarchy(UserIdentity $userIdentity): int {
    // isAnon() does not exist in UserIdentity objects.
    if (!$userIdentity->isRegistered()) {
      return 0;
    }

    $hierarchies = [0];

    if (count($this->hierarchies) !== 0) {
      $groups = $this->userGroupManager->getUserEffectiveGroups($userIdentity);
      $groupsToCheck = array_diff($groups, ['*', 'user']);

      foreach ($groupsToCheck as $group) {
        if (isset($this->hierarchies[$group])) {
          $hierarchies[] = $this->hierarchies[$group];
        }
      }
    }

    if (!$this->centralAuthLoaded || count($this->centralAuthHierarchies) === 0) {
      return max($hierarchies);
    }

    $groups = CentralAuthUser::getInstance($userIdentity)->getGlobalGroups();

    foreach ($groups as $group) {
      if (isset($this->centralAuthHierarchies[$group])) {
        $hierarchies[] = $this->centralAuthHierarchies[$group];
      }
    }

    return max($hierarchies);
  }
}
