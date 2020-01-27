# PlavorMindTools
**PlavorMindTools** is a extension for MediaWiki that provides useful features for PlavorMind wikis.

This extension is written for PlavorMind wikis in mind, but some features will also be useful on other wikis.
## Features
### `BlueCategoryLinks`
Makes MediaWiki treat all category links as link to existing pages (blue link)
### `NoActionsOnNonEditable`
Prevents users from executing actions on pages they cannot edit

By default, in some cases it is possible to delete pages that users have permission to delete them but not edit. With this feature, this is no longer possible.

Also in some cases the Move tab appears when a user has permission to move pages but not edit, even though they cannot actually move them. This feature supports hiding the Move tab in such cases, but not cascade protection. To enable this option, set `$wgPMTFeatureConfig["NoActionsOnNonEditable"]["HideMoveTab"]` to `true`.
### `ReplaceInterfaceMessages`
Replaces some interface messages as defined by this extension

Interface messages provided by this extension aims to be simplified with minimal information. Some messages are wrapped by message box CSS class (`errorbox`, `messagebox`, `successbox` or `warningbox`) and stylized.

This feature also supports forcing some system users to always use English usernames, regardless of `$wgLanguageCode`. This option may be useful on wikis that uses global accounts and different languages. To enable this option, set `$wgPMTFeatureConfig["ReplaceInterfaceMessages"]["EnglishSystemUsers"]` to `true`.
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
  "EnglishSystemUsers"=>false]
]
```
---
Languages: **English** [한국어](https://github.com/PlavorMind/PlavorMindTools/blob/master/readme-ko.md)
