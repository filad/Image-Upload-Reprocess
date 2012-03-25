Image Upload Reprocess
=====================

General
-------
You can use the ImgReprocess class to reprocess the image the user has uploaded. So the image will be safe, and it will not contain any malicous code. 

You can find it in *ImgReprocess.php*. There you can find two adapter classes, too. So you can use it with the GD library or the Imagick extension as well.

The class automatically adds the proper extension based on PHP's Fileinfo MIME Type checking. Of course relying only on MIME Type is not safe, thats why we use reprocessing: if GD or Imagick notices that the file isn't a real image, then the `reprocess()` function will stop and return false.

If it's a real image and contains unsafe header information, reprocess() will completely rebuild the image, then it will be safe to store. It supports **.jpg .png .gif** files. Tested on Windows(XP), and Ubuntu.

Usage
-----

Firstly, create a new ImgReprocess object:
	

	require_once('ImgReprocess.php');

	//constructor parameters
	$file    = '/var/www/sourcepic.gif';
	$newdir  = '/var/www/destination/';
	$newname = 'newfile';

	$repr = new ImgReprocess($file, $newdir, $newname);

`$file` is your source filename. (It could be your `$_FILES['userfile']['tmp_name']`, too.)  
`$newdir` is the the directory of the reprocessed image.  
And the third argument, you just have to add the name of the new image `$newname`.  
*Important*: Here you can see we not added any extension, the class will fill the proper extension for you. 

Then you call `setAdapter()` and eventually `reprocess()`:

	$repr->setAdapter('GD')
		->reprocess();

Or we can set the 'Imagick' adapter as well, (but you have to install Image Magick and the php Imagick extension for this):

	$repr->setAdapter('Imagick')
	     ->reprocess();
		
`reprocess()` will return FALSE on failure.
