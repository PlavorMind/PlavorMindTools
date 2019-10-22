# PlavorMindTools
MediaWiki extension for providing tools for PlavorMind wikis
## Installation
Add this to `LocalSettings.php` file:
```php
wfLoadExtension("PlavorMindTools");
```
## Configuration
* `$wgPMTEnabledTools`: Array of enabled tools (default: `[]`)
* `$wgPMTEnglishSystemUsers`: Whether to always use English usernames for system users (default: `false`)
* `$wgPMTMBoxCSSExemptSkins`: Array of skins to not apply message box CSS (default: `["plavorbuma"]`)
* `$wgPMTPlavorMindMessages`: Whether to replace some messages with PlavorMind-specific messages (default: `false`)
## Used software
* [Bulma](https://bulma.io/) (Licensed under the [MIT License](https://github.com/jgthms/bulma/blob/master/LICENSE))