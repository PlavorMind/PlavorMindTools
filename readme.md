# PlavorMindTools
MediaWiki extension for providing tools for PlavorMind wikis
## Installation
Add this to `LocalSettings.php` file:
```php
wfLoadExtension("PlavorMindTools");
```
## Configuration
- `$wgPMTMBoxCSSExemptSkins`: Array of skins to not apply message box CSS (default: `["plavorbuma"]`)
## Used software
- [Bulma](https://bulma.io/) (Licensed under the [MIT License](https://github.com/jgthms/bulma/blob/master/LICENSE))
