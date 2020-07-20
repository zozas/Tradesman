<?php
	// Initialize
	ini_set('display_errors', 'On');
	//error_reporting(E_ALL | E_STRICT);
	session_start();
	// Initialize
	// CFG, SES, LNG
	require_once('engine.php');
	$CFG = new ini;
	$CFG->open('config.ini.php');
	$CFG->read();
	// Session variables : USER_NAME, USER_PASSWORD, USER_ID
	$SES = new session;
	// Users
	$USR = new user;
	// Database
	$DB = new database($CFG->get('DATABASE','HOST'), $CFG->get('DATABASE','NAME'), $CFG->get('DATABASE','PASSWORD'), $CFG->get('DATABASE','USER'));
	$DB->connect();
	// Language
	$LNG = new ini;
	$LNG->open('english.lng');
	$LNG->read();
	// Check for login POST variables or current session login variables
	$login_username = '';
	if (isset($_POST['login_username'])) {
		$login_username = $_POST['login_username'];
		$SES->set('USER_NAME', $login_username);
	}
	if ($SES->exist('USER_NAME'))
		$login_username = $SES->get('USER_NAME');
	$login_password = '';
	if (isset($_POST['login_password'])) {
		$login_password = $_POST['login_password'];
		$SES->set('USER_PASSWORD', $login_password);
	}
	if ($SES->exist('USER_PASSWORD'))
		$login_password = $SES->get('USER_PASSWORD');
	$USR->set_name($login_username);
	$USR->set_password($login_password);
	// Initialize actions
	$action = '';
	if (isset($_POST['action']))
		$action = $_POST['action'];
	else
		if (isset($_GET['action']))
			$action = $_GET['action'];
	if ($action == 'login') {
		if ($USR->validate() == true) {
			$SES->set('USER_NAME', $login_username);
			$SES->set('USER_PASSWORD', $login_password);
			$SES->set('USER_ID', $USR->get_id());
			$TPL = new template;
			$TPL->open('game.start.tpl');
			$TPL_METATAGS = new template;
			$TPL_METATAGS->open('game.meta.tpl');
			$TPL_METATAGS->set('charset', $LNG->get('CONFIG','charset'));
			$TPL_METATAGS->set('author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL_METATAGS->set('contact', $CFG->get('APPLICATION','CONTACT'));
			$TPL_METATAGS->set('google', $CFG->get('APPLICATION','GOOGLE'));
			$TPL_METATAGS->set('distribution', $CFG->get('APPLICATION','DISTRIBUTION'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('copyright', 'Copyright &copy; '.$CFG->get('APPLICATION','COPYRIGHT').' '.$CFG->get('APPLICATION','AUTHOR').'. Released under '.$CFG->get('APPLICATION','COPYRIGHT').'.');
			$TPL_METATAGS->set('viewport', $CFG->get('APPLICATION','VIEWPORT'));
			$TPL_METATAGS->set('xua', $CFG->get('APPLICATION','XUA'));
			$TPL_METATAGS->set('type', $CFG->get('APPLICATION','TYPE'));
			$TPL_METATAGS->set('product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL_METATAGS->set('keywords', $CFG->get('APPLICATION','KEYWORDS'));
			$TPL_METATAGS->set('description', $CFG->get('APPLICATION','DESCRIPTION'));
			$TPL->set('metatags', $TPL_METATAGS->get());
			$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
			$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
			$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
			$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
			$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
			$TPL->set('logoff', $LNG->get('MENU','logoff'));
			$TPL->set('continue', $LNG->get('GENERAL','continue'));
			$TPL->set('delete', $LNG->get('GENERAL','delete'));
			$TPL->set('id', $SES->get('USER_ID'));
			$GM = new game($SES->get('USER_ID'));
			$TPL->set('city', $GM->get_record('city'));
			$TPL->set('season_days', '<i>'.$GM->get_record('year').'&nbsp;'.$LNG->get('SUMMARY','season_days_past').'</i>');
			$date = new DateTime('1492-10-10');
			$interval = new DateInterval('P'.$GM->get_record('year').'D');
			$date->add($interval);
			$TPL->set('warehouse', $LNG->get('MENU','warehouse'));
			$TPL->set('year', $date->format($LNG->get('CONFIG','date_format')));
			$TPL->set('season', $LNG->get('GENERAL','season'));
			$TPL->set('treasury', $LNG->get('GENERAL','treasury'));
			$warehouse_items = explode(',', $GM->get_record('warehouse'));
			$warehouse_prices = explode(',', $GM->get_record('prices'));
			$warehouse_amount = 0;
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				$warehouse_amount = $warehouse_amount + ($warehouse_items[$i-1]*$warehouse_prices[$i-1]);
			}
			$TPL->set('money_amount', number_format($GM->get_record('money'), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('warehouse_amount', number_format($warehouse_amount, 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('population', $LNG->get('GENERAL','population'));
			$TPL->set('citizens', $GM->get_record('citizens'));
			$buildings = explode(',', $GM->get_record('buildings'));
			$total_buildings = 0;
			$tilemap_size = count($buildings); 
			for ($i = 1; $i < $tilemap_size; $i++) {
				if ($buildings[$i - 1] > 0)
					$total_buildings = $total_buildings + 1;
			}
			$TPL->set('buildings', $LNG->get('SUMMARY','build_total'));
			$TPL->set('total_buildings', $total_buildings);
			$TPL->set('production', $LNG->get('GENERAL','production'));
			$production = '';
			$breakpoint = 0;
			for ($i = 1; $i < $tilemap_size; $i++) {
				if($buildings[$i - 1] > 0) {
					$breakpoint = $breakpoint + 1;
					if($buildings[$i - 1] < 10) {
						$production = $production."<img src='8100".$buildings[$i - 1].".png' title='".$LNG->get('ITEMS','8100'.$buildings[$i - 1])."'>";
					} else {
						$production = $production."<img src='810".$buildings[$i - 1].".png' title='".$LNG->get('ITEMS','810'.$buildings[$i - 1])."'>";
					}
					if ($breakpoint > 9) {
						$breakpoint = 0;
						$production = $production.'<br>';
					}
				}
			}
			$TPL->set('products', $production);
			$TPL->set('score', $LNG->get('MENU','score'));
			$score_result = ($GM->get_record('money') + $warehouse_amount + ($GM->get_record('citizens')*1000) + ($total_buildings*500));
			if ($GM->get_record('year') != 0)
				$score_result =  $score_result / $GM->get_record('year');
			$score_result = $score_result * ($GM->get_record('difficulty')/100);
			$TPL->set('score_result', number_format($score_result, 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			echo $TPL->get();
		} else {
			$SES->erase_session();
			$SES->erase_session();
			$TPL = new template;
			$TPL->open('game.login.tpl');
			$TPL->set('return', $LNG->get('MENU','return'));
			$TPL->set('remind', $LNG->get('MENU','remind'));
			$TPL_METATAGS = new template;
			$TPL_METATAGS->open('game.meta.tpl');
			$TPL_METATAGS->set('charset', $LNG->get('CONFIG','charset'));
			$TPL_METATAGS->set('author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL_METATAGS->set('contact', $CFG->get('APPLICATION','CONTACT'));
			$TPL_METATAGS->set('google', $CFG->get('APPLICATION','GOOGLE'));
			$TPL_METATAGS->set('distribution', $CFG->get('APPLICATION','DISTRIBUTION'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('copyright', 'Copyright &copy; '.$CFG->get('APPLICATION','COPYRIGHT').' '.$CFG->get('APPLICATION','AUTHOR').'. Released under '.$CFG->get('APPLICATION','COPYRIGHT').'.');
			$TPL_METATAGS->set('viewport', $CFG->get('APPLICATION','VIEWPORT'));
			$TPL_METATAGS->set('xua', $CFG->get('APPLICATION','XUA'));
			$TPL_METATAGS->set('type', $CFG->get('APPLICATION','TYPE'));
			$TPL_METATAGS->set('product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL_METATAGS->set('keywords', $CFG->get('APPLICATION','KEYWORDS'));
			$TPL_METATAGS->set('description', $CFG->get('APPLICATION','DESCRIPTION'));
			$TPL->set('metatags', $TPL_METATAGS->get());
			$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
			$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
			$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
			$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
			$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
			$TPL->set('login', $LNG->get('MENU','login'));
			$TPL->set('username', $LNG->get('MENU','username'));
			$TPL->set('userpassword', $LNG->get('MENU','userpassword'));
			echo $TPL->get();
		}
	} else if ($action == 'logout') {
		$SES->erase_session();
		$SES->erase_session();
		$TPL = new template;
		$TPL->open('game.login.tpl');
		$TPL->set('return', $LNG->get('MENU','return'));
		$TPL->set('remind', $LNG->get('MENU','remind'));
		$TPL_METATAGS = new template;
		$TPL_METATAGS->open('game.meta.tpl');
		$TPL_METATAGS->set('charset', $LNG->get('CONFIG','charset'));
		$TPL_METATAGS->set('author', $CFG->get('APPLICATION','AUTHOR'));
		$TPL_METATAGS->set('contact', $CFG->get('APPLICATION','CONTACT'));
		$TPL_METATAGS->set('google', $CFG->get('APPLICATION','GOOGLE'));
		$TPL_METATAGS->set('distribution', $CFG->get('APPLICATION','DISTRIBUTION'));
		$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
		$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
		$TPL_METATAGS->set('copyright', 'Copyright &copy; '.$CFG->get('APPLICATION','COPYRIGHT').' '.$CFG->get('APPLICATION','AUTHOR').'. Released under '.$CFG->get('APPLICATION','COPYRIGHT').'.');
		$TPL_METATAGS->set('viewport', $CFG->get('APPLICATION','VIEWPORT'));
		$TPL_METATAGS->set('xua', $CFG->get('APPLICATION','XUA'));
		$TPL_METATAGS->set('type', $CFG->get('APPLICATION','TYPE'));
		$TPL_METATAGS->set('product', $CFG->get('APPLICATION','PRODUCT'));
		$TPL_METATAGS->set('keywords', $CFG->get('APPLICATION','KEYWORDS'));
		$TPL_METATAGS->set('description', $CFG->get('APPLICATION','DESCRIPTION'));
		$TPL->set('metatags', $TPL_METATAGS->get());		
		$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
		$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
		$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
		$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
		$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
		$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
		$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
		$TPL->set('login', $LNG->get('MENU','login'));
		$TPL->set('username', $LNG->get('MENU','username'));
		$TPL->set('userpassword', $LNG->get('MENU','userpassword'));
		echo $TPL->get();
	} else if ($action == 'remind') {
		$SES->erase_session();
		$SES->erase_session();
		$TPL = new template;
		$TPL->open('game.remind.tpl');
		$TPL_METATAGS = new template;
		$TPL_METATAGS->open('game.meta.tpl');
		$TPL_METATAGS->set('charset', $LNG->get('CONFIG','charset'));
		$TPL_METATAGS->set('author', $CFG->get('APPLICATION','AUTHOR'));
		$TPL_METATAGS->set('contact', $CFG->get('APPLICATION','CONTACT'));
		$TPL_METATAGS->set('google', $CFG->get('APPLICATION','GOOGLE'));
		$TPL_METATAGS->set('distribution', $CFG->get('APPLICATION','DISTRIBUTION'));
		$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
		$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
		$TPL_METATAGS->set('copyright', 'Copyright &copy; '.$CFG->get('APPLICATION','COPYRIGHT').' '.$CFG->get('APPLICATION','AUTHOR').'. Released under '.$CFG->get('APPLICATION','COPYRIGHT').'.');
		$TPL_METATAGS->set('viewport', $CFG->get('APPLICATION','VIEWPORT'));
		$TPL_METATAGS->set('xua', $CFG->get('APPLICATION','XUA'));
		$TPL_METATAGS->set('type', $CFG->get('APPLICATION','TYPE'));
		$TPL_METATAGS->set('product', $CFG->get('APPLICATION','PRODUCT'));
		$TPL_METATAGS->set('keywords', $CFG->get('APPLICATION','KEYWORDS'));
		$TPL_METATAGS->set('description', $CFG->get('APPLICATION','DESCRIPTION'));
		$TPL->set('metatags', $TPL_METATAGS->get());		
		$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
		$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
		$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
		$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
		$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
		$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
		$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
		$TPL->set('return', $LNG->get('MENU','return'));
		$TPL->set('remind', $LNG->get('MENU','remind'));
		$TPL->set('send', $LNG->get('MENU','send'));
		$TPL->set('email', $LNG->get('MENU','email'));
		echo $TPL->get();
	} else if ($action == 'delete') {
		$id = '';
		if (isset($_POST['id']))
			$id = $_POST['id'];
		else
			if (isset($_GET['id']))
				$id = $_GET['id'];
		$GM = new game($SES->get('USER_ID'));
		$GM->delete($id);
		echo "<script>window.location.replace('index.php');</script>";
	} else if ($action == 'reset') {
		$login_email = '';
		if (isset($_POST['login_email']))
			$login_email = $_POST['login_email'];
		else
			if (isset($_GET['login_email']))
				$login_email = $_GET['login_email'];
		$USR->reset_user($login_email, $CFG->get('APPLICATION','TITLE'), $CFG->get('APPLICATION','CONTACT'), $LNG->get('MENU','remind'));
		echo "<script>window.location.replace('login.php');</script>";
	} else {
		if ($USR->validate() == true) {
			$TPL = new template;
			$TPL->open('game.start.tpl');
			$TPL_METATAGS = new template;
			$TPL_METATAGS->open('game.meta.tpl');
			$TPL_METATAGS->set('charset', $LNG->get('CONFIG','charset'));
			$TPL_METATAGS->set('author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL_METATAGS->set('contact', $CFG->get('APPLICATION','CONTACT'));
			$TPL_METATAGS->set('google', $CFG->get('APPLICATION','GOOGLE'));
			$TPL_METATAGS->set('distribution', $CFG->get('APPLICATION','DISTRIBUTION'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('copyright', 'Copyright &copy; '.$CFG->get('APPLICATION','COPYRIGHT').' '.$CFG->get('APPLICATION','AUTHOR').'. Released under '.$CFG->get('APPLICATION','COPYRIGHT').'.');
			$TPL_METATAGS->set('viewport', $CFG->get('APPLICATION','VIEWPORT'));
			$TPL_METATAGS->set('xua', $CFG->get('APPLICATION','XUA'));
			$TPL_METATAGS->set('type', $CFG->get('APPLICATION','TYPE'));
			$TPL_METATAGS->set('product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL_METATAGS->set('keywords', $CFG->get('APPLICATION','KEYWORDS'));
			$TPL_METATAGS->set('description', $CFG->get('APPLICATION','DESCRIPTION'));
			$TPL->set('metatags', $TPL_METATAGS->get());
			$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
			$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
			$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
			$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
			$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
			$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL->set('logoff', $LNG->get('MENU','logoff'));
			$TPL->set('continue', $LNG->get('GENERAL','continue'));
			$TPL->set('delete', $LNG->get('GENERAL','delete'));
			$TPL->set('id', $SES->get('USER_ID'));
			$GM = new game($SES->get('USER_ID'));
			$TPL->set('city', $GM->get_record('city'));
			$TPL->set('season_days', '<i>'.$GM->get_record('year').'&nbsp;'.$LNG->get('SUMMARY','season_days_past').'</i>');
			$date = new DateTime('1492-10-10');
			$interval = new DateInterval('P'.$GM->get_record('year').'D');
			$date->add($interval);
			$TPL->set('warehouse', $LNG->get('MENU','warehouse'));
			$TPL->set('year', $date->format($LNG->get('CONFIG','date_format')));
			$TPL->set('season', $LNG->get('GENERAL','season'));
			$TPL->set('treasury', $LNG->get('GENERAL','treasury'));
			$warehouse_items = explode(',', $GM->get_record('warehouse'));
			$warehouse_prices = explode(',', $GM->get_record('prices'));
			$warehouse_amount = 0;
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				$warehouse_amount = $warehouse_amount + ($warehouse_items[$i-1]*$warehouse_prices[$i-1]);
			}
			$TPL->set('money_amount', number_format($GM->get_record('money'), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('warehouse_amount', number_format($warehouse_amount, 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('population', $LNG->get('GENERAL','population'));
			$TPL->set('citizens', $GM->get_record('citizens'));
			$buildings = explode(',', $GM->get_record('buildings'));
			$total_buildings = 0;
			$tilemap_size = count($buildings); 
			for ($i = 1; $i < $tilemap_size; $i++) {
				if ($buildings[$i - 1] > 0)
					$total_buildings = $total_buildings + 1;
			}
			$TPL->set('buildings', $LNG->get('SUMMARY','build_total'));
			$TPL->set('total_buildings', $total_buildings);
			$TPL->set('production', $LNG->get('GENERAL','production'));
			$production = '';
			$breakpoint = 0;
			for ($i = 1; $i < $tilemap_size; $i++) {
				if($buildings[$i - 1] > 0) {
					$breakpoint = $breakpoint + 1;
					if($buildings[$i - 1] < 10) {
						$production = $production."<img src='8100".$buildings[$i - 1].".png' title='".$LNG->get('ITEMS','8100'.$buildings[$i - 1])."'>";
					} else {
						$production = $production."<img src='810".$buildings[$i - 1].".png' title='".$LNG->get('ITEMS','810'.$buildings[$i - 1])."'>";
					}
					if ($breakpoint > 9) {
						$breakpoint = 0;
						$production = $production.'<br>';
					}
				}
			}
			$TPL->set('products', $production);
			$TPL->set('score', $LNG->get('MENU','score'));
			$score_result = ($GM->get_record('money') + $warehouse_amount + ($GM->get_record('citizens')*1000) + ($total_buildings*500));
			if ($GM->get_record('year') != 0)
				$score_result =  $score_result / $GM->get_record('year');
			$score_result = $score_result * ($GM->get_record('difficulty')/100);
			$TPL->set('score_result', number_format($score_result, 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			echo $TPL->get();
		} else {
			$SES->erase_session();
			$TPL = new template;
			$TPL->open('game.login.tpl');
			$TPL->set('return', $LNG->get('MENU','return'));
			$TPL->set('remind', $LNG->get('MENU','remind'));
			$TPL_METATAGS = new template;
			$TPL_METATAGS->open('game.meta.tpl');
			$TPL_METATAGS->set('charset', $LNG->get('CONFIG','charset'));
			$TPL_METATAGS->set('author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL_METATAGS->set('contact', $CFG->get('APPLICATION','CONTACT'));
			$TPL_METATAGS->set('google', $CFG->get('APPLICATION','GOOGLE'));
			$TPL_METATAGS->set('distribution', $CFG->get('APPLICATION','DISTRIBUTION'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('robots', $CFG->get('APPLICATION','ROBOTS'));
			$TPL_METATAGS->set('copyright', 'Copyright &copy; '.$CFG->get('APPLICATION','COPYRIGHT').' '.$CFG->get('APPLICATION','AUTHOR').'. Released under '.$CFG->get('APPLICATION','COPYRIGHT').'.');
			$TPL_METATAGS->set('viewport', $CFG->get('APPLICATION','VIEWPORT'));
			$TPL_METATAGS->set('xua', $CFG->get('APPLICATION','XUA'));
			$TPL_METATAGS->set('type', $CFG->get('APPLICATION','TYPE'));
			$TPL_METATAGS->set('product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL_METATAGS->set('keywords', $CFG->get('APPLICATION','KEYWORDS'));
			$TPL_METATAGS->set('description', $CFG->get('APPLICATION','DESCRIPTION'));
			$TPL->set('metatags', $TPL_METATAGS->get());
			$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
			$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
			$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
			$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
			$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
			$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
			$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
			$TPL->set('login', $LNG->get('MENU','login'));
			$TPL->set('username', $LNG->get('MENU','username'));
			$TPL->set('userpassword', $LNG->get('MENU','userpassword'));
			echo $TPL->get();
		}
	}
?>

