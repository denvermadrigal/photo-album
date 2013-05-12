<?php
ini_set('display_errors','0');
require_once 'inc/configuration.php';
if(class_exists('Config')){
	header('location: ./');
	exit;
}
$mysqli_err = 0;
if($_POST['xsubmit'] == 'do.install'){
	require_once 'inc/helpers.php';
	if(Helpers::pingDb($_POST['db_host'],$_POST['db_name'],$_POST['db_user'],$_POST['db_pass'])){
		# build mysql script
		$user = '
			CREATE TABLE IF NOT EXISTS `tbl_users`(
				`id`				int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name`			varchar(50) NOT NULL,
				`email`			varchar(100) NOT NULL,
				`password`	varchar(100) NOT NULL,
				`created`		timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				UNIQUE KEY `email` (`email`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		';
		Helpers::query2('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";',$_POST);
		Helpers::query2('SET time_zone = "+00:00";',$_POST);
		if(Helpers::createTables($user,$_POST) === TRUE){
      # insert user
      Helpers::query2('
        insert into `tbl_users`(
          `name`, `email`, `password`
        )values(
          \''.addslashes($_POST['name']).'\',
          \''.addslashes($_POST['email']).'\',
          \''.md5($_POST['password']).'\'
        )
      ',$_POST);
			# create the config file
			$handle = fopen('inc/configuration.php','w');
			$conf = "<?php\n";
			$conf.= "class Config{\n";
			$conf.= "\t".'public $site_name = "'.$_POST['site_name'].'";'."\n";
			$conf.= "\t".'public $meta_keyword = "'.$_POST['meta_keyword'].'";'."\n";
			$conf.= "\t".'public $meta_description = "'.$_POST['meta_description'].'";'."\n";
			$conf.= "\t".'public $footer_text = "'.$_POST['footer_text'].'";'."\n";
			$conf.= "\t".'public $ga_tracking_id = "'.$_POST['ga_tracking_id'].'";'."\n";
			$conf.= "\t".'public $facebook_page_id = "'.$_POST['facebook_page_id'].'";'."\n";
			$conf.= "\t".'public $twitter_id = "'.$_POST['twitter_id'].'";'."\n";
			$conf.= "\t".'public $host = "'.$_POST['db_host'].'";'."\n";
			$conf.= "\t".'public $username = "'.$_POST['db_user'].'";'."\n";
			$conf.= "\t".'public $password = "'.$_POST['db_pass'].'";'."\n";
			$conf.= "\t".'public $db_name = "'.$_POST['db_name'].'";'."\n";
			$conf.= "}\n";
			$conf.= "?>";
			$result = fwrite($handle,$conf);
			fclose($handle);

			if($result){
				header('location: ./');
				 exit;
			}else{
				# drop table
				Helpers::query2('DROP TABLE tbl_users',$_POST);
				$mysqli_err = 3;
			}
		}else{
			$mysqli_err = 2;
		}
	}else{
		$mysqli_err = 1;	
	}
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class='no-js lt-ie9 lt-ie8 lt-ie7'> <![endif]-->
<!--[if IE 7]>         <html class='no-js lt-ie9 lt-ie8'> <![endif]-->
<!--[if IE 8]>         <html class='no-js lt-ie9'> <![endif]-->
<!--[if gt IE 8]><!--> <html class='no-js'> <!--<![endif]-->
	<head>
		<meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
		<title>Installation</title>
		<meta name='description' content='' />
		<meta name='viewport' content='width=device-width' />
		<link rel='stylesheet' href='css/bootstrap.min.css' />
		<link rel='stylesheet' href='css/bootstrap-responsive.min.css' />
		<link rel='stylesheet' href='css/main.css' />
		<script src='js/vendor/modernizr-2.6.2-respond-1.1.0.min.js'></script>
	</head>
	<body class='install'>
		<!--[if lt IE 7]>
			<p class='chromeframe'>You are using an <strong>outdated</strong> browser. Please <a href='http://browsehappy.com/'>upgrade your browser</a> or <a href='http://www.google.com/chromeframe/?redirect=true'>activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->

		<!-- content: begin -->
		<div class='container'>
			<form class='form-signin' id='frm_install' method='post' action='install.php' autocomplete='off'>
				<div class='page-header'>
					<h1 class='form-signin-heading'>{album.install}</h1>
				</div>
				<div id='notices'></div>
				
				<h3>Account Information</h3>
				<input value='<?php echo $_POST['name']; ?>' type='text' name='name' class='input-block-level' placeholder='*Your Name' />
				<input value='<?php echo $_POST['email']; ?>' type='text' name='email' class='input-block-level' placeholder='*Your Email' />
				<input value='<?php echo $_POST['password']; ?>' type='password' name='password' class='input-block-level' placeholder='*Password' />
				<input value='<?php echo $_POST['verify_password']; ?>' type='password' name='verify_password' class='input-block-level' placeholder='*Verify Password' />
				<h3>Site Configuration</h3>
				<input value='<?php echo $_POST['site_name']; ?>' type='text' name='site_name' class='input-block-level' placeholder='*Site Name' />
				<input value='<?php echo $_POST['meta_keyword']; ?>' type='text' name='meta_keyword' class='input-block-level' placeholder='Meta Keyword' />
				<textarea name='meta_description' class='input-block-level' placeholder='Meta Description'><?php echo $_POST['meta_description']; ?></textarea>
				<textarea name='footer_text' class='input-block-level' placeholder='Footer Text'><?php echo $_POST['footer_text']; ?></textarea>
				<input value='<?php echo $_POST['ga_tracking_id']; ?>' type='text' name='ga_tracking_id' class='input-block-level' placeholder='Google Analytics Tracking ID' />
				<input value='<?php echo $_POST['facebook_page_id']; ?>' type='text' name='facebook_page_id' class='input-block-level' placeholder='Facebook Page ID' />
				<input value='<?php echo $_POST['twitter_id']; ?>' type='text' name='twitter_id' class='input-block-level' placeholder='Twitter Account ID' />
				<h3>Database Configuration</h3>
				<input value='<?php echo $_POST['db_host']; ?>' type='text' name='db_host' class='input-block-level' placeholder='*Host / Server (usually "localhost")' />
				<input value='<?php echo $_POST['db_name']; ?>' type='text' name='db_name' class='input-block-level' placeholder='*Database name' />
				<input value='<?php echo $_POST['db_user']; ?>' type='text' name='db_user' class='input-block-level' placeholder='*Username' />
				<input value='<?php echo $_POST['db_pass']; ?>' type='password' name='db_pass' class='input-block-level' placeholder='Password' />
				<a onclick='chkInstallData();' class='btn btn-primary'>Click here to install</a>
				<input type='hidden' name='xsubmit' value='do.install' />
			</form>
		</div>

		<div id='footer'>Hand made by <a href='mailto:denvermadrigal@gmail.com' target='_blank'>denvermadrigal@gmail.com</a></div>
		<!-- content: end -->

		<!-- modal -->
		<div class='modal hide fade' id='modal-err-db' style='padding:20px;max-width:528px;'>
			<div class='page-header' style='margin:0 0 20px 0;'>
				<a class='close' data-dismiss='modal' style='line-height:38.5px;'>x</a>
				<h1>{database.error}</h1>
			</div>
			<?php if($mysqli_err == 1): ?>
				Database connection error! Please check your database credentials.
			<?php elseif($mysqli_err == 2): ?>
				Failed builing database tables.
			<?php elseif($mysqli_err == 3): ?>
				Failed to update the configuration file.
			<?php endif; ?>
		</div>

		<script src='js/vendor/jquery-1.9.1.min.js'></script>
		<script src='js/vendor/bootstrap.min.js'></script>
		<script src='js/main.js'></script>
		<script>
			(function($){
				$(document).ready(function(){
					<?php if($mysqli_err): ?>
						$('#modal-err-db').modal('show');
					<?php endif; ?>
				})
			})(jQuery);

			function chkInstallData(){
				$('#notices').html('');
				var msg = new Array();
				if($('input[name="name"]').val() == ''){
					msg.push('Your name is required.');
				}
				var email = $('input[name="email"]').val();
				if(email.match(/^\w+(['\.\-\+]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/) == null){
					msg.push('Your email is invalid.');
				}
				if($('input[name="password"]').val() != ''){
					if($('input[name="password"]').val() != $('input[name="verify_password"]').val()){
						msg.push('Password verification failed.');
					}
				}else{
					msg.push('Please enter your password.');
				}
				if($('input[name="site_name"]').val() == ''){
					msg.push('Site name is required.');
				}
				if($('input[name="db_host"]').val() == ''){
					msg.push('Database host / server is requied.');
				}
				if($('input[name="db_name"]').val() == ''){
					msg.push('Database name is required.');
				}
				if($('input[name="db_user"]').val() == ''){
					msg.push('Database username is required.');
				}
				
				if(msg.length){
					for(var i = 0; i < msg.length; i++){
						var div = document.createElement('div');
						div.setAttribute('class','alert alert-error');
						div.setAttribute('style','margin-bottom:5px;');
						var btn = document.createElement('button');
						btn.setAttribute('type','button');
						btn.setAttribute('class','close');
						btn.setAttribute('data-dismiss','alert');
						btn.innerHTML = '&times;';
						var span = document.createElement('span');
						span.innerHTML = msg[i];
						div.appendChild(btn);
						div.appendChild(span);
						$('#notices').append(div);
					}
				}else{
					$('.alert-error').fadeOut();
					$('.alert-error-msg').html('');
					$('#frm_install').submit();
				}
			}
			var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
	</body>
</html>
