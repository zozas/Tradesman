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
	if ($action == 'configure_new') {
		$SES->erase_session();
		$SES->erase_session();
		$TPL = new template;
		$TPL->open('game.create.tpl');
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
		$TPL->set('configure_new', $LNG->get('MENU','configure_new'));
		$TPL->set('return', $LNG->get('MENU','return'));
		$TPL->set('create', $LNG->get('MENU','create'));		
		$TPL->set('username', $LNG->get('MENU','username'));
		$TPL->set('userpassword', $LNG->get('MENU','userpassword'));
		$TPL->set('useremail', $LNG->get('MENU','useremail'));
		$TPL->set('city', $LNG->get('MENU','city'));
		$TPL->set('reign_range', $LNG->get('MENU','reign_range'));
		$TPL->set('reign_range_info', $LNG->get('MENU','reign_range_info'));
		$TPL->set('days', $LNG->get('MENU','days'));
		$TPL->set('money', $LNG->get('MENU','money'));
		$TPL->set('money_range', $LNG->get('MENU','money_range'));
		$TPL->set('citizens', $LNG->get('MENU','citizens'));
		$TPL->set('citizens_range', $LNG->get('MENU','citizens_range'));		
		$TPL->set('landmass', $LNG->get('MENU','landmass'));
		$TPL->set('landmass_range', $LNG->get('MENU','landmass_range'));		
		$TPL->set('difficulty', $LNG->get('MENU','difficulty'));
		$TPL->set('difficulty_range', $LNG->get('MENU','difficulty_range'));
		$TPL->set('mountain', $LNG->get('MENU','mountain'));
		$TPL->set('mountain_range', $LNG->get('MENU','mountain_range'));
		$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
		$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
		$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
		$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
		$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
		$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
		$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
		echo $TPL->get();
	} else if ($action == 'highscores') {
		$SES->erase_session();
		$SES->erase_session();
		$TPL = new template;
		$TPL->open('game.highscores.tpl');
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
		$TPL->set('highscores', $LNG->get('MENU','highscores'));
		$TPL->set('return', $LNG->get('MENU','return'));
		$GM = new game(0);
		$highscores_entries = $GM->get_games_played();
		if ($highscores_entries > $CFG->get('GAME','HIGHSCORES'))
			$highscores_entries = $CFG->get('GAME','HIGHSCORES');
		$highscores = $GM->highscores($highscores_entries);
		$highscores_template = '';
		for ($i = 0; $i < $highscores_entries; $i++) {
			$TPL_SCORES = new template;
			$TPL_SCORES->open('game.scores.tpl');
			$TPL_SCORES->set('application_title', $CFG->get('APPLICATION','TITLE'));
			$buildings = explode(',', $highscores[$i]['buildings']);
			$total_buildings = 0;
			$tilemap_size = count($buildings); 
			for ($j = 1; $j < $tilemap_size; $j++) {
				if ($buildings[$j - 1] > 0)
					$total_buildings = $total_buildings + 1;
			}
			$TPL_SCORES->set('user', $highscores[$i]['name']);
			$TPL_SCORES->set('city', $highscores[$i]['city']);
			$TPL_SCORES->set('day', number_format($highscores[$i]['year'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL_SCORES->set('duration', number_format($highscores[$i]['end'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL_SCORES->set('money', number_format($highscores[$i]['money'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL_SCORES->set('citizens', number_format($highscores[$i]['citizens'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL_SCORES->set('buildings', $total_buildings);
			$TPL_SCORES->set('difficulty', $highscores[$i]['difficulty']);
			$TPL_SCORES->set('score', number_format($highscores[$i]['score'], 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$highscores_template = $highscores_template.$TPL_SCORES->get();
		}
		$TPL->set('highscore_entries', $highscores_template);
		$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
		$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
		$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
		$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
		$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
		$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
		$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
		echo $TPL->get();
	} else if ($action == 'create') {
		$login_username = '';
		$login_password = '';
		$login_email = '';
		$login_city = '';
		$login_end = '';
		$login_money = '';
		$login_citizens = '';
		$login_landmass = '';
		$login_difficulty = '';
		$login_mountain = '';
		if (isset($_POST['login_username']))
			$login_username = $_POST['login_username'];
		else
			if (isset($_GET['login_username']))
				$login_username = $_GET['login_username'];
		if (isset($_POST['login_password']))
			$login_password = $_POST['login_password'];
		else
			if (isset($_GET['login_password']))
				$login_password = $_GET['login_password'];
		if (isset($_POST['login_email']))
			$login_email = $_POST['login_email'];
		else
			if (isset($_GET['login_email']))
				$login_email = $_GET['login_email'];
		if (isset($_POST['login_city']))
			$login_city = $_POST['login_city'];
		else
			if (isset($_GET['login_city']))
				$login_city = $_GET['login_city'];
		if (isset($_POST['login_end']))
			$login_end = $_POST['login_end'];
		else
			if (isset($_GET['login_end']))
				$login_end = $_GET['login_end'];
		if (isset($_POST['login_money']))
			$login_money = $_POST['login_money'];
		else
			if (isset($_GET['login_money']))
				$login_money = $_GET['login_money'];
		if (isset($_POST['login_citizens']))
			$login_citizens = $_POST['login_citizens'];
		else
			if (isset($_GET['login_citizens']))
				$login_citizens = $_GET['login_citizens'];
		if (isset($_POST['login_landmass']))
			$login_landmass = $_POST['login_landmass'];
		else
			if (isset($_GET['login_landmass']))
				$login_landmass = $_GET['login_landmass'];
		if (isset($_POST['login_difficulty']))
			$login_difficulty = $_POST['login_difficulty'];
		else
			if (isset($_GET['login_difficulty']))
				$login_difficulty = $_GET['login_difficulty'];
		if (isset($_POST['login_mountain']))
			$login_mountain = $_POST['login_mountain'];
		else
			if (isset($_GET['login_mountain']))
				$login_mountain = $_GET['login_mountain'];
		$GM = new game(0);
		$new_user = $GM->create($login_username, $login_password, $login_email, $login_city, $login_end, $login_money, $login_citizens, $login_landmass, $login_difficulty, $login_mountain);
		if ($new_user > 0) {
			$SES->set('USER_NAME', $login_username);
			$SES->set('USER_PASSWORD', $login_password);
			echo "<script>window.location.replace('login.php?action=login');</script>";
		} else {
			echo "<script>window.location.replace('index.php');</script>";
		}
	} else {
		$SES->erase_session();
		$SES->erase_session();
		$TPL = new template;
		$TPL->open('game.index.tpl');
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
		$TPL->set('new_game', $LNG->get('MENU','new_game'));
		$TPL->set('past_game', $LNG->get('MENU','past_game'));
		$TPL->set('other', $LNG->get('MENU','other'));
		$TPL->set('configure_new', $LNG->get('MENU','configure_new'));
		$TPL->set('configure_new_info', $LNG->get('MENU','configure_new_info'));
		$TPL->set('load', $LNG->get('MENU','load'));
		$TPL->set('load_info', $LNG->get('MENU','load_info'));
		$TPL->set('highscores', $LNG->get('MENU','highscores'));
		$TPL->set('highscores_info', $LNG->get('MENU','highscores_info'));
		$TPL->set('application_version', $CFG->get('APPLICATION','VERSION'));
		$TPL->set('application_author', $CFG->get('APPLICATION','AUTHOR'));
		$TPL->set('application_deployment', $CFG->get('APPLICATION','DEPLOYMENT'));
		$TPL->set('application_copyright', $CFG->get('APPLICATION','COPYRIGHT'));
		$TPL->set('application_product', $CFG->get('APPLICATION','PRODUCT'));
		$TPL->set('application_subtitle', preg_replace('/\s+/', '&nbsp;&middot;&nbsp;', $CFG->get('APPLICATION','SUBTITLE')));
		echo $TPL->get();		
	}
?>

