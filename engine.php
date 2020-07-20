<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);

	class game {
		private $game_session = '';
		function __construct($game_session) {
			$this->game_session = $game_session;
		}
		public function get_record($record_type) {
			Global $DB;
			$sql = "SELECT `".$record_type."` FROM `users` WHERE `id`='".$this->game_session."';";
			$result = $DB->getquery($sql);
			return $result[0][0];
		}
		public function set_record($record_type, $record_value) {
			Global $DB;
			$sql = "UPDATE `users` SET `".$record_type."` = '".$record_value."' WHERE `id`='".$this->game_session."';";
			$result = $DB->setquery($sql);
		}
		public function get_games_played() {
			Global $DB;
			$sql = "SELECT COUNT(*) FROM `users`;";
			$result = $DB->getquery($sql);
			return $result[0][0];
		}
		public function highscores($records) {
			Global $DB;
			$sql = "SELECT `name`, `city`, `year`, `end`, `money`, `citizens`, `difficulty`, `buildings`, `score` FROM `users` ORDER BY `users`.`score` DESC LIMIT ".$records.";";
			$result = $DB->getquery($sql);
			return $result;
		}
		public function delete($login_id) {
			Global $DB;
			$sql = "DELETE FROM `users` WHERE `users`.`id` = '".$login_id."'";
			$result = $DB->setquery($sql);
		}
		public function create($login_username, $login_password, $login_email, $login_city, $login_end, $login_money, $login_citizens, $login_landmass, $login_difficulty, $login_mountain) {
			Global $CFG;
			Global $DB;
			$demolish_cost = rand(10, (10 + $login_difficulty));
			$buildings_cost = '';
			$prices = '';
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				$buildings_cost = $buildings_cost.ceil(rand(rand(10,50), (50 + rand(10,50)*(1 + $login_difficulty)))).',';
				$prices = $prices.ceil(rand(rand(1,5), (10 + rand(1,10)*(1 + $login_difficulty)))).',';
			}
			$buildings_cost = substr($buildings_cost, 0, -1);
			$prices = substr($prices, 0, -1);	
			$map = '';
			for ($i = 1; $i <= $CFG->get('GAME','MAP_SIZE'); $i++) {
				if (rand(1,100) > $login_landmass) {
					// Sea 1
					$map = $map.'1,';
				} else {
					if (rand(1,110) > $login_difficulty) {
						// Land - Praire 0
						$map = $map.'0,';
					} else {
						if (rand(1,100) < $login_mountain) {
							// Land - Mountain 2
							$map = $map.'2,';
						} else {
							// Land - Forest 3
							$map = $map.'3,';
						}
					}
				}
			}
			$map = substr($map, 0, -1);
			$sql = "SELECT COUNT(*) FROM `users` WHERE `name`='".$login_username."';";
			$result = $DB->getquery($sql);
			if ($result[0][0] == 0) {
				$sql = "INSERT INTO `users` (`id`, `name`, `password`, `email`, `city`, `map`, `buildings`, `buildings_cost`, `warehouse`, `prices`, `prices_exchange`, `year`, `end`, `money`, `citizens`, `demolish_cost`, `builds`, `demolitions`, `difficulty`) VALUES (NULL, '".$login_username."', MD5('".$login_password."'), '".$login_email."', '".$login_city."', '".$map."', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '".$buildings_cost."', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '".$prices."', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0', '".$login_end."', '".$login_money."', '".$login_citizens."', '".$demolish_cost."', '0', '0', '".$login_difficulty."');";
				$result = $DB->setquery($sql);
				$new_user = $DB->getquery_id();
				$this->game_session = $new_user;
				return $new_user;
			} else {
				return -1;
			}
		}
	}	
	class template {
		protected $file;
		protected $values = array();
		public function open($file) {
			$this->file = $file;
			if($file != '') {
				if(is_readable($file)) {
					$this->file = $file;
					return(TRUE);
				} else {
					return(FALSE);
				}
			}
		}
		public function set($key, $value) {
			$this->values[$key] = $value;
		}
		public function get() {
			if (!file_exists($this->file)) {
				die($this->file);
			}
			$output = file_get_contents($this->file);
			foreach ($this->values as $key => $value) {
				$tagToReplace = "[@$key]";
				$output = str_replace($tagToReplace, $value, $output);
			}
			return $output;
		}
	}
	class user {
		private $user_name = '';
		private $user_password = '';
		private $user_id = '';
		public function set_name($user_name='') {
			$this->user_name = $user_name;
		}
		public function set_password($user_password='') {
			$this->user_password = $user_password;
		}
		public function set_id($user_id='') {
			$this->user_id = $user_id;
		}
		public function get_id() {
			return $this->user_id;
		}
		public function random_password() {
			$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			$random_password = array();
			$alphabet_length = strlen($alphabet) - 1;
			for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphabet_length);
				$random_password[] = $alphabet[$n];
			}
			return implode($random_password);
		}
		public function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '') {
			$from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
			$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
			$headers = "From: $from_user <$from_email>\r\n"."MIME-Version: 1.0"."\r\n"."Content-type: text/html; charset=UTF-8"."\r\n";
			return mail($to, $subject, $message, $headers);
		}
		public function reset_user($user_email='', $mail_from_user, $mail_from_email, $mail_subject) {
			Global $CFG;
			Global $DB;
			$output = false;
			$sql = "SELECT COUNT(*) FROM `users` WHERE `email`='".$user_email."';";
			$result = $DB->getquery($sql);
			if ($result[0][0] == 1) {
				$output = true;
				$sql = "SELECT * FROM `users` WHERE `email`='".$user_email."';";
				$result = $DB->getquery($sql);
				$this->set_id($result[0]['id']);
				$this->set_name($result[0]['name']);
				$new_password = $this->random_password();
				$this->set_password($new_password);
				$sql = "UPDATE `users` SET `password` = MD5('".$new_password."') WHERE `id`='".$this->user_id."';";
				$result = $DB->setquery($sql);
				$mail_to = $user_email;
				$mail_message = $new_password;
				$this->mail_utf8($mail_to, $mail_from_user, $mail_from_email, $mail_subject, $mail_message);
			} else {
				$output = false;
			}
			return $output;
		}
		public function validate() {
			Global $CFG;
			Global $DB;
			$output = false;
			if ($this->user_id == '') {
				$sql = "SELECT COUNT(*) FROM `users` WHERE `name`='".$this->user_name."' AND `password`=MD5('".$this->user_password."');";
			} else {
				$sql = "SELECT COUNT(*) FROM `users` WHERE `id`='".$this->user_id."';";
			}
			$result = $DB->getquery($sql);
			if ($result[0][0] == 1) {
				$output = true;
				$sql = "SELECT * FROM `users` WHERE `name`='".$this->user_name."' AND `password`=MD5('".$this->user_password."');";
				$result = $DB->getquery($sql);
				$this->user_id = $result[0]['id'];
			} else {
				$output = false;
			}
			return $output;
		}
	}	
	class session {
		public function __construct() {
			if(!isset($_SESSION)) {
				session_start();
				return TRUE;
			} else {
				return FALSE;
			}
		}
		public function erase_session() {
			setcookie(session_name(), NULL, 0, "/");
			$status = session_status();
			if($status == PHP_SESSION_NONE){
				session_start();
			} else if($status == PHP_SESSION_DISABLED){
			} else if($status == PHP_SESSION_ACTIVE){
				session_destroy();
				session_start();
			}
			session_destroy();
			session_unset();
		}
		public function set($key, $value) {
			return $_SESSION[$key] = $value;
		}
		public function exist($key) {
			if(isset($_SESSION[$key])) {
				return TRUE;
			}
			return FALSE;
		}
		public function delete($key) {
			unset($_SESSION[$key]);
		}
		public function get($key) {
			if(!isset($_SESSION[$key])) {
				return FALSE;
			}
			return $_SESSION[$key];
		}
	}
	class database {
		private $db_user;
		private $db_password;
		private $db_host;
		private $db_name;
		private $db_connection;
		function __construct($host, $name, $password, $user) {
			$this->db_host = $host;
			$this->db_name = $name;
			$this->db_password = $password;
			$this->db_user = $user;
			$this->db_connection = "";
		}
		public function connect() {
			$this->db_connection = mysqli_connect($this->db_host, $this->db_user, $this->db_password, $this->db_name);
		}
		public function setquery($query) {
			mysqli_query($this->db_connection, $query) or die(mysqli_error($this->db_connection));			
		}
		public function getquery($query) {
			$result = mysqli_query($this->db_connection, $query) or die(mysqli_error($this->db_connection));
			$returnarray = array ();
			while ($row = mysqli_fetch_array($result)) {
				$returnarray[] = $row;
			}
			return $returnarray;
		}
		public function getquery_id() {
			return mysqli_insert_id($this->db_connection);
		}
		public function getinfo($info_type) {
			if ($info_type == 'server_info') {
				return mysqli_get_server_info($this->db_connection);
			} else if ($info_type == 'server_version') {
				return mysqli_get_server_version($this->db_connection);
			} else if ($info_type == 'client_info') {
				return mysqli_get_client_info($this->db_connection);
			} else if ($info_type == 'client_version') {
				return mysqli_get_client_version($this->db_connection);
			} else if ($info_type == 'database_size') {
				$result = mysqli_query($this->db_connection, "SHOW TABLE STATUS") or die(mysqli_error($this->db_connection));
				$total = 0;
				while($row = mysqli_fetch_array($result, MYSQL_BOTH)) {
					$total = $total + $row['Data_length']+$row['Index_length'];
				}
				return($total);
			} else if ($info_type == 'database_tables') {
				$result = mysqli_query($this->db_connection, "SHOW TABLE STATUS") or die(mysqli_error($this->db_connection));
				$tables = "";
				while($row = mysqli_fetch_array($result, MYSQL_BOTH)) {
					$tables[$row['Name']]['total_size'] = ($row['Data_length']+$row['Index_length']);
					$tables[$row['Name']]['total_records'] = $row['Rows'];
					$tables[$row['Name']]['data_size'] = $row['Data_length'];
					$tables[$row['Name']]['index_size'] = $row['Index_length'];
					$tables[$row['Name']]['average_size_per_row'] = $row['Avg_row_length'];
				}
				return $tables;
			}
		}
	}
	class ini {
		private $file = NULL;
		private $data = array();
		public function open($file) {
			$this->file = $file;
			if($file != '') {
				if(is_readable($file)) {
					$this->file = $file;
					return(TRUE);
				} else {
					return(FALSE);
				}
			} else {
				return(FALSE);
			}
		}
		public function read() {
			$this->data = parse_ini_file(realpath($this->file), TRUE);
			if ($this->data == FALSE) {
				return(FALSE);
			} else {
				return(TRUE);
			}
		}
		public function write() {
			$content = NULL;
			foreach ($this->data as $section => $data) {
				$content = $content.'['.$section.']'.PHP_EOL;
				foreach ($data as $key => $val) {
					if (is_array($val)) {
						foreach ($val as $v) {
							$content = $content.$key.'[] = '.(is_numeric($v) ? $v : '"'.$v.'"').PHP_EOL;
						}
					} elseif (empty($val)) {
						$content = $content.$key.' = '.PHP_EOL;
					} else {
						$content = $content.$key.' = '.(is_numeric($val) ? $val : '"'.$val.'"').PHP_EOL;
					}
				}
				$content = $content.PHP_EOL;
			}
			return (($handle = fopen($this->file, 'w')) && fwrite($handle, ";<?php".PHP_EOL.";die();".PHP_EOL.";/*".PHP_EOL.trim($content).PHP_EOL.";*/".PHP_EOL.";?>".PHP_EOL) && fclose($handle)) ? TRUE : FALSE;
		}
		public function exist($section, $key = NULL) {
			if ($key != NULL ) {
				if (isset($this->data[$section][$key])) {
					return(TRUE);
				} else {
					return(FALSE);
				}
			} else {
				if (isset($this->data[$section])) {
					return(TRUE);
				} else {
					return(FALSE);
				}
			}
		}
		public function get($section, $key) {
			if (isset($this->data[$section][$key])) {
				return $this->data[$section][$key];
			} else {
				return(FALSE);
			}
		}
		public function set($section, $key, $value) {
			if (isset($this->data[$section][$key])) {
				$this->data[$section][$key] = $value;
				return(TRUE);
			} else {
				$this->data[$section][$key] = $value;
				return(FALSE);
			}
		}
		public function delete($section, $key = NULL) {
			if ($key != NULL ) {
				if (isset($this->data[$section][$key])) {
					unset($this->data[$section][$key]);
					return(TRUE);
				} else {
					return(FALSE);
				}
			} else {			
				if (isset($this->data[$section])) {
					unset($this->data[$section]);
					return(TRUE);
				} else {
					return(FALSE);
				}
			}
		}
	}
?>
