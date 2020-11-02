<?php
//=============================
// 変数
//=============================
define('MSG01','Eメールの形式で入力してください');
define('MSG02','入力必須です');
define('MSG03','半角数字のみご利用いただけます');
//=============================
// グローバル変数
//=============================
// エラーメッセージを格納ための配列
$is_error = array();
//=============================
// バリデーション関数
//=============================
// メール形式チェック
function validEmail($str, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$str)){
        global $is_error;
        $is_error[$key] = MSG01;
    } else {
        global $is_error;
        $is_error[$key] = '';
    }
}
// 未入力チェック
function validRequired($str, $key){
    if(empty($str)){
        global $is_error;
        $is_error[$key] = MSG02;
    } else {
        global $is_error;
        $is_error[$key] = '';
    }
}
// 半角数字チェック
function validHalf($str, $key){
    if(!preg_match("/^[0-9]+$/", $str)){
        global $is_error;
        $is_error[$key] = MSG03;
    } else {
        global $is_error;
        $is_error[$key] = '';
    }
}

if(!empty($_POST)){
    // 人数が入力されたら
    if(!empty($_POST['people'])){
        validHalf($_POST['people'], 'people');
        if(empty($is_error['people'])){
            echo json_encode(array(
                'errorFlgPeople' => false,
                'msgPeople' => '',
            ));
        }else{
            echo json_encode(array(
                'errorFlgPeople' => true,
                'msgPeople' => $is_error['people'],
            ));
        }
    }
    // Eメールが入力されたら
    if(!empty($_POST['email'])){
        validEmail($_POST['email'],'email');
        if(empty($is_error['email'])){
            echo json_encode(array(
                'errorFlgEmail' => false,
                'msgEmail' => '',
            ));
        }else{
            echo json_encode(array(
                'errorFlgEmail' => true,
                'msgEmail' => $is_error['email'],
            ));
        }
    }
    exit();
}

?>