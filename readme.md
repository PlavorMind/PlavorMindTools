# PlavorMindTools
**PlavorMindTools** is a extension for MediaWiki that provides useful features for PlavorMind wikis.

This extension is written for PlavorMind wikis in mind, but some features will also be useful on other wikis.
## Features
### `BlueCategoryLinks`
Makes MediaWiki treat all category links as link to existing pages (blue link)
### `NoActionsOnNonEditable`
Prevents users from executing actions on pages they cannot edit

By default, in some cases it is possible to delete pages that users have permission to delete them but not edit. With this feature, this is no longer possible.

Also in some cases the Move tab appears when a user has permission to move pages but not edit, even though they cannot actually move them. This feature supports hiding the Move tab in such cases, but it does not apply to cascade protection. To enable this option, set `$wgPMTFeatureConfig["NoActionsOnNonEditable"]["HideMoveTab"]` to `true`.
### `ReplaceInterfaceMessages`
Replaces some interface messages as defined by this extension

Interface messages provided by this extension aims to be simplified with minimal information. Some messages are wrapped by message box CSS class (`errorbox`, `messagebox`, `successbox` or `warningbox`) and stylized.

This feature also supports forcing some system users to always use English usernames, regardless of `$wgLanguageCode`. This option may be useful on wikis that uses global accounts and different languages. To enable this option, set `$wgPMTFeatureConfig["ReplaceInterfaceMessages"]["EnglishSystemUsers"]` to `true`.
### `UserPageAccess`
Adjust permissions for user pages

This feature prevents users without `editotheruserpages` permission from editing other users' user pages.

Also this feature allows users with `deleteownuserpages` permission to delete their own user pages and `moveownuserpages` permission to move their own user pages.
## Installation
1. Download, rename the directory to `PlavorMindTools` and put it in `extensions` directory.
1. Add the following to `LocalSettings.php` file:
```php
wfLoadExtension("PlavorMindTools");
```
## Settings
|Variable|Description|Default|
|-|-|-|
|`$wgPMTFeatureConfig`|Configurations for features|_(See below)_|
* Default value of `$wgPMTFeatureConfig`:
```php
["BlueCategoryLinks"=>
  ["enable"=>false],
"NoActionsOnNonEditable"=>
  ["enable"=>false,
  "HideMoveTab"=>false],
"ReplaceInterfaceMessages"=>
  ["enable"=>false,
  "EnglishSystemUsers"=>false],
"UserPageAccess"=>
  ["enable"=>false]
]
```
## License
Copyright (C) 2020 PlavorMind

This software is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more details.

You should have received [a copy of the GNU Affero General Public License](https://github.com/PlavorMind/PlavorMindTools/blob/master/LICENSE.txt) along with this software. If not, see <https://www.gnu.org/licenses/>.

---
Languages: **English** [한국어](https://github.com/PlavorMind/PlavorMindTools/blob/master/readme-ko.md)
