<?php
ini_set('display_errors','1');
# author: denver madrigal
# script: photo album v1.0
class Helpers{
	private $_host;
	private $_user;
	private $_pass;
	private $_name;
	
	function __construct(){ /**/ }
	
	function init(){
		# check if already installed
		require_once 'inc/configuration.php';
		if(!class_exists('Config')){
			header('location: ./install.php');
			exit;
		}
	}

	function getAlbums(){
		return glob('albums/*',GLOB_ONLYDIR);
	}

	function pingDb($host,$name,$user,$pass){
		$db = new mysqli($host,$user,$pass,$name);
		$errno = mysqli_connect_errno();
		$db->close();
		return ($errno)?false:true;
	}
	
	function createTables($sql,$post){
		$db = new mysqli($post['db_host'],$post['db_user'],$post['db_pass'],$post['db_name']);
		$return = $db->query(addslashes($sql));
		$db->close();
		return $return;
	}

	function query2($sql,$post){
		$db = new mysqli($post['db_host'],$post['db_user'],$post['db_pass'],$post['db_name']);
		$db->query($sql);
		$db->close();
	}
}
?>
