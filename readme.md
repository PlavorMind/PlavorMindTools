# PlavorMindTools
**PlavorMindTools** is an extension for MediaWiki to provide tools for PlavorMind wikis.
## Installation
1. Download this extension.
1. Add this to `LocalSettings.php` file:
```php
wfLoadExtension("PlavorMindTools");
```
## Configuration
|Variable|Description|Default|
|-|-|-|
|`$wgPMTEnableTools`|Array of enabled tools|_(See below)_|
|`$wgPMTEnglishSystemUsers`|Whether to always use English usernames for system users|`false`|
|`$wgPMTPlavorMindMessages`|Whether to replace some messages with PlavorMind-specific messages|`false`|
* Default value of `$wgPMTEnableTools`:
```php
["bluecategorylinks"=>false,
"pmtmsg"=>false,
"protectuserpages"=>false]
```
