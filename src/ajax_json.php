<?php
// json形式で返す
if(!empty($_POST)){
    // joson_encodeでjson形式に変える
    echo json_encode(array(
        'date' => $_POST['date'],
        'country' => $_POST['country'],
        'people' => $_POST['people'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
    ));
}

?>