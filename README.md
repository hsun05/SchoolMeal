# SchoolMeal
나이스 API로 급식 API

## 요청 파라미터

- dept = 교육청 이름 또는 교육청 코드<br>
```
서울 B10
부산 C10
대구 D10
인천 E10
광주 F10
대전 G10
울산 H10
세종 I10
경기 J10
강원 K10
충북 M10
충남 N10
전북 P10
전남 Q10
경북 R10
경남 S10
제주 T10
```
- code = 학교 코드<br>
- date = 날짜(Ymd 형식)<br>

## 요청 방법
```
<?php

$api = json_decode(file_get_contents("https://example.com/meal.php?dept=지역&code=학교코드&date=날짜"), true);

echo $api;

?>
```

## 출력 값
-성공시
```
status = 상태  예) success
date = 날짜(Ymd 형식)
meal = 급식(firstmeal\nsecondmeal\nthirdmeal)
cal = 칼로리  예) 123.Kcal
```
-실패시
```
status = 상태  예) fail
message = 결과
messageko = 결과 한국어
```
