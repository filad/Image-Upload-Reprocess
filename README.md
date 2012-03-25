Image Upload Reprocess
=====================

General
-------
You can use the ImgReprocess class to reprocess the image the user has uploaded.

You can find in ImgReprocess.php. You can find there two adapter classes, too. So you can use it with the GD library or the Imagick extension as well.

The class automatically adds the proper extension based on PHP's Fileinfo MIME Type checking. Of course relying only on MIME Type is not safe, thats we using reprocessing: if GD or Imagick notices that the file isn't a real image, then the `reprocess()` function will stop and return false. 

If it's a real image and contains unsafe header information, reprocess() will completely rebuild the image, and sanitize it. It supports **.jpg .png .gif** files. Tested on Windows(XP), and Ubuntu.

Usage
-----

Create a new ImgReprocess object:
	
    <?php
    require_once('ImgReprocess.php');
    
    //constructor parameters
    $file = '/var/www/sourcepic.gif';
    $newdir = '/var/www/test/';
    $newname = 'newfile';

    $repr = new ImgReprocess($file, $newdir, $newname);
    
Here you can see we not added any extension, the class will fill the proper extension for you. You just have to add the name of the file `$newname` as the third argument.

Then you call `setAdapter()` and eventually `reprocess()`:

    $repr->setAdapter('GD')
         ->reprocess();

Or we can set the 'Imagick' adapter as well, (but you have to install Image Magick and the php Imagick extension for this):  

        $repr->setAdapter('Imagick')
             ->reprocess();
