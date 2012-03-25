<?php
class ImgReprocess
{
	protected $_file;
	protected $_newdir;
	protected $_adapter;
	protected $_newname;

	/**
	 * Class constructor. 
	 * @param $file The image which was uploaded by the user
	 * @param $newdir Directory path of the fresh reprocessed image ex. '/etc/www/'.  
	 * @param $newname Name of the new file like 'abc'. The class will set the extension automatically.
	 */
	
	public function __construct($file, $newdir = null, $newname = null)
	{
		$this->_file 	= $file;
		$this->_newdir  = $newdir;
		$this->_newname = $newname;
	}
	
	/**
	 * Sets adapter, default is 'GD'. So it will use the GD library by default.
	 */
	public function setAdapter($adapter)
	{
		if ($adapter == 'GD') {
			$this->_adapter = new Adapter_GD($this->_file, $this->_newdir, $this->_newname);
		}
		elseif ($adapter == 'Imagick')
			//no need mimeType here
			$this->_adapter = new Adapter_Imagick($this->_file, $this->_newdir, $this->_newname);
		return $this;
	}
	
	/**
	 * Reprocess the image - this will remove exif data as well
	 * The new image will be safe after reprocess.
	 * @return bool This value will be false if something went wrong
	 */
	public function reprocess()
	{
		$mime = $this->checkMime();	
		if ($mime == null) { 
			return false;
		}
		return $this->_adapter->reprocess($mime);
	}
	
	/**
	 * Check MIME-type. We will use the fileinfo extension for this.
	 * We call the proper reprocess function based on the returned value of this. 
	 * Remember you cannot trust in MIME-type of uploaded images. 
	 */
	public function checkMime()
	{
		$mime = null;
		$file = $this->_file;
		
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		if(is_resource($finfo) == false)
			return false;
		
		$mime = finfo_file($finfo, $file);
		finfo_close($finfo);
		
		return $mime;
	}
}

abstract class Adapter_Abstract 
{
	protected $_file;
	protected $_newdir;
	protected $_newname;

	public function __construct($file, $newdir = null, $newname = null)
	{
		$this->_file 	= $file;
		$this->_newdir  = $newdir;
		$this->_newname = $newname;
	}
	
	abstract public function reprocess($mime = null);	
}

/**
 * GD Adapter
 */
class Adapter_GD extends Adapter_Abstract
{
	public function reprocess($mime = null)
	{
		switch ($mime):
			case 'image/jpeg':
				return $this->_reprocessJpeg();
				break;
				
			case 'image/png':
				return $this->_reprocessPng();
				break;
				
			case 'image/gif':
				return $this->_reprocessGif();
				break;
				
			default:
				//handle error
				return false;		
		endswitch;
	}
	
	protected function _reprocessJpeg() 
	{
		$file  = $this->_file;
		$image = @imagecreatefromjpeg($file);
		if (!$image) {
			return false;
		} else {
			$filepath = $this->_newdir.$this->_newname.'.jpg'; 
			imagejpeg($image, $filepath, 80);
		}
		
		//free up memory
		imagedestroy($image);
		return true;
	}
	
	protected function _reprocessPng() 
	{
		$file  = $this->_file;
		$image = @imagecreatefrompng($file);
		if (!$image) {
			return false;
		} else {
			$filepath = $this->_newdir.$this->_newname.'.png'; 
			imagepng($image, $filepath, 8);
		}
		
		//free up memory
		imagedestroy($image);
		return true;
	}
	
	protected function _reprocessGif() 
	{
		$file  = $this->_file;
		$image = @imagecreatefromgif($file);
		if (!$image) {
			return false;
		} else {
			$filepath = $this->_newdir.$this->_newname.'.gif'; 
			imagegif($image, $filepath);
		}
		
		//free up memory
		imagedestroy($image);
		return true;	
	}	
}

/**
 * Imagick Adapter
 */
class Adapter_Imagick extends Adapter_Abstract
{
	/**
	 * @param string Mime type - optional here
	 * @return bool false on fail
	 * @throws Exception
	 */	
	public function reprocess($mime = null)
	{
		try 
		{
	        $img = new Imagick($file);
	        $img->stripImage();
			
			$pathname = $this->_newdir.$this->_newname;
	        $img->writeImage($pathname);
	        $img->clear();
	        $img->destroy();
			return true;
		} catch (Exception $e){
			error_log('Imagick exception caught: '.$e->getMessage());
			return false;
		}
	}
}
?>