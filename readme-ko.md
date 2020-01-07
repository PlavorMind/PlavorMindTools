# PlavorMindTools
**PlavorMindTools**는 PlavorMind 위키를 위한 도구를 제공하는 미디어위키용 확장 기능입니다.
## 설치
1. 확장 기능을 다운로드하고 `extensions` 디렉토리에 넣으세요.
1. `LocalSettings.php` 파일에 다음을 추가하세요:
```php
wfLoadExtension("PlavorMindTools");
```
## 설정
|변수|설명|기본값|
|-|-|-|
|`$wgPMTEnableTools`|활성화할 도구|_(아래 참조)_|
|`$wgPMTEnglishSystemUsers`|시스템 사용자에게 항상 영어 사용자 이름을 사용할지 여부 (`pmtmsg` 도구 필요)|`false`|
|`$wgPMTPlavorMindMessages`|일부 메시지를 PlavorMind 관련 메시지로 교체할지 여부 (`pmtmsg` 도구 필요)|`false`|
* `$wgPMTEnableTools`의 기본값:
```php
["bluecategorylinks"=>false,
"noactionsonnoneditable"=>false,
"pmtmsg"=>false,
"protectuserpages"=>false]
```
