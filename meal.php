<?php
$schooldept = $_GET['dept'];
$schoolcode = $_GET['code'];
$mealdate = $_GET['date'];

switch ($schooldept) {
    case '서울': $deptcode = 'B10'; break;
    case '부산': $deptcode = 'C10'; break;
    case '대구': $deptcode = 'D10'; break;
    case '인천': $deptcode = 'E10'; break;
    case '광주': $deptcode = 'F10'; break;
    case '대전': $deptcode = 'G10'; break;
    case '울산': $deptcode = 'H10'; break;
    case '세종': $deptcode = 'I10'; break;
    case '경기': $deptcode = 'J10'; break;
    case '강원': $deptcode = 'K10'; break;
    case '충북': $deptcode = 'M10'; break;
    case '충남': $deptcode = 'N10'; break;
    case '전북': $deptcode = 'P10'; break;
    case '전남': $deptcode = 'Q10'; break;
    case '경북': $deptcode = 'R10'; break;
    case '경남': $deptcode = 'S10'; break;
    case '제주': $deptcode = 'T10'; break;
    default: $deptcode = $schooldept; break;
}

if(empty($mealdate)){
    $mealdate = date('Ymd');
}

if((empty($schooldept)) || (empty($schoolcode))){
    $array = array(
        'status' => 'fail',
        'message' => 'no input',
        'messageko' => '입력 값을 확인해주세요.'
    );
} else{
    $apiKey = "";//여기에 https://open.neis.go.kr/ 에서 발급 받은 API 키를 입력해주세요.

    $apiCall = file_get_contents("https://open.neis.go.kr/hub/mealServiceDietInfo?Type=json&KEY=$apiKey&ATPT_OFCDC_SC_CODE=$deptcode&SD_SCHUL_CODE=$schoolcode&MLSV_YMD=$mealdate");
    
    $failArray = json_decode($apiCall, true);

    $a = json_encode($apiCall, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    $b = stripslashes($a);
    $c = substr($b, 1, -1);

    $apiResult = json_decode($c, true);

    $resultFail = $failArray['RESULT']['MESSAGE'];
    $resultSuccess = $apiResult['mealServiceDietInfo'][0]['head'][1]['RESULT']['MESSAGE']; //예) 정상 처리되었습니다.
    $apiDate = $apiResult['mealServiceDietInfo'][1]['row'][0]['MLSV_YMD']; //예) 20210803   Ymd
    $apiDish = $apiResult['mealServiceDietInfo'][1]['row'][0]['DDISH_NM']; // firstmeal1.1..<br\/>secondmeal1.1..<br\/>
    $apiMeal = preg_replace('/[0-9\-.*]/', '', $apiDish); //firstmeal<br/>secondmeal<br/>thirdmeal
    $apiMealOut = str_replace("<br/>", "\n", $apiMeal);
    $apiCal = $apiResult['mealServiceDietInfo'][1]['row'][0]['CAL_INFO']; // 512.5 KCal

    if($resultSuccess == '정상 처리되었습니다.'){
        $array = array(
            'status' => 'success',
            'date' => $apiDate,
            'meal' => $apiMealOut,
            'cal' => $apiCal
        );
    } elseif(isset($resultFail)){
        if($resultFail == '해당하는 데이터가 없습니다.'){
            $array = array(
                'status' => 'fail',
                'message' => 'no meal',
                'messageko' => '해당 날짜에 급식이 없습니다.'
            );
        } else{
            $array = array(
                'status' => 'fail',
                'message' => 'no value',
                'messageko' => '정보를 가져올 수 없습니다.'
            );
        }
    }
}

echo json_encode($array, JSON_UNESCAPED_UNICODE);

?>