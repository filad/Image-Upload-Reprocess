<?php
/**
 * Testing Imagick adapter on linux (ubuntu)
 */
require_once('ImgReprocess.php');

$file = '/var/www/sourcepic.gif';
$newdir = '/var/www/test/';
$newname = 'newfile';

$repr = new ImgReprocess($file, $newdir, $newname);
$repr->setAdapter('Imagick')
     ->reprocess();
?>