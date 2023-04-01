<?php
session_start();
$error = '';
$success_massage = 'file uploaded successfully';
$allowed_files = ['image/png', 'image/jpeg'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // consts
    define('MIN_IMG_SIZE', 10000);
    define('MAX_IMG_SIZE', 500000);
    define('UPLOAD_FILE', "../imgs/");


    // data 
    $img = $_FILES['img'];
    $img_name = $img['name'];
    $img_tmp_name = $img['tmp_name'];
    $img_error = $img['error'];

    $img_size = filesize($img_tmp_name);
    $img_info = finfo_open(FILEINFO_MIME_TYPE);
    $img_mime_type = finfo_file($img_info, $img_tmp_name);

    $img_extention = pathinfo($img_name, PATHINFO_EXTENSION);

    // validations 
    if ($img_name === '') {
        $error = 'img is required';
    } else if ($img_size > MAX_IMG_SIZE) {
        $error = 'img is too larg';
    } else if ($img_size < MIN_IMG_SIZE) {
        $error = 'img is too small';
    } elseif ($img_error !== 0) {
        $error = 'there is an error uploading the img';
    } elseif (!in_array($img_mime_type, $allowed_files)) {
        $error = 'invalid img type';
    } else {
        $new_img_name = uniqid('', true) . "." . $img_extention;
        $img_new_path = UPLOAD_FILE . $new_img_name;
        if (!move_uploaded_file($img_tmp_name, $img_new_path)) {
            $error = 'moving img error';
        }
    }

    if (!empty($error)) {

        $_SESSION['error'] = $error;
    } else {
        $_SESSION['success'] = $success_massage;
    }
    header("location:../index.php");
    die;

    // wrong method
} else {
    $_SESSION['error'] = 'wrong method';
    header("location:../index.php");
    die;
}
