<?php
class Image{
	function __construct(){ }
	
	function imageIsValid($ext){
		switch(strtolower($ext)){
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'png':
				return true;
				break;
			default:
				return false;
		}
	}

	function checkThumbsToCreate($path){
		if(file_exists($path) && $path != ''){
			$handle = opendir($path) or die('Error loading album.');
			if($handle){
				$arr = array();
				while(false !== ($file = readdir($handle))){
					if(($file != '.') && ($file != '..')){
						$info = pathinfo($file);
						if($this->imageIsValid($info['extension'])){
							# check for thumbs to create
							if(!preg_match('[thumb_]',$file)){
								if(!file_exists($path.'/thumb_'.$file)){
									array_push($arr,$file);
								}
							}
						}
					}
				}
				return $arr;
			}
		}
	}

	function readDir($path,$checkForThumb = 0){
		if(file_exists($path) && $path != ''){
			$handle = opendir($path) or die('Error loading album.');
			if($handle){
				$arr = array();
				while(false !== ($file = readdir($handle))){
					if(($file != '.') && ($file != '..')){
						$info = pathinfo($file);
						if($this->imageIsValid($info['extension'])){
							# check for thumbs
							if((int)$checkForThumb){
								if(preg_match('[thumb_]',$file)){
									array_push($arr,$file);
								}
							}else{
								if(!preg_match('[thumb_]',$file)){
									if(!file_exists($path.'/thumb_'.$file)){
										array_push($arr,$file);
									}
								}
							}
						}
					}
				}
				return $arr;
			}
		}
	}

	# check if there are thumbnails to create
	# meaning original files must not match with "thumb_".FILENAME.EXT
	function checkThumbs(){
		
	}
	
	function createImage($fileName){
		$maxSize = 570;
		
		$info = getimagesize($fileName);
		$type = isset($info['type'])?$info['type']:$info[2];
		if(!(imagetypes() & $type))
			return false;
			
		$width = (isset($info['width']))?$info['width']:$info[0];
		$height= (isset($info['height']))?$info['height']:$info[1];
		# Aspect ratio
		$wRatio = $maxSize / $width;
		$hRatio = $maxSize / $height;
		# detect file type
		$source = imagecreatefromstring(file_get_contents($fileName));
		
		if(($width <= $maxSize) && ($height <= $maxSize))
			return $source;
		else if(($wRatio * $height) < $maxSize){
			$tHeight = ceil($wRatio * $height);
			$tWidth = $maxSize;
		}else{
			$tWidth = ceil($hRatio * $width);
			$tHeight = $maxSize;
		}
		
		$thumb = imagecreatetruecolor($tWidth,$tHeight);
		if($source === false)
			return false;
			
		imagecopyresampled($thumb,$source,0,0,0,0,$tWidth,$tHeight,$width,$height);
		imagedestroy($source);
		return $thumb;
	}
	
	function imageToFile($im,$fileName,$quality = 100){
		if(!$im || file_exists($fileName))
			return false;
			
		$ext = strtolower(substr($fileName,strrpos($fileName,'.')));
		switch($ext){
			case '.gif':
				imagegif($im,$fileName);
				break;
			case '.jpg':
			case '.jpeg':
				imagejpeg($im,$fileName,$quality);
				break;
			case '.png':
				imagepng($im,$fileName);
				break;
			default:
				return false;
		}
		return true;
	}
}
?>
