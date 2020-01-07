# PlavorMindTools
**PlavorMindTools** is extension for MediaWiki to provide tools for PlavorMind wikis.
## Installation
1. Download this extension and place it in `extensions` directory.
1. Add this to `LocalSettings.php` file:
```php
wfLoadExtension("PlavorMindTools");
```
## Configuration
|Variable|Description|Default|
|-|-|-|
|`$wgPMTEnableTools`|Which tools should be enabled|_(See below)_|
|`$wgPMTEnglishSystemUsers`|Whether to always use English usernames for system users (requires `pmtmsg` tool)|`false`|
|`$wgPMTPlavorMindMessages`|Whether to replace some messages with PlavorMind-specific messages (requires `pmtmsg` tool)|`false`|
* Default value of `$wgPMTEnableTools`:
```php
["bluecategorylinks"=>false,
"noactionsonnoneditable"=>false,
"pmtmsg"=>false,
"protectuserpages"=>false]
```
