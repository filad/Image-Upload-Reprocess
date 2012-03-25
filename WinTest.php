<?php
/**
 * Testing GD adapter on Win(XP)
 */
require_once('ImgReprocess.php');

$file = 'E:\xampp\htdocs\github\Image-Upload-Reprocess\sourcepic.gif';
$newdir = 'E:\xampp\htdocs\github\Image-Upload-Reprocess\test\\';
$newname = 'newfile';

$repr = new ImgReprocess($file, $newdir, $newname);
$repr->setAdapter('GD')
     ->reprocess();
?>