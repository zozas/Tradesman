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
	// Session variables : USER_NAME, USER_PASSWORD
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
	// Check for POST variables
	$login_username = '';
	if ($SES->exist('USER_NAME')) {
		$login_username = $SES->get('USER_NAME');
	} else {
		if (isset($_POST['user_name']))
			$login_username = $_POST['user_name'];
		else
			if (isset($_GET['user_name']))
				$login_username = $_GET['user_name'];
	}
	$login_password = '';
	if ($SES->exist('USER_PASSWORD')) {
		$login_password = $SES->get('USER_PASSWORD');
	} else {
		if (isset($_POST['user_password']))
			$login_password = $_POST['user_password'];
		else
			if (isset($_GET['user_password']))
				$login_password = $_GET['user_password'];
	}
	$action = '';
	if (isset($_POST['action']))
		$action = $_POST['action'];
	else
		if (isset($_GET['action']))
			$action = $_GET['action'];
	$action_parameters = '';
	if (isset($_POST['action_parameters']))
		$action_parameters = $_POST['action_parameters'];
	else
		if (isset($_GET['action_parameters']))
			$action_parameters = $_GET['action_parameters'];
	// Authorize user
	$USR->set_name($login_username);
	$USR->set_password($login_password);
	if ($USR->validate() == true) {
		$GM = new game($SES->get('USER_ID'));
		if ($action == 'buy') {
			$current_money = $GM->get_record('money');
			$warehouse_prices = explode(",", $GM->get_record('prices'));
			$warehouse_items = explode(",", $GM->get_record('warehouse'));
			if (($current_money - $warehouse_prices[$action_parameters]) > 0) {
				$current_tax = $GM->get_record('tax');
				$GM->set_record('money', floor($current_money + $warehouse_prices[$action_parameters]*((100 - $current_tax)/100)));
				$warehouse_items[$action_parameters] = $warehouse_items[$action_parameters] + 1;
				$warehouse_items_update = implode(',', $warehouse_items);
				$GM->set_record('warehouse', $warehouse_items_update);
			}
			$prices_exchange = explode(",", $GM->get_record('prices_exchange'));
			$prices_exchange[$action_parameters] = $prices_exchange[$action_parameters] + 1;
			$prices_exchange = implode(',', $prices_exchange);
			$GM->set_record('prices_exchange', $prices_exchange);
		} else if ($action == 'sell') {
			$current_money = $GM->get_record('money');
			$warehouse_prices = explode(",", $GM->get_record('prices'));
			$warehouse_items = explode(",", $GM->get_record('warehouse'));
			if ($warehouse_items[$action_parameters] > 0) {
				$current_tax = $GM->get_record('tax');
				$GM->set_record('money', floor($current_money + $warehouse_prices[$action_parameters]*((100 - $current_tax)/100)));
				$warehouse_items[$action_parameters] = $warehouse_items[$action_parameters] - 1;
				$warehouse_items_update = implode(',', $warehouse_items);
				$GM->set_record('warehouse', $warehouse_items_update);
			}
			$prices_exchange = explode(",", $GM->get_record('prices_exchange'));
			$prices_exchange[$action_parameters] = $prices_exchange[$action_parameters] - 1;
			$prices_exchange = implode(',', $prices_exchange);
			$GM->set_record('prices_exchange', $prices_exchange);
		} else if ($action == 'build') {
			$action_parameters_list = explode("-", $action_parameters);
			$current_tile = $action_parameters_list[0];
			$building_type = $action_parameters_list[1];
			$current_money = $GM->get_record('money');
			$buildings_cost = explode(",", $GM->get_record('buildings_cost'));
			if ($current_money - $buildings_cost[$building_type - 1] > 0) {				
				$GM->set_record('money', ($current_money - $buildings_cost[$building_type - 1]));
				$buildings = explode(",", $GM->get_record('buildings'));
				$buildings[$current_tile] = $building_type;
				$buildings_update = implode(',', $buildings);
				$GM->set_record('buildings', $buildings_update);
			}
			$GM->set_record('builds', $GM->get_record('builds')+1);
		} else if ($action == 'demolish') {
			$current_tile = $action_parameters;
			$current_money = $GM->get_record('money');
			$demolish_cost = $GM->get_record('demolish_cost');
			$buildings_cost = explode(",", $GM->get_record('buildings_cost'));
			$buildings = explode(",", $GM->get_record('buildings'));
			if ($current_money - floor(($demolish_cost * $buildings_cost[$buildings[$current_tile]])/100) > 0) {				
				$GM->set_record('money', $current_money - floor(($demolish_cost * $buildings_cost[$buildings[$current_tile]])/100));
				$buildings = explode(",", $GM->get_record('buildings'));
				$buildings[$current_tile] = 0;
				$buildings_update = implode(',', $buildings);
				$GM->set_record('buildings', $buildings_update);
			}
			$GM->set_record('demolitions', $GM->get_record('demolitions')+1);
		} else if ($action == 'research') {
			$current_money = $GM->get_record('money');
			$buildings_cost = explode(",", $GM->get_record('buildings_cost'));
			$current_expertise = $action_parameters;
			$expertise = explode(",", $GM->get_record('expertise'));
			$current_expertise_cost = floor($expertise[$current_expertise]*$buildings_cost[$current_expertise] + $buildings_cost[$current_expertise]);
			if ($current_money - $current_expertise_cost > 0) {				
				$GM->set_record('money', $current_money - $current_expertise_cost);
				$expertise[$current_expertise] = $expertise[$current_expertise] + 1;
				$expertise_update = implode(',', $expertise);
				$GM->set_record('expertise', $expertise_update);
			}
		}
	} else {
		echo 'logoff';
	}
?>
