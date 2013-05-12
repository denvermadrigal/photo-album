<?php
ini_set('display_errors','0');
if(($_GET['path'] != '') && (file_exists($_GET['path']))){
	$path = $_GET['path'];
	require_once 'inc/image.php';
	$image = new Image();
	if($_GET['do'] == 'check.new'){
		# check for unresized images
		$files = $image->readDir($path,0);
		echo json_encode($files);
	}elseif($_GET['do'] == 'check.thumb'){
		$files = $image->checkThumbsToCreate($path);
		echo json_encode($files);
	}elseif($_GET['do'] == 'create.thumb'){
		$files = $image->checkThumbsToCreate($path);
		$count = count($files);
		$ctr = 0;
		for($i = 0; $i < $count; $i++){
			$curr = $files[$i];
			if((!file_exists($path.'/thumb_'.$curr)) && (!preg_match('[thumb_]',$curr))){
				$info = pathinfo($curr);
				if($image->imageIsValid($info['extension'])){
					$image->imageToFile($image->createImage($path.'/'.$curr),$path.'/thumb_'.$curr);
				}
				if(file_exists($path.'/thumb_'.$curr)){
					$ctr++;
				}
			}
		}
		echo $ctr;
	}elseif($_GET['do'] == 'get.list'){
		$files = $image->readDir($path,1);
		$arr = array();
		$cnt = count($files);
		for($i = 0; $i < $cnt; $i++){
			array_push($arr,array(
				'thumb' => $files[$i],
				'full' => str_replace('thumb_','',$files[$i])
			));
		}
		echo json_encode($arr);
	}
}
?>
