<?php
//================================
// 定数
//================================
define('MSG01', 'Eメールの形式で入力してください');
define('MSG02', '入力必須です');
define('MSG03', '半角数字のみご使用頂けます');
//================================
// グローバル変数
//================================
// エラーメッセージを格納するための配列
$is_err = array();
//================================
// バリデーション関数
//================================
// メールアドレスの形式チェック
function validEmail($str,$key){
	if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
		global $is_err;
		$is_err[$key] = MSG01;
	}else{
		global $is_err;
		$is_err[$key] = '';
	}
}
// 未入力チェック
function validRequired($str,$key){
	if(empty($str)){
		global $is_err;
		$is_err[$key] = MSG02;
	}else{
		global $is_err;
		$is_err[$key] = '';
	}
}
// 半角数字チェック
function validHalfnumber($str,$key){
	if(!preg_match("/^[0-9]+$/", $str)){
		global $is_err;
		$is_err[$key] = MSG03;
	}else{
		global $is_err;
		$is_err[$key] = '';
	}
}
if(!empty($_POST)){
	// 人数が入力された場合
	if(!empty($_POST['people'])){
		validHalfnumber($_POST['people'],'people');
		if(empty($is_err['people'])) {
				echo json_encode(array(
	            'errorFlgPeople' => false,
	            'msgPeople' => '',
	        ));
		}else{
			 echo json_encode(array(
	            'errorFlgPeople' => true,
	            'msgPeople' => $is_err['people'],
	        ));
		}
	}
	// 人数チェック
	// Eメールが入力された場合
	if(!empty($_POST['email'])){
		validEmail($_POST['email'],'email');
		if(empty($is_err['email'])) {
				echo json_encode(array(
	            'errorFlgEmail' => false,
	            'msgEmail' => '',
	        ));
		}else{
			 echo json_encode(array(
	            'errorFlgEmail' => true,
	            'msgEmail' => $is_err['email'],
	        ));
		}
	}
	exit();
}
