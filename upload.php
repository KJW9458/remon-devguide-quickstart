<?php
$roomHash = $_REQUEST['roomHash'];
$upload_dir='uploads/'.$roomHash.'/';

if(!is_dir($upload_dir)){
    mkdir($upload_dir, 0777);
}

if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $_FILES['file']['name']);
        
    }

?>