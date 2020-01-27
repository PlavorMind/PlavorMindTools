# PlavorMindTools
**PlavorMindTools**는 PlavorMind 위키를 위한 유용한 기능을 제공하는 미디어위키용 확장 기능입니다.

이 확장 기능은 PlavorMind 위키를 위해 제작되었지만, 일부 기능은 다른 위키에서도 유용할 것입니다.
## 기능
### `BlueCategoryLinks`
미디어위키가 모든 분류 링크를 있는 문서의 링크 (파란 링크)처럼 취급하도록 합니다
### `NoActionsOnNonEditable`
사용자가 편집할 수 없는 문서에서 명령을 실행하는 것을 방지합니다

기본적으로, 경우에 따라 사용자가 삭제할 권한은 있지만 편집할 권한은 없는 문서를 삭제하는 것이 가능합니다. 이 기능을 사용하면 이는 더 이상 불가능합니다.

또한 사용자가 문서를 이동할 권한은 있지만 편집할 권한은 없는 경우 실제로 해당 문서를 이동할 수 없음에도 불구하고 이동 탭이 표시되는 경우가 있습니다. 이 기능은 이러한 경우 이동 탭을 숨기는 것을 지원하지만, 연쇄 보호는 지원하지 않습니다. 이 옵션을 활성화하려면 `$wgPMTFeatureConfig["NoActionsOnNonEditable"]["HideMoveTab"]`을 `true`로 설정하세요.
### `ReplaceInterfaceMessages`
일부 인터페이스 메시지를 이 확장 기능이 정의한 대로 교체합니다

이 확장 기능에서 제공하는 인터페이스 메시지는 최소한의 정보로 단순화되는 것을 목표로 합니다. 일부 메시지는 알림 상자 CSS 클래스 (`errorbox`, `messagebox`, `successbox` or `warningbox`)로 둘러싸여 있으며 스타일이 적용됩니다.

이 기능은 일부 시스템 사용자가 `$wgLanguageCode`에 관계 없이 항상 영어 사용자 이름을 사용하도록 강제하는 것도 지원합니다. 이 옵션은 글로벌 계정과 서로 다른 언어를 사용하는 위키에서 유용할 수 있습니다. 이 옵션을 활성화하려면 `$wgPMTFeatureConfig["ReplaceInterfaceMessages"]["EnglishSystemUsers"]`를 `true`로 설정하세요.
## 설정
|변수|설명|기본값|
|-|-|-|
|`$wgPMTFeatureConfig`|기능 구성|_(아래 참조)_|
* `$wgPMTFeatureConfig`의 기본값:
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
언어: [English](https://github.com/PlavorMind/PlavorMindTools/blob/master/readme.md) **한국어**
