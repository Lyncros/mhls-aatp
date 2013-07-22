<?php
function clear_old_files(){
    
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/jhadmin/temp_upload/*';  
    //listed in minutes
    $expiretime = 3600;
    // Open a known directory, and proceed to read its contents  
    foreach(glob($dir) as $file)  
    {  
        $FileCreationTime = filectime($file);
        $FileAge = time() - $FileCreationTime;
        if ($FileAge > ($expiretime * 60)) {
            unlink($file);
            echo "here";
        }
    }  
}
clear_old_files();
$_COOKIE['PHPSESSID'] = $_POST['PHPSESSID'];
session_start();
if(isset($_SESSION['USERID'])){
$batch_id = $_POST['batch_id'];
$_SESSION['test_file_id'] = $_SESSION['test_file_id'].$_POST['FILE_ID'];
if (!empty($_FILES))
{
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $timestamp = time();
    $random = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 5)), 0, 5);
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/jhadmin/temp_upload/';
    $storage_file_name = $timestamp . $random . str_replace('/','',$_FILES['Filedata']['name']);
    $targetFile = str_replace('//', '/', $targetPath) . $timestamp . $random . stripslashes ($_FILES['Filedata']['name']);
    move_uploaded_file($tempFile, $targetFile);
    echo $timestamp . $random . $_FILES['Filedata']['name'] . "-----------------------------------/-" . $_FILES['Filedata']['name'];
    $temp_array = array();
    $temp_array['file_name'] = str_replace('/','', $_FILES['Filedata']['name']);
    $temp_array['storage_name'] = $storage_file_name;
    if (is_array($_SESSION['file_links'][$batch_id]))
    {
       $_SESSION['file_links'][$batch_id][$_POST['FILE_ID']] = $temp_array;
    }
    else
    {
        $_SESSION['file_links'][$batch_id] = array();
        $_SESSION['file_links'][$batch_id][$_POST['FILE_ID']] = $temp_array;
    }
}
else
{
    echo 'Invalid file type.';
}
}
?>