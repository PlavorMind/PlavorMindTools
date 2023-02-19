<?php
namespace PlavorMind\PlavorMindTools\ControlUserGroups;
use ExtensionRegistry;
use MediaWiki\Extension\CentralAuth\User\CentralAuthUser;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;

class UserGroupHierarchies {
  public static function getUserHierarchy(UserIdentity $userIdentity, array $groupHierarchies, array $centralAuthGroupHierarchies): int {
    // isAnon() does not exist in UserIdentity objects.
    if (!$userIdentity->isRegistered()) {
      return 0;
    }

    $hierarchies = [0];

    if (count($groupHierarchies) !== 0) {
      $groups = MediaWikiServices::getInstance()->getUserGroupManager()->getUserEffectiveGroups($userIdentity);
      $groupsToCheck = array_diff($groups, ['*', 'user']);

      foreach ($groupsToCheck as $group) {
        if (isset($groupHierarchies[$group])) {
          $hierarchies[] = $groupHierarchies[$group];
        }
      }
    }

    if (count($centralAuthGroupHierarchies) === 0 || !ExtensionRegistry::getInstance()->isLoaded('CentralAuth')) {
      return max($hierarchies);
    }

    $groups = CentralAuthUser::getInstance($userIdentity)->getGlobalGroups();

    foreach ($groups as $group) {
      if (isset($centralAuthGroupHierarchies[$group])) {
        $hierarchies[] = $centralAuthGroupHierarchies[$group];
      }
    }

    return max($hierarchies);
  }
}
