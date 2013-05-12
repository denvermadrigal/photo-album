<?php
ini_set('display_errors',1);
require_once 'inc/helpers.php';
Helpers::init();
require_once 'inc/configuration.php';
$conf = new Config();
$albums = Helpers::getAlbums();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class='no-js lt-ie9 lt-ie8 lt-ie7'> <![endif]-->
<!--[if IE 7]>         <html class='no-js lt-ie9 lt-ie8'> <![endif]-->
<!--[if IE 8]>         <html class='no-js lt-ie9'> <![endif]-->
<!--[if gt IE 8]><!--> <html class='no-js'> <!--<![endif]-->
	<head>
		<meta charset='utf-8' />
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
		<title><?php echo $conf->site_name; ?> | Photo Album</title>
		<meta name='description' content='<?php echo $conf->meta_description; ?>' />
		<meta name='keywords' content='<?php echo $conf->meta_keyword; ?>' />
		<meta name='viewport' content='width=device-width' />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css' />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' />
		<link rel='stylesheet' href='css/font-awesome.min.css' />
		<link rel='stylesheet' href='css/bootstrap.min.css' />
		<link rel='stylesheet' href='css/bootstrap-responsive.min.css' />
		<link rel='stylesheet' href='css/bootstrap-lightbox.min.css' />
		<link rel='stylesheet' href='css/main.css' />
		<script src='js/vendor/modernizr-2.6.2-respond-1.1.0.min.js'></script>
	</head>
	<body class='index'>
		<!--[if lt IE 7]>
			<p class='chromeframe'>You are using an <strong>outdated</strong> browser. Please <a href='http://browsehappy.com/'>upgrade your browser</a> or <a href='http://www.google.com/chromeframe/?redirect=true'>activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->

		<div id='overlay'>
			<div id='overlay_msg'></div>
		</div>

		<div class='visible-desktop' style='height:60px;overflow:hidden;'>&nbsp;</div>

		<!-- content: begin -->
		<div class='navbar navbar-inverse navbar-fixed-top'>
			<div class='navbar-inner'>
				<div class='container'>
					<div class='navbar-inner' style='padding:0 10px !important;'>
						<a href='#' class='brand'><?php echo $conf->site_name; ?></a>
						<ul class='nav'>
							<li class='dropdown'>
								<a href='#' class='dropwdown-toggle' data-toggle='dropdown'><i class='icon-picture'></i> My Albums <i class='icon-caret-down'></i></a>
								<?php if(count($albums)): ?>
									<ul class='dropdown-menu'>
										<?php foreach($albums as $album): ?>
											<li><a rel='<?php echo $album; ?>' onclick='return false;' class='nav-call' href='#<?php echo $album ?>'><?php echo basename($album); ?></a></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class='container' id='main_body'></div>

		<!-- content: end -->
		<div id='footer' style='margin-top:20px;'>
			<?php echo $conf->footer_text; ?>
			<div>Hand made by <a href='mailto:denvermadrigal@gmail.com'>denvermadrigal@gmail.com</a></div>
		</div>

		<script src='js/vendor/jquery-1.9.1.min.js'></script>
		<script src='js/vendor/bootstrap.min.js'></script>
		<script src='js/vendor/bootstrap-lightbox.min.js'></script>
		<script src='js/main.js'></script>
		<script>
			var _gaq=[['_setAccount','<?php echo $conf->ga_tracking_id; ?>'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
	</body>
</html>
