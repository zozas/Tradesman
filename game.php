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
	// Check for login POST variables or current session login variables
	$login_username = '';
	if ($SES->exist('USER_NAME'))
		$login_username = $SES->get('USER_NAME');
	$login_password = '';
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
	// Validate user
	if ($USR->validate() == true) {
		$GM = new game($SES->get('USER_ID'));
		if($GM->get_record('end') != 0)
			if ($GM->get_record('end') - $GM->get_record('year') <= 0)
				$action = 'endgame';
		//
		//
		//
		//
		// INTERVAL
		//
		//
		//
		//	
		if ($action == 'interval') {
			$GM->load_session();
			$TPL = new template;
			$TPL->open('game.interval.tpl');
			$TPL_METATAGS = new template;
			if($GM->get_record('end') != 0) {
				if ($GM->get_record('end') - $GM->get_record('year') <= 0) {
					$TPL->set('view', 'view_end');
				} else {
					$TPL->set('view', 'view_next');
				}
			} else {
				$TPL->set('view', 'view_next');
			}
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
			$TPL->set('city', $GM->get_record('city'));
			$TPL->set('season', $LNG->get('GENERAL','season'));
			$TPL->set('continue', $LNG->get('GENERAL','continue'));
			$TPL->set('continue_snap', $LNG->get('GENERAL','continue_snap'));
			$TPL->set('coordinate_x', $GM->get_record('coordinate_x'));
			$TPL->set('coordinate_y', $GM->get_record('coordinate_y'));
			// Update year
			$GM->set_record('year', $GM->get_record('year') + 1);
			$TPL->set('number_format_decimal', $LNG->get('CONFIG','charset_decimal'));
			$TPL->set('number_format_thousand', $LNG->get('CONFIG','charset_thousand'));
			$date = new DateTime('1492-10-10');
			$interval = new DateInterval('P'.$GM->get_record('year').'D');
			$date->add($interval);
			$TPL->set('year', $date->format($LNG->get('CONFIG','date_format')).'&nbsp;<small>'.($GM->get_record('end') - $GM->get_record('year')).'&times;'.$GM->get_record('duration').'</small>');
			$TPL->set('summary_content', $LNG->get('GENERAL','season_summary'));
			$TPL->set('season_days', '<i><b>'.$GM->get_record('year').'</b>&nbsp;'.$LNG->get('GENERAL','season_days_past').'</i>');
			if($GM->get_record('end') != 0) {
				if ($GM->get_record('end') - $GM->get_record('year') <= 0) {
					$TPL->set('season_remain', '');
				} else {
					$TPL->set('season_remain', '<i><b>'.($GM->get_record('end') - $GM->get_record('year')).'</b>&nbsp;'.$LNG->get('GENERAL','season_days_remain').'</i>');
				}
			} else {
				$TPL->set('season_remain', '');
			}
			// Update production & tax
			$buildings = explode(',', $GM->get_record('buildings'));
			$total_buildings = 0;
			$citizens_available = $GM->get_record('citizens');
			$tilemap_size = count($buildings); 
			$warehouse_items = explode(',', $GM->get_record('warehouse'));
			$food_production_rate = 0;
			for ($i = 1; $i < $tilemap_size; $i++) {
				if ($buildings[$i - 1] > 0)
					$total_buildings = $total_buildings + 1;
			}
			$TPL->set('production', $LNG->get('GENERAL','production'));
			$production_warnings = "";
			$speciality = 0;
			$citizen_experience = explode(',', $GM->get_record('expertise'));
			for ($i = 1; $i < $tilemap_size; $i++) {
				if($buildings[$i - 1] > 0) {
					if ($total_buildings == 0)
						$speciality = 1 + floor($citizens_available);
					else
						$speciality = 1 + floor($citizens_available/$total_buildings);
					$current_good_name = '';
					if($buildings[$i - 1] < 10) {
						$current_good_name = '8100'.$buildings[$i - 1];
					} else {
						$current_good_name = '810'.$buildings[$i - 1];
					}
					$current_good_manufacturing = $CFG->get('GAME_ITEMS_MANUFACTURING', $current_good_name);
					if ($CFG->get('GAME_ITEMS_MANUFACTURING', $current_good_name) == 0) {
						$warehouse_items[$buildings[$i - 1] - 1] = $warehouse_items[$buildings[$i - 1] - 1] + floor(1*$speciality + $citizen_experience[$buildings[$i - 1] - 1]);
						if ($buildings[$i - 1] > 10) {
							if ($CFG->get('GAME_ITEMS_FOOD','810'.$buildings[$i - 1]) == 1) {
								$food_production_rate = $food_production_rate + 1;
							}
						} else {
							if ($CFG->get('GAME_ITEMS_FOOD','8100'.$buildings[$i - 1]) == 1) {
								$food_production_rate = $food_production_rate + 1;
							}
						}
					} else {
						if($warehouse_items[($current_good_manufacturing - 81000) - 1] > 0) {
							$warehouse_items[($current_good_manufacturing - 81000) - 1] = $warehouse_items[($current_good_manufacturing - 81000) - 1] - 1;
							$warehouse_items[$buildings[$i - 1] - 1] = $warehouse_items[$buildings[$i - 1] - 1] + floor(1*$speciality + $citizen_experience[$buildings[$i - 1] - 1]);
							if ($buildings[$i - 1] == $CFG->get('GAME','FOOD_PRODUCT')) {
								$food_production_rate = $food_production_rate + 2;
							}
						} else {
							$production_warnings = $production_warnings."<tr><td><img src='".(81000 + $buildings[$i - 1]).".png'></td><td>&harr;</td><td><img src='".$current_good_manufacturing.".png'></td><td align='right'><small>".$LNG->get('ITEMS', 81000 + $buildings[$i - 1])."</small></td><td>&harr;</td><td align='left'><small>".$LNG->get('ITEMS', $current_good_manufacturing)."</small></td><td>&nbsp;&nbsp;</td><td><img src='".(80000 + $buildings[$i - 1]).".png'></td><td>&harr;</td><td><img src='".($current_good_manufacturing-1000).".png'></td><td align='right'><small>".$LNG->get('BUILDINGS', 80000 + $buildings[$i - 1])."</td><td>&harr;</td><td align='left'><small>".$LNG->get('BUILDINGS', $current_good_manufacturing-1000)."</small></td></tr>";
						}
					}
				}
			}
			if ($total_buildings > 0)
				$speciality = 1 + ($citizens_available/$total_buildings);
			else
				$speciality = 1;
			$speciality = number_format($speciality, 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal'));
			if ($production_warnings == '')
				$production_warnings = '<br>'.$LNG->get('GENERAL','good_reduced_production').' : <b>0</b><table>';
			else
				$production_warnings = '<br>'.$LNG->get('GENERAL','good_reduced_production').' :<br><table>'.$production_warnings;
			// Update tax
			$current_tax = $GM->get_record('tax');
			$tax_trend = 0;
			$tax_trend = rand(1, $citizens_available) - rand(1, $total_buildings) + ($GM->get_record('difficulty')/100);
			if ($tax_trend > 1) {
				$current_tax = $current_tax + 1;
				$GM->set_record('tax', $current_tax);
				$current_tax = $current_tax." %"." <span style='color:green;'>&and;</span>";
			} else if ($tax_trend == 1) {
				$GM->set_record('tax', $current_tax);
				$current_tax = $current_tax." %"." <span style='color:blue;'>&asymp;</span>";
			} else {
				if ($current_tax > 0)
					$current_tax = $current_tax - 1;
				else
					$current_tax = 0;
				$GM->set_record('tax', $current_tax);
				$current_tax = $current_tax." %"." <span style='color:red;'>&or;</span>";
			}
			$TPL->set('production_summary', $LNG->get('GENERAL','tax').' : <b>'.$current_tax. '</b><br>'.$LNG->get('GENERAL','speciality').' : <b>'.$speciality.'</b><br>'.$production_warnings.'</table>');
			$warehouse_items_update = implode(',', $warehouse_items);
			$GM->set_record('warehouse', $warehouse_items_update);
			$food_rations_available = $food_production_rate;
			$citizens_available = $GM->get_record('citizens');
			$TPL->set('population', $LNG->get('GENERAL','population'));
			if($citizens_available > $food_rations_available) {
				$warehouse_items[$CFG->get('GAME','FOOD_PRODUCT')-1] = 0;
				$food_rations_available = 0;
				for ($j = 1; $j <= $CFG->get('GAME','PRODUCTS'); $j++) {
					if ($j > 10) {
						if ($CFG->get('GAME_ITEMS_FOOD','810'.$j) == 1) {
							$warehouse_items[$j-1] = 0;
						}
					} else {
						if ($CFG->get('GAME_ITEMS_FOOD','8100'.$j) == 1) {
							$warehouse_items[$j-1] = 0;
						}
					}				
				}
				$citizens_decrease_rate = 1;
				$citizens_available = $citizens_available - $citizens_decrease_rate;
				if ($citizens_available < 0)
					$citizens_available = 0;
				if ($citizens_available == 0)
					$TPL->set('citizen_summary', $LNG->get('GENERAL','citizens_decrease').'<br>'.$LNG->get('GENERAL','citizens_decrease_rate').' <b>'.$citizens_decrease_rate.'</b> <small>(0%)</small><br>'.$LNG->get('GENERAL','expertise_decrease'));
				else
					$TPL->set('citizen_summary', $LNG->get('GENERAL','citizens_decrease').'<br>'.$LNG->get('GENERAL','citizens_decrease_rate').' <b>'.$citizens_decrease_rate.'</b> <small>(-'.number_format(($citizens_decrease_rate/$citizens_available)*100, 0).'%)</small><br>'.$LNG->get('GENERAL','expertise_decrease'));
				// If citizens zero reduce expertise
				if ($citizens_available == 0) {
					$reduced_experience = false;
					$citizen_experience = explode(',', $GM->get_record('expertise'));
					for ($k = 1; $k <= $CFG->get('GAME','PRODUCTS'); $k++) {
						if(($citizen_experience[$k - 1] > 0) && ($reduced_experience == false)) {
							$citizen_experience[$k - 1] = $citizen_experience[$k - 1] - 1;
							$citizen_experience_update = implode(',', $citizen_experience);
							$GM->set_record('expertise', $citizen_experience_update);
							$reduced_experience = true;
						}
					}					
				}
			} else if($citizens_available <= $food_rations_available) {
				$food_rations_available = $food_rations_available - $citizens_available;
				for ($j = 1; $j <= $CFG->get('GAME','PRODUCTS'); $j++) {
					if ($j > 10) {
						if ($CFG->get('GAME_ITEMS_FOOD','810'.$j) == 1) {
							$warehouse_items[$j-1] = $warehouse_items[$j-1] - $food_rations_available;
							if ($warehouse_items[$j-1] < 0 ) {
								$food_rations_available = abs($warehouse_items[$j-1]);
								$warehouse_items[$j-1] = 0;
							} else {
								$food_rations_available = 0;
							}
						}
					} else {
						if ($CFG->get('GAME_ITEMS_FOOD','8100'.$j) == 1) {
							$warehouse_items[$j-1] = $warehouse_items[$j-1] - $food_rations_available;
							if ($warehouse_items[$j-1] < 0 ) {
								$food_rations_available = abs($warehouse_items[$j-1]);
								$warehouse_items[$j-1] = 0;
							} else {
								$food_rations_available = 0;
							}
						}
					}				
				}
				$food_rations_available = 0;
				for ($j = 1; $j <= $CFG->get('GAME','PRODUCTS'); $j++) {
					if ($j > 10) {
						if ($CFG->get('GAME_ITEMS_FOOD','810'.$j) == 1) {
							$food_rations_available = $food_rations_available + $warehouse_items[$j-1];
						}
					} else {
						if ($CFG->get('GAME_ITEMS_FOOD','8100'.$j) == 1) {
							$food_rations_available = $food_rations_available + $warehouse_items[$j-1];
						}
					}
				}
				$citizens_increase_rate = 1;
				$citizens_available = $citizens_available + $citizens_increase_rate;
				$TPL->set('citizen_summary', $LNG->get('GENERAL','citizens_increase').'<br>'.$LNG->get('GENERAL','citizens_increase_rate').' <b>'.$citizens_increase_rate.'</b> <small>(+'.number_format(($citizens_increase_rate/$citizens_available)*100, 0).'%)</small>');
			} else {
				if ($citizens_available == 0) {
					$TPL->set('citizen_summary', $LNG->get('GENERAL','citizens_dead').'<br>'.$LNG->get('GENERAL','citizens_dead_rate'));
				} else {
					$TPL->set('citizen_summary', $LNG->get('GENERAL','citizens_stall').'<br>'.$LNG->get('GENERAL','citizens_stall_rate'));
				}
			}
			// Food
			$TPL->set('food', $LNG->get('GENERAL','food'));
			$food_summary = '';
			if (($food_rations_available+$food_production_rate-$citizens_available)>0) {
				$food_summary =  $LNG->get('GENERAL','food_surplus').' <b>+'.($food_rations_available+$food_production_rate-$citizens_available).'</b>';
			} else if (($food_rations_available+$food_production_rate-$citizens_available)<0) {
				$food_summary =  $LNG->get('GENERAL','food_sortage').' <b>'.($food_rations_available+$food_production_rate-$citizens_available).'</b>';
			} else {
				$food_summary =  $LNG->get('GENERAL','food_equilibrium');
			}
			$TPL->set('food_summary', $food_summary.'<br><b>'.$food_production_rate.'</b> '.$LNG->get('GENERAL','food_produced').'<br>'.'<b>'.$citizens_available.'</b> '.$LNG->get('GENERAL','food_consumed').'<br>'.'<b>'.$food_rations_available.'</b> '.$LNG->get('GENERAL','food_stored').'<br>');
			$GM->set_record('citizens', $citizens_available);
			$warehouse_items_update = implode(',', $warehouse_items);
			$GM->set_record('warehouse', $warehouse_items_update);
			$TPL->set('citizens', number_format($citizens_available, 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			// Prices
			$prices_exchange = explode(',', $GM->get_record('prices_exchange'));
			$warehouse_items = explode(',', $GM->get_record('warehouse'));
			$warehouse_prices = explode(',', $GM->get_record('prices'));
			$TPL->set('warehouse_content', $LNG->get('GENERAL','warehouse')." & ".$LNG->get('GENERAL','commodities'));
			$TPL->set('warehouse', $GM->get_record('warehouse'));
			$TPL->set('prices', $GM->get_record('prices'));
			$TPL->set('warehouse_prices', $LNG->get('GENERAL','price'));
			$TPL->set('warehouse_quantity', $LNG->get('GENERAL','quantity'));
			$TPL->set('warehouse_total', $LNG->get('GENERAL','total'));
			$TPL->set('warehouse_balance', $LNG->get('GENERAL','balance'));
			$TPL->set('warehouse_trend', $LNG->get('GENERAL','trend'));
			$TPL->set('warehouse_total_value', $LNG->get('GENERAL','total_value'));
			$TPL->set('warehouse_commodities', $LNG->get('GENERAL','commodities'));
			$warehouse_status = "";
			$total_value = 0;
			$updated_prices = $warehouse_prices;
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				$TPL_WAREHOUSE_ITEM = new template;
				$TPL_WAREHOUSE_ITEM->open('game.warehouse.tpl');
				$TPL_WAREHOUSE_ITEM->set('product_item', $i);
				if($i < 10) {
					$TPL_WAREHOUSE_ITEM->set('product_item_name', $LNG->get('ITEMS','8100'.$i));
					$TPL_WAREHOUSE_ITEM->set('product_item_image', '8100'.$i.'.png');
				} else {
					$TPL_WAREHOUSE_ITEM->set('product_item_name', $LNG->get('ITEMS','810'.$i));
					$TPL_WAREHOUSE_ITEM->set('product_item_image', '810'.$i.'.png');
				}
				$TPL_WAREHOUSE_ITEM->set('product_item_quantity', number_format($warehouse_items[$i-1], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_WAREHOUSE_ITEM->set('product_item_price', number_format($warehouse_prices[$i-1], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_WAREHOUSE_ITEM->set('product_item_tax', number_format($warehouse_prices[$i-1]*($GM->get_record('tax')/100), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_WAREHOUSE_ITEM->set('product_item_value', number_format(($warehouse_items[$i-1]*$warehouse_prices[$i-1]), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$total_value = $total_value + ($warehouse_items[$i-1]*$warehouse_prices[$i-1]);
				$TPL_WAREHOUSE_ITEM->set('product_item_buy_button', number_format(($prices_exchange[$i-1]*$warehouse_prices[$i-1]), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				if ($GM->get_record('money') == 0) {
					$trend = 0;
				} else {
					$trend = $prices_exchange[$i-1] / $GM->get_record('money');
				}
				if ($prices_exchange[$i-1] > 0) {
					$TPL_WAREHOUSE_ITEM->set('product_item_sell_button', "<span style='color:green;'>&and;</span>");
				} else if ($prices_exchange[$i-1] < 0) {
					$TPL_WAREHOUSE_ITEM->set('product_item_sell_button', "<span style='color:red;'>&or;</span>");
				} else {
					$TPL_WAREHOUSE_ITEM->set('product_item_sell_button', "<span style='color:blue;'>&asymp;</span>");
					$trend_possibility = rand(0, 100);
					if($trend_possibility <= $CFG->get('GAME','PRICE_RECOVERY_CHANCE')) {
						$trend = $CFG->get('GAME','PRICE_RECOVERY') / (100);
					} else if($trend_possibility > $CFG->get('GAME','PRICE_RECOVERY_CHANCE')) {
						$trend = $CFG->get('GAME','PRICE_RECOVERY') / (-100);
					}
				}
				$updated_prices[$i-1] = floor($updated_prices[$i-1] + $updated_prices[$i-1]*$trend + 1);
				$warehouse_status = $warehouse_status.$TPL_WAREHOUSE_ITEM->get();
			}
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				if ($updated_prices[$i-1] < 0)
					$updated_prices[$i-1] = 0;
			}
			$TPL->set('total_value', number_format($total_value, 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('warehouse_status', $warehouse_status);
			$warehouse_prices = implode(',', $updated_prices);
			$GM->set_record('prices', $warehouse_prices);
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				$prices_exchange[$i - 1] = 0;
			}
			$prices_exchange = implode(',', $prices_exchange);
			$GM->set_record('prices_exchange', $prices_exchange);
			// Building & demolishing costs
			$TPL->set('real_estate', $LNG->get('GENERAL','real_estate'));
			$builds = $GM->get_record('builds');
			$demolitions = $GM->get_record('demolitions');
			$build_factor = 0;
			$demolition_factor = 0;
			if (($builds + $demolitions)!= 0) {
				$build_factor = $builds / ($builds + $demolitions);
				$demolition_factor = $demolitions / ($builds + $demolitions);
			}
			$build_status = $LNG->get('GENERAL','build_unchanged');
			$buildings_cost = explode(',', $GM->get_record('buildings_cost'));
			if ($build_factor > 0.5) {
				$build_status = $LNG->get('GENERAL','build_increase');
				for ($i = 0; $i <= ($CFG->get('GAME','PRODUCTS')-1); $i++) {
					$buildings_cost[$i] = ceil($buildings_cost[$i] + (1 + $build_factor));
					$buildings_cost_increased = implode(',', $buildings_cost);
					$GM->set_record('buildings_cost', $buildings_cost_increased);
				}
			} else if ($build_factor < 0.5 && $build_factor > 0) {
				$build_status = $LNG->get('GENERAL','build_decrease');
				for ($i = 0; $i <= ($CFG->get('GAME','PRODUCTS')-1); $i++) {
					$buildings_cost[$i] = $buildings_cost[$i];
					$buildings_cost[$i] = ceil($buildings_cost[$i] - (1 + $build_factor));
					if($buildings_cost[$i] <= 0)
						$buildings_cost[$i] = 1;
					$buildings_cost_decreased = implode(',', $buildings_cost);
					$GM->set_record('buildings_cost', $buildings_cost_decreased);
				}
			}
			$demolish_status = $LNG->get('GENERAL','demolish_unchanged');
			if ($demolition_factor > 0.5) {
				$demolish_status = $LNG->get('GENERAL','demolish_increase');
				$GM->set_record('demolish_cost', floor($GM->get_record('demolish_cost')+1*$demolition_factor));
			} else if ($demolition_factor < 0.5 && $demolition_factor > 0) {
				$demolish_status = $LNG->get('GENERAL','demolish_decrease');
				$GM->set_record('demolish_cost', floor($GM->get_record('demolish_cost')-1*$demolition_factor));
			}
			if($GM->get_record('demolish_cost') <= 0)
				$GM->set_record('demolish_cost', 10);
			$TPL->set('real_estate_summary', $LNG->get('GENERAL','build_total').' : <b>'.$total_buildings.'</b><br>'.$LNG->get('GENERAL','build_occupied_percent').' : <b>'.number_format((100*$total_buildings/$tilemap_size), 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')).'%</b><br>'.$LNG->get('GENERAL','build_total_stats').' : <b>'.$GM->get_record('builds').'</b><br>'.$LNG->get('GENERAL','demolish_total_stats').' : <b>'.$GM->get_record('demolitions').'</b><br><br>'.$LNG->get('GENERAL','build_factor').' : <b>'.number_format(($build_factor), 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')).'</b><br>'.$build_status.'<br><br>'.$LNG->get('GENERAL','demolish_cost').' : <b>'.$GM->get_record('builds').'</b><br>'.$LNG->get('GENERAL','demolish_factor').' : <b>'.number_format(($demolition_factor), 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')).'</b><br>'.$demolish_status);





			//
			//
			//
			// TODO : RANDOM EVENTS
			//
			//

		
			
			
			
		
		
		
			// Update money
			$TPL->set('treasury', $LNG->get('GENERAL','treasury'));
			$warehouse_items = explode(',', $GM->get_record('warehouse'));
			$warehouse_prices = explode(',', $GM->get_record('prices'));
			$warehouse_amount = 0;
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				$warehouse_amount = $warehouse_amount + ($warehouse_items[$i-1]*$warehouse_prices[$i-1]);
			}
			$TPL->set('money_amount', number_format($GM->get_record('money'), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('warehouse_amount', number_format($warehouse_amount, 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			// Calculate score
			$TPL->set('score', $LNG->get('GENERAL','score'));
			$TPL->set('score_result', number_format($GM->get_record('score'), 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$score_result = (($GM->get_record('money') + $warehouse_amount)*((1 - $GM->get_record('tax'))/100) + ($GM->get_record('citizens')*1000) + ($total_buildings*500));
			if ($score_result < 0)
				$score_result = 0;
			if ($GM->get_record('end') != 0)
				$score_result =  $score_result / (1 + $GM->get_record('end') - $GM->get_record('year'));
			else
				$score_result =  $score_result / (1 + $CFG->get('GAME','DURATION_MAX') - $GM->get_record('year'));
			$score_result = $score_result * ($GM->get_record('difficulty')/100);
			$TPL->set('score_result', number_format($score_result, 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$GM->set_record('score', $score_result);
			// Statistics
			$GM->load_session();
			$statistics = $GM->statistics();
			$TPL->set('statistics_content', $LNG->get('GENERAL','statistics'));
			
			
			
//$build_factor = $builds / ($builds + $demolitions);
//<0.5 meiosi kai >0.5 afxisi
// ΑΡΧΗ 0
//$demolition_factor = $demolitions / ($builds + $demolitions);			
//<0.5 meiosi kai >0.5 afxisi
// ΑΡΧΗ 0
//$speciality = 1 + ($citizens_available/$total_buildings);
//posa paragei ena atomo
// ΑΡΧΗ 1
/*
 			$score_result = (($GM->get_record('money') + $warehouse_amount)*((1 - $GM->get_record('tax'))/100) + ($GM->get_record('citizens')*1000) + ($total_buildings*500));
			if ($GM->get_record('end') != 0)
				$score_result =  $score_result / (1 + $GM->get_record('end') - $GM->get_record('year'));
			else
				$score_result =  $score_result / (1 + $CFG->get('GAME','DURATION_MAX') - $GM->get_record('year'));
*/
			
			
			
				// Statistics - Score summary
				$TPL->set('statistics_score', $LNG->get('GENERAL','score'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', '');
				$TPL_GRAPH->set('start', 16*$statistics['historic']['score']/max(($statistics['historic']['score']+0.01), ($statistics['curent']['score']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['score']/max(($statistics['historic']['score']+0.01), ($statistics['curent']['score']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['score'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['score'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['score'] - $statistics['historic']['score'])/($statistics['historic']['score'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL->set('statistics_score_summary', $TPL_GRAPH->get());
				// Statistics - Money summary
				$TPL->set('statistics_money', $LNG->get('GENERAL','money'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','money_cash'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['money']['cash']/max(($statistics['historic']['money']['cash']+0.01), ($statistics['curent']['money']['cash']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['money']['cash']/max(($statistics['historic']['money']['cash']+0.01), ($statistics['curent']['money']['cash']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['money']['cash'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['money']['cash'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['money']['cash'] - $statistics['historic']['money']['cash'])/($statistics['historic']['money']['cash'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_cash = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','tax'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['tax']/max(($statistics['historic']['tax']+0.01), ($statistics['curent']['tax']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['tax']/max(($statistics['historic']['tax']+0.01), ($statistics['curent']['tax']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['tax'] - $statistics['historic']['tax'])/($statistics['historic']['tax'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_tax = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_build'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_build']/max(($statistics['historic']['stat_build']+0.01), ($statistics['curent']['stat_build']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_build']/max(($statistics['historic']['stat_build']+0.01), ($statistics['curent']['stat_build']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_build'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_build'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_build'] - $statistics['historic']['stat_build'])/($statistics['historic']['stat_build'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_build = $TPL_GRAPH->get();				
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_demolish'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_demolish']/max(($statistics['historic']['stat_demolish']+0.01), ($statistics['curent']['stat_demolish']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_demolish']/max(($statistics['historic']['stat_demolish']+0.01), ($statistics['curent']['stat_demolish']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_demolish'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_demolish'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_demolish'] - $statistics['historic']['stat_demolish'])/($statistics['historic']['stat_demolish'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_demolish = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_buy'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_buy']/max(($statistics['historic']['stat_buy']+0.01), ($statistics['curent']['stat_buy']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_buy']/max(($statistics['historic']['stat_buy']+0.01), ($statistics['curent']['stat_buy']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_buy'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_buy'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_buy'] - $statistics['historic']['stat_buy'])/($statistics['historic']['stat_buy'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_buy = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_sell'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_sell']/max(($statistics['historic']['stat_sell']+0.01), ($statistics['curent']['stat_sell']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_sell']/max(($statistics['historic']['stat_sell']+0.01), ($statistics['curent']['stat_sell']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_sell'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_sell'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_sell'] - $statistics['historic']['stat_sell'])/($statistics['historic']['stat_sell'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_sell = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_research'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_research']/max(($statistics['historic']['stat_research']+0.01), ($statistics['curent']['stat_research']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_research']/max(($statistics['historic']['stat_research']+0.01), ($statistics['curent']['stat_research']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_research'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_research'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_research'] - $statistics['historic']['stat_research'])/($statistics['historic']['stat_research'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_research = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_tax'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_tax']/max(($statistics['historic']['stat_tax']+0.01), ($statistics['curent']['stat_tax']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_tax']/max(($statistics['historic']['stat_tax']+0.01), ($statistics['curent']['stat_tax']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_tax'] - $statistics['historic']['stat_tax'])/($statistics['historic']['stat_tax'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_tax = $TPL_GRAPH->get();
				$TPL->set('statistics_money_summary', $money_cash.$money_tax.$money_stat_build.$money_stat_demolish.$money_stat_buy.$money_stat_sell.$money_stat_research.$money_stat_tax);
				// Statistics - Citizens summary
				$TPL->set('statistics_citizens', $LNG->get('GENERAL','citizens'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','citizens'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['citizens']/max(($statistics['historic']['citizens']+0.01), ($statistics['curent']['citizens']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['citizens']/max(($statistics['historic']['citizens']+0.01), ($statistics['curent']['citizens']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['citizens'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['citizens'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['citizens'] - $statistics['historic']['citizens'])/($statistics['historic']['citizens'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL->set('statistics_citizens_summary', $TPL_GRAPH->get());
				// Experience
				$citizen_experience = explode(',', $GM->get_record('expertise'));
				$experience = "";
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					if ($citizen_experience[$i - 1] > 0) {
						if($i > 10) {
							$experience = $experience."<img src='810".$i.".png' alt='".$LNG->get('ITEMS','810'.$i)."' title='".$LNG->get('ITEMS','810'.$i)."'> ".$LNG->get('ITEMS','810'.$i)." &times; ".$citizen_experience[$i - 1]."<br>";
						} else {
							$experience = $experience."<img src='8100".$i.".png' alt='".$LNG->get('ITEMS','8100'.$i)."' title='".$LNG->get('ITEMS','8100'.$i)."'> ".$LNG->get('ITEMS','8100'.$i)." &times; ".$citizen_experience[$i - 1]."<br>";
						}
					}
				}
				$TPL->set('statistics_experience', $LNG->get('GENERAL','expertise'));
				$TPL->set('statistics_citizen_experience_summary', $experience);
				// Statistics - Buildings summary
				$TPL->set('statistics_buildings', $LNG->get('GENERAL','buildings'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','demolish_cost'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['demolish_cost']/max(($statistics['historic']['demolish_cost']+0.01), ($statistics['curent']['demolish_cost']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['demolish_cost']/max(($statistics['historic']['demolish_cost']+0.01), ($statistics['curent']['demolish_cost']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['demolish_cost'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['demolish_cost'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['demolish_cost'] - $statistics['historic']['demolish_cost'])/($statistics['historic']['demolish_cost'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$buildings_demolish_cost = $TPL_GRAPH->get();
				$buildings_analytical = '';
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					$TPL_GRAPH = new template;
					$TPL_GRAPH->open('game.graph.tpl');
					if($i < 10) {
						$TPL_GRAPH->set('image', "<img src='8000".$i.".png'>&nbsp;".$LNG->get('BUILDINGS','8000'.$i));
					} else {
						$TPL_GRAPH->set('image', "<img src='800".$i.".png'>&nbsp;".$LNG->get('BUILDINGS','800'.$i));
					}
					$TPL_GRAPH->set('start', 16*$statistics['historic']['buildings_cost'][$i]/max(($statistics['historic']['buildings_cost'][$i]+0.01), ($statistics['curent']['buildings_cost'][$i]+0.01)));
					$TPL_GRAPH->set('end', 16*$statistics['curent']['buildings_cost'][$i]/max(($statistics['historic']['buildings_cost'][$i]+0.01), ($statistics['curent']['buildings_cost'][$i]+0.01)));
					$TPL_GRAPH->set('historic', number_format($statistics['historic']['buildings_cost'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('current', number_format($statistics['curent']['buildings_cost'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['buildings_cost'][$i] - $statistics['historic']['buildings_cost'][$i])/($statistics['historic']['buildings_cost'][$i] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$buildings_analytical = $buildings_analytical.$TPL_GRAPH->get();
				}
				$TPL->set('statistics_buildings_summary', $buildings_demolish_cost.$buildings_analytical);
				// Statistics - Prices summary
				$TPL->set('statistics_prices', $LNG->get('GENERAL','prices'));
				$prices_analytical = '';
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					$TPL_GRAPH = new template;
					$TPL_GRAPH->open('game.graph.tpl');
					if($i < 10) {
						$TPL_GRAPH->set('image', "<img src='8100".$i.".png'>&nbsp;".$LNG->get('ITEMS','8100'.$i));
					} else {
						$TPL_GRAPH->set('image', "<img src='810".$i.".png'>&nbsp;".$LNG->get('ITEMS','810'.$i));
					}
					$TPL_GRAPH->set('start', 16*$statistics['historic']['prices'][$i]/max(($statistics['historic']['prices'][$i]+0.01), ($statistics['curent']['prices'][$i]+0.01)));
					$TPL_GRAPH->set('end', 16*$statistics['curent']['prices'][$i]/max(($statistics['historic']['prices'][$i]+0.01), ($statistics['curent']['prices'][$i]+0.01)));
					$TPL_GRAPH->set('historic', number_format($statistics['historic']['prices'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('current', number_format($statistics['curent']['prices'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['prices'][$i] - $statistics['historic']['prices'][$i])/($statistics['historic']['prices'][$i] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$prices_analytical = $prices_analytical.$TPL_GRAPH->get();
				}
				$TPL->set('statistics_prices_summary', $prices_analytical);
			echo $TPL->get();
		//
		//
		//
		//
		// END GAME
		//
		//
		//
		//	
		} else if ($action == 'endgame') {
			$GM->load_session();
			$TPL = new template;
			$TPL->open('game.end.tpl');
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
			$TPL->set('logoff', $LNG->get('GENERAL','logoff'));
			$GM = new game($SES->get('USER_ID'));
			$TPL->set('city', $GM->get_record('city'));
			$TPL->set('season_days', '<i>'.$GM->get_record('year').'&nbsp;'.$LNG->get('GENERAL','season_days_past').'</i>');
			$date = new DateTime('1492-10-10');
			$interval = new DateInterval('P'.$GM->get_record('year').'D');
			$date->add($interval);
			$TPL->set('warehouse', $LNG->get('GENERAL','warehouse'));
			$TPL->set('year', $date->format($LNG->get('CONFIG','date_format')).'&nbsp;<small>'.($GM->get_record('end') - $GM->get_record('year')).'&times;'.$GM->get_record('duration').'</small>');
			$TPL->set('season', $LNG->get('GENERAL','season'));
			$TPL->set('treasury', $LNG->get('GENERAL','treasury'));
			$TPL->set('tax', $LNG->get('GENERAL','tax'));
			$TPL->set('total_tax', $GM->get_record('tax'). ' %');			
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
			$TPL->set('buildings', $LNG->get('GENERAL','build_total'));
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
			// Calculate score
			$TPL->set('score', $LNG->get('GENERAL','score'));
			$score_result = (($GM->get_record('money') + $warehouse_amount)*((1 - $GM->get_record('tax'))/100) + ($GM->get_record('citizens')*1000) + ($total_buildings*500));
			if ($score_result < 0)
				$score_result = 0;
			if ($GM->get_record('end') != 0)
				$score_result =  $score_result / ($GM->get_record('year') / $GM->get_record('end'));
			else
				$score_result =  $score_result / ($GM->get_record('year') / $CFG->get('GAME','DURATION_MAX'));			
			$score_result = $score_result * ($GM->get_record('difficulty')/100);
			$GM->set_record('score', $score_result);
			$TPL->set('score_result', number_format($score_result, 2, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			// Statistics
			$GM->load_session();
			$statistics = $GM->statistics();
			$TPL->set('statistics_content', $LNG->get('GENERAL','statistics'));
				// Statistics - Score summary
				$TPL->set('statistics_score', $LNG->get('GENERAL','score'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', '');
				$TPL_GRAPH->set('start', 16*$statistics['historic']['score']/max(($statistics['historic']['score']+0.01), ($statistics['curent']['score']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['score']/max(($statistics['historic']['score']+0.01), ($statistics['curent']['score']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['score'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['score'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['score'] - $statistics['historic']['score'])/($statistics['historic']['score'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL->set('statistics_score_summary', $TPL_GRAPH->get());
				// Statistics - Money summary
				$TPL->set('statistics_money', $LNG->get('GENERAL','money'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','money_cash'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['money']['cash']/max(($statistics['historic']['money']['cash']+0.01), ($statistics['curent']['money']['cash']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['money']['cash']/max(($statistics['historic']['money']['cash']+0.01), ($statistics['curent']['money']['cash']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['money']['cash'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['money']['cash'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['money']['cash'] - $statistics['historic']['money']['cash'])/($statistics['historic']['money']['cash'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_cash = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','tax'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['tax']/max(($statistics['historic']['tax']+0.01), ($statistics['curent']['tax']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['tax']/max(($statistics['historic']['tax']+0.01), ($statistics['curent']['tax']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['tax'] - $statistics['historic']['tax'])/($statistics['historic']['tax'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_tax = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_build'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_build']/max(($statistics['historic']['stat_build']+0.01), ($statistics['curent']['stat_build']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_build']/max(($statistics['historic']['stat_build']+0.01), ($statistics['curent']['stat_build']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_build'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_build'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_build'] - $statistics['historic']['stat_build'])/($statistics['historic']['stat_build'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_build = $TPL_GRAPH->get();				
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_demolish'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_demolish']/max(($statistics['historic']['stat_demolish']+0.01), ($statistics['curent']['stat_demolish']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_demolish']/max(($statistics['historic']['stat_demolish']+0.01), ($statistics['curent']['stat_demolish']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_demolish'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_demolish'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_demolish'] - $statistics['historic']['stat_demolish'])/($statistics['historic']['stat_demolish'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_demolish = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_buy'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_buy']/max(($statistics['historic']['stat_buy']+0.01), ($statistics['curent']['stat_buy']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_buy']/max(($statistics['historic']['stat_buy']+0.01), ($statistics['curent']['stat_buy']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_buy'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_buy'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_buy'] - $statistics['historic']['stat_buy'])/($statistics['historic']['stat_buy'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_buy = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_sell'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_sell']/max(($statistics['historic']['stat_sell']+0.01), ($statistics['curent']['stat_sell']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_sell']/max(($statistics['historic']['stat_sell']+0.01), ($statistics['curent']['stat_sell']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_sell'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_sell'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_sell'] - $statistics['historic']['stat_sell'])/($statistics['historic']['stat_sell'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_sell = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_research'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_research']/max(($statistics['historic']['stat_research']+0.01), ($statistics['curent']['stat_research']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_research']/max(($statistics['historic']['stat_research']+0.01), ($statistics['curent']['stat_research']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_research'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_research'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_research'] - $statistics['historic']['stat_research'])/($statistics['historic']['stat_research'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_research = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_tax'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_tax']/max(($statistics['historic']['stat_tax']+0.01), ($statistics['curent']['stat_tax']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_tax']/max(($statistics['historic']['stat_tax']+0.01), ($statistics['curent']['stat_tax']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_tax'] - $statistics['historic']['stat_tax'])/($statistics['historic']['stat_tax'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_tax = $TPL_GRAPH->get();
				$TPL->set('statistics_money_summary', $money_cash.$money_tax.$money_stat_build.$money_stat_demolish.$money_stat_buy.$money_stat_sell.$money_stat_research.$money_stat_tax);				
				// Statistics - Citizens summary
				$TPL->set('statistics_citizens', $LNG->get('GENERAL','citizens'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','citizens'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['citizens']/max(($statistics['historic']['citizens']+0.01), ($statistics['curent']['citizens']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['citizens']/max(($statistics['historic']['citizens']+0.01), ($statistics['curent']['citizens']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['citizens'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['citizens'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['citizens'] - $statistics['historic']['citizens'])/($statistics['historic']['citizens'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL->set('statistics_citizens_summary', $TPL_GRAPH->get());
				// Experience
				$citizen_experience = explode(',', $GM->get_record('expertise'));
				$experience = "";
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					if ($citizen_experience[$i - 1] > 0) {
						if($i > 10) {
							$experience = $experience."<img src='810".$i.".png' alt='".$LNG->get('ITEMS','810'.$i)."' title='".$LNG->get('ITEMS','810'.$i)."'> ".$LNG->get('ITEMS','810'.$i)." &times; ".$citizen_experience[$i - 1]."<br>";
						} else {
							$experience = $experience."<img src='8100".$i.".png' alt='".$LNG->get('ITEMS','8100'.$i)."' title='".$LNG->get('ITEMS','8100'.$i)."'> ".$LNG->get('ITEMS','8100'.$i)." &times; ".$citizen_experience[$i - 1]."<br>";
						}
					}
				}
				$TPL->set('statistics_experience', $LNG->get('GENERAL','expertise'));
				$TPL->set('statistics_citizen_experience_summary', $experience);
				// Statistics - Buildings summary
				$TPL->set('statistics_buildings', $LNG->get('GENERAL','buildings'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','demolish_cost'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['demolish_cost']/max(($statistics['historic']['demolish_cost']+0.01), ($statistics['curent']['demolish_cost']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['demolish_cost']/max(($statistics['historic']['demolish_cost']+0.01), ($statistics['curent']['demolish_cost']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['demolish_cost'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['demolish_cost'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['demolish_cost'] - $statistics['historic']['demolish_cost'])/($statistics['historic']['demolish_cost'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$buildings_demolish_cost = $TPL_GRAPH->get();
				$buildings_analytical = '';
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					$TPL_GRAPH = new template;
					$TPL_GRAPH->open('game.graph.tpl');
					if($i < 10) {
						$TPL_GRAPH->set('image', "<img src='8000".$i.".png'>&nbsp;".$LNG->get('BUILDINGS','8000'.$i));
					} else {
						$TPL_GRAPH->set('image', "<img src='800".$i.".png'>&nbsp;".$LNG->get('BUILDINGS','800'.$i));
					}
					$TPL_GRAPH->set('start', 16*$statistics['historic']['buildings_cost'][$i]/max(($statistics['historic']['buildings_cost'][$i]+0.01), ($statistics['curent']['buildings_cost'][$i]+0.01)));
					$TPL_GRAPH->set('end', 16*$statistics['curent']['buildings_cost'][$i]/max(($statistics['historic']['buildings_cost'][$i]+0.01), ($statistics['curent']['buildings_cost'][$i]+0.01)));
					$TPL_GRAPH->set('historic', number_format($statistics['historic']['buildings_cost'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('current', number_format($statistics['curent']['buildings_cost'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['buildings_cost'][$i] - $statistics['historic']['buildings_cost'][$i])/($statistics['historic']['buildings_cost'][$i] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$buildings_analytical = $buildings_analytical.$TPL_GRAPH->get();
				}
				$TPL->set('statistics_buildings_summary', $buildings_demolish_cost.$buildings_analytical);
				// Statistics - Prices summary
				$TPL->set('statistics_prices', $LNG->get('GENERAL','prices'));
				$prices_analytical = '';
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					$TPL_GRAPH = new template;
					$TPL_GRAPH->open('game.graph.tpl');
					if($i < 10) {
						$TPL_GRAPH->set('image', "<img src='8100".$i.".png'>&nbsp;".$LNG->get('ITEMS','8100'.$i));
					} else {
						$TPL_GRAPH->set('image', "<img src='810".$i.".png'>&nbsp;".$LNG->get('ITEMS','810'.$i));
					}
					$TPL_GRAPH->set('start', 16*$statistics['historic']['prices'][$i]/max(($statistics['historic']['prices'][$i]+0.01), ($statistics['curent']['prices'][$i]+0.01)));
					$TPL_GRAPH->set('end', 16*$statistics['curent']['prices'][$i]/max(($statistics['historic']['prices'][$i]+0.01), ($statistics['curent']['prices'][$i]+0.01)));
					$TPL_GRAPH->set('historic', number_format($statistics['historic']['prices'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('current', number_format($statistics['curent']['prices'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['prices'][$i] - $statistics['historic']['prices'][$i])/($statistics['historic']['prices'][$i] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$prices_analytical = $prices_analytical.$TPL_GRAPH->get();
				}
				$TPL->set('statistics_prices_summary', $prices_analytical);
			echo $TPL->get();
		//
		//
		//
		//
		// GAMEPLAY
		//
		//
		//
		//	
		} else {
			$GM->load_session();
			if ($action == 'snap') {
				$GM->snap();
			}
			$map = (string)$GM->get_record('map');
			$buildings = (string)$GM->get_record('buildings');
			$TPL = new template;
			$TPL->open('game.ui.tpl');
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
			$TPL_MANUAL = new template;
			$TPL_MANUAL->open($LNG->get('CONFIG','manual'));
			$TPL->set('ingame_manual', $TPL_MANUAL->get());
			$TPL->set('application_title', $CFG->get('APPLICATION','TITLE'));
			$TPL->set('user_name', $SES->get('USER_NAME'));
			$TPL->set('user_password', $SES->get('USER_PASSWORD'));
			$TPL->set('city', $GM->get_record('city'));
			$TPL->set('map', $map);
			$TPL->set('logoff', $LNG->get('GENERAL','logoff'));
			$TPL->set('manual', $LNG->get('GENERAL','manual'));
			$TPL->set('money', $GM->get_record('money'));
			$TPL->set('tax', $GM->get_record('tax'));
			$TPL->set('taxation', $LNG->get('GENERAL','tax').' '.$GM->get_record('tax').'%');
			$TPL->set('products_total', $CFG->get('GAME','PRODUCTS'));
			$TPL->set('treasury', $LNG->get('GENERAL','treasury'));
			$TPL->set('duration_round', $GM->get_record('duration'));
			$TPL->set('number_format_decimal', $LNG->get('CONFIG','charset_decimal'));
			$TPL->set('number_format_thousand', $LNG->get('CONFIG','charset_thousand'));
			$date = new DateTime('1492-10-10');
			$interval = new DateInterval('P'.$GM->get_record('year').'D');
			$date->add($interval);
			$TPL->set('year', $date->format($LNG->get('CONFIG','date_format')).'&nbsp;<small>'.($GM->get_record('end') - $GM->get_record('year')).'&times;'.$GM->get_record('duration').'</small>');
			$TPL->set('season', $LNG->get('GENERAL','season'));
			$TPL->set('citizens', number_format($GM->get_record('citizens'), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('population', $LNG->get('GENERAL','population'));
			$TPL->set('continue', $LNG->get('GENERAL','continue'));
			// Buildings
			$TPL->set('buildings', $buildings);
			$buildings_land_compatibility = '';
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				if($i < 10) {
					$buildings_land_compatibility = $buildings_land_compatibility.$CFG->get('GAME_BUILDINGS_LAND_COMBATIBILITY','8000'.$i).',';
				} else {
					$buildings_land_compatibility = $buildings_land_compatibility.$CFG->get('GAME_BUILDINGS_LAND_COMBATIBILITY','800'.$i).',';
				}
			}
			$buildings_land_compatibility = substr($buildings_land_compatibility, 0, -1);
			$TPL->set('buildings_land_compatibility', $buildings_land_compatibility);
			$buildings_titles = '';
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				if($i < 10) {
					$buildings_titles = $buildings_titles."'".$LNG->get('BUILDINGS','8000'.$i)."',";
				} else {
					$buildings_titles = $buildings_titles."'".$LNG->get('BUILDINGS','800'.$i)."',";
				}
			}
			$buildings_titles = substr($buildings_titles, 0, -1);
			$TPL->set('buildings_titles', $buildings_titles);
			$TPL->set('buildings_cost', $GM->get_record('buildings_cost'));
			$TPL->set('plot_building', $LNG->get('GENERAL','building'));
			$TPL->set('plot_cost', $LNG->get('GENERAL','cost'));
			$TPL->set('build', $LNG->get('GENERAL','build'));
			$TPL->set('demolish', $LNG->get('GENERAL','demolish'));
			$TPL->set('demolish_cost', $GM->get_record('demolish_cost'));
			// Statistics
			$statistics = $GM->statistics();
			$TPL->set('statistics_content', $LNG->get('GENERAL','statistics'));
				// Statistics - Score summary
				$TPL->set('statistics_score', $LNG->get('GENERAL','score'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', '');
				$TPL_GRAPH->set('start', 16*$statistics['historic']['score']/max(($statistics['historic']['score']+0.01), ($statistics['curent']['score']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['score']/max(($statistics['historic']['score']+0.01), ($statistics['curent']['score']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['score'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['score'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['score'] - $statistics['historic']['score'])/($statistics['historic']['score'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL->set('statistics_score_summary', $TPL_GRAPH->get());
				// Statistics - Money summary
				$TPL->set('statistics_money', $LNG->get('GENERAL','money'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','money_cash'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['money']['cash']/max(($statistics['historic']['money']['cash']+0.01), ($statistics['curent']['money']['cash']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['money']['cash']/max(($statistics['historic']['money']['cash']+0.01), ($statistics['curent']['money']['cash']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['money']['cash'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['money']['cash'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['money']['cash'] - $statistics['historic']['money']['cash'])/($statistics['historic']['money']['cash'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_cash = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','tax'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['tax']/max(($statistics['historic']['tax']+0.01), ($statistics['curent']['tax']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['tax']/max(($statistics['historic']['tax']+0.01), ($statistics['curent']['tax']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['tax'] - $statistics['historic']['tax'])/($statistics['historic']['tax'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_tax = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_build'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_build']/max(($statistics['historic']['stat_build']+0.01), ($statistics['curent']['stat_build']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_build']/max(($statistics['historic']['stat_build']+0.01), ($statistics['curent']['stat_build']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_build'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_build'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_build'] - $statistics['historic']['stat_build'])/($statistics['historic']['stat_build'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_build = $TPL_GRAPH->get();				
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_demolish'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_demolish']/max(($statistics['historic']['stat_demolish']+0.01), ($statistics['curent']['stat_demolish']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_demolish']/max(($statistics['historic']['stat_demolish']+0.01), ($statistics['curent']['stat_demolish']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_demolish'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_demolish'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_demolish'] - $statistics['historic']['stat_demolish'])/($statistics['historic']['stat_demolish'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_demolish = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_buy'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_buy']/max(($statistics['historic']['stat_buy']+0.01), ($statistics['curent']['stat_buy']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_buy']/max(($statistics['historic']['stat_buy']+0.01), ($statistics['curent']['stat_buy']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_buy'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_buy'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_buy'] - $statistics['historic']['stat_buy'])/($statistics['historic']['stat_buy'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_buy = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_sell'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_sell']/max(($statistics['historic']['stat_sell']+0.01), ($statistics['curent']['stat_sell']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_sell']/max(($statistics['historic']['stat_sell']+0.01), ($statistics['curent']['stat_sell']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_sell'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_sell'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_sell'] - $statistics['historic']['stat_sell'])/($statistics['historic']['stat_sell'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_sell = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_research'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_research']/max(($statistics['historic']['stat_research']+0.01), ($statistics['curent']['stat_research']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_research']/max(($statistics['historic']['stat_research']+0.01), ($statistics['curent']['stat_research']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_research'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_research'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_research'] - $statistics['historic']['stat_research'])/($statistics['historic']['stat_research'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_research = $TPL_GRAPH->get();
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','stat_tax'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['stat_tax']/max(($statistics['historic']['stat_tax']+0.01), ($statistics['curent']['stat_tax']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['stat_tax']/max(($statistics['historic']['stat_tax']+0.01), ($statistics['curent']['stat_tax']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['stat_tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['stat_tax'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['stat_tax'] - $statistics['historic']['stat_tax'])/($statistics['historic']['stat_tax'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$money_stat_tax = $TPL_GRAPH->get();
				$TPL->set('statistics_money_summary', $money_cash.$money_tax.$money_stat_build.$money_stat_demolish.$money_stat_buy.$money_stat_sell.$money_stat_research.$money_stat_tax);				
				// Statistics - Citizens summary
				$TPL->set('statistics_citizens', $LNG->get('GENERAL','citizens'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','citizens'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['citizens']/max(($statistics['historic']['citizens']+0.01), ($statistics['curent']['citizens']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['citizens']/max(($statistics['historic']['citizens']+0.01), ($statistics['curent']['citizens']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['citizens'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['citizens'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['citizens'] - $statistics['historic']['citizens'])/($statistics['historic']['citizens'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL->set('statistics_citizens_summary', $TPL_GRAPH->get());
				// Experience
				$TPL->set('expertise_type', $LNG->get('GENERAL','expertise'));
				$TPL->set('cost', $LNG->get('GENERAL','cost'));
				$TPL->set('level', $LNG->get('GENERAL','level'));
				$TPL->set('expertise', $GM->get_record('expertise'));
				$TPL->set('research', $LNG->get('GENERAL','research'));
				// Expertise
				$citizen_experience = explode(',', $GM->get_record('expertise'));
				$experience = "";
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					if ($citizen_experience[$i - 1] > 0) {
						if($i > 10) {
							$experience = $experience."<img src='810".$i.".png' alt='".$LNG->get('ITEMS','810'.$i)."' title='".$LNG->get('ITEMS','810'.$i)."'> ".$LNG->get('ITEMS','810'.$i)." &times; ".$citizen_experience[$i - 1]."<br>";
						} else {
							$experience = $experience."<img src='8100".$i.".png' alt='".$LNG->get('ITEMS','8100'.$i)."' title='".$LNG->get('ITEMS','8100'.$i)."'> ".$LNG->get('ITEMS','8100'.$i)." &times; ".$citizen_experience[$i - 1]."<br>";
						}
					}
				}
				$TPL->set('statistics_experience', $LNG->get('GENERAL','expertise'));
				$TPL->set('statistics_citizen_experience_summary', $experience);
				// Statistics - Buildings summary
				$TPL->set('statistics_buildings', $LNG->get('GENERAL','buildings'));
				$TPL_GRAPH = new template;
				$TPL_GRAPH->open('game.graph.tpl');
				$TPL_GRAPH->set('image', $LNG->get('GENERAL','demolish_cost'));
				$TPL_GRAPH->set('start', 16*$statistics['historic']['demolish_cost']/max(($statistics['historic']['demolish_cost']+0.01), ($statistics['curent']['demolish_cost']+0.01)));
				$TPL_GRAPH->set('end', 16*$statistics['curent']['demolish_cost']/max(($statistics['historic']['demolish_cost']+0.01), ($statistics['curent']['demolish_cost']+0.01)));
				$TPL_GRAPH->set('historic', number_format($statistics['historic']['demolish_cost'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('current', number_format($statistics['curent']['demolish_cost'], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['demolish_cost'] - $statistics['historic']['demolish_cost'])/($statistics['historic']['demolish_cost'] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$buildings_demolish_cost = $TPL_GRAPH->get();
				$buildings_analytical = '';
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					$TPL_GRAPH = new template;
					$TPL_GRAPH->open('game.graph.tpl');
					if($i < 10) {
						$TPL_GRAPH->set('image', "<img src='8000".$i.".png' alt='".$LNG->get('BUILDINGS','8000'.$i)."' title='".$LNG->get('BUILDINGS','8000'.$i)."'>&nbsp;".$LNG->get('BUILDINGS','8000'.$i));
					} else {
						$TPL_GRAPH->set('image', "<img src='800".$i.".png' alt='".$LNG->get('BUILDINGS','800'.$i)."' title='".$LNG->get('BUILDINGS','800'.$i)."'>&nbsp;".$LNG->get('BUILDINGS','800'.$i));
					}
					$TPL_GRAPH->set('start', 16*$statistics['historic']['buildings_cost'][$i]/max(($statistics['historic']['buildings_cost'][$i]+0.01), ($statistics['curent']['buildings_cost'][$i]+0.01)));
					$TPL_GRAPH->set('end', 16*$statistics['curent']['buildings_cost'][$i]/max(($statistics['historic']['buildings_cost'][$i]+0.01), ($statistics['curent']['buildings_cost'][$i]+0.01)));
					$TPL_GRAPH->set('historic', number_format($statistics['historic']['buildings_cost'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('current', number_format($statistics['curent']['buildings_cost'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['buildings_cost'][$i] - $statistics['historic']['buildings_cost'][$i])/($statistics['historic']['buildings_cost'][$i] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$buildings_analytical = $buildings_analytical.$TPL_GRAPH->get();
				}
				$TPL->set('statistics_buildings_summary', $buildings_demolish_cost.$buildings_analytical);
				// Statistics - Prices summary
				$TPL->set('statistics_prices', $LNG->get('GENERAL','prices'));
				$prices_analytical = '';
				for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
					$TPL_GRAPH = new template;
					$TPL_GRAPH->open('game.graph.tpl');
					if($i < 10) {
						$TPL_GRAPH->set('image', "<img src='8100".$i.".png' alt='".$LNG->get('ITEMS','8100'.$i)."' title='".$LNG->get('ITEMS','8100'.$i)."'>&nbsp;".$LNG->get('ITEMS','8100'.$i));
					} else {
						$TPL_GRAPH->set('image', "<img src='810".$i.".png' alt='".$LNG->get('ITEMS','810'.$i)."' title='".$LNG->get('ITEMS','810'.$i)."'>&nbsp;".$LNG->get('ITEMS','810'.$i));
					}
					$TPL_GRAPH->set('start', 16*$statistics['historic']['prices'][$i]/max(($statistics['historic']['prices'][$i]+0.01), ($statistics['curent']['prices'][$i]+0.01)));
					$TPL_GRAPH->set('end', 16*$statistics['curent']['prices'][$i]/max(($statistics['historic']['prices'][$i]+0.01), ($statistics['curent']['prices'][$i]+0.01)));
					$TPL_GRAPH->set('historic', number_format($statistics['historic']['prices'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('current', number_format($statistics['curent']['prices'][$i], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$TPL_GRAPH->set('percent', number_format(100*($statistics['curent']['prices'][$i] - $statistics['historic']['prices'][$i])/($statistics['historic']['prices'][$i] +0.01), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
					$prices_analytical = $prices_analytical.$TPL_GRAPH->get();
				}
				$TPL->set('statistics_prices_summary', $prices_analytical);
			// Warehouse & prices		
			$warehouse_items = explode(',', $GM->get_record('warehouse'));
			$warehouse_prices = explode(',', $GM->get_record('prices'));
			$TPL->set('warehouse_content', $LNG->get('GENERAL','warehouse'));
			$TPL->set('warehouse', $GM->get_record('warehouse'));
			$TPL->set('prices', $GM->get_record('prices'));
			$TPL->set('warehouse_prices', $LNG->get('GENERAL','price'));
			$TPL->set('warehouse_quantity', $LNG->get('GENERAL','quantity'));
			$TPL->set('warehouse_total', $LNG->get('GENERAL','total'));
			$TPL->set('warehouse_total_value', $LNG->get('GENERAL','total_value'));
			$TPL->set('warehouse_commodities', $LNG->get('GENERAL','commodities'));
			$warehouse_status = "";
			$total_value = 0;
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {			
				$TPL_WAREHOUSE_ITEM = new template;
				$TPL_WAREHOUSE_ITEM->open('game.warehouse.tpl');
				$TPL_WAREHOUSE_ITEM->set('product_item', $i);
				if($i < 10) {
					$TPL_WAREHOUSE_ITEM->set('product_item_name', $LNG->get('ITEMS','8100'.$i));
					$TPL_WAREHOUSE_ITEM->set('product_item_image', '8100'.$i.'.png');
				} else {
					$TPL_WAREHOUSE_ITEM->set('product_item_name', $LNG->get('ITEMS','810'.$i));
					$TPL_WAREHOUSE_ITEM->set('product_item_image', '810'.$i.'.png');
				}
				$TPL_WAREHOUSE_ITEM->set('product_item_quantity', number_format($warehouse_items[$i-1], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_WAREHOUSE_ITEM->set('product_item_price', number_format($warehouse_prices[$i-1], 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_WAREHOUSE_ITEM->set('product_item_tax', number_format($warehouse_prices[$i-1]*($GM->get_record('tax')/100), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$TPL_WAREHOUSE_ITEM->set('product_item_value', number_format(($warehouse_items[$i-1]*$warehouse_prices[$i-1]*(1 - ($GM->get_record('tax')/100))), 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
				$total_value = $total_value + floor(($warehouse_items[$i-1]*$warehouse_prices[$i-1])*(1 - ($GM->get_record('tax')/100)));
				$TPL_WAREHOUSE_ITEM->set('product_item_sell_button', "<button class='button_short' onclick='warehouse_sell(\"".$i."\")' title='".$LNG->get('GENERAL','sell')."'><img src='70002.png'></button>");
				$TPL_WAREHOUSE_ITEM->set('product_item_buy_button', "<button class='button_short' onclick='warehouse_buy(\"".$i."\")' title='".$LNG->get('GENERAL','buy')."'><img src='70003.png'></button>");
				$warehouse_status = $warehouse_status.$TPL_WAREHOUSE_ITEM->get();
			}
			$TPL->set('total_value', number_format($total_value, 0, $LNG->get('CONFIG','charset_thousand'), $LNG->get('CONFIG','charset_decimal')));
			$TPL->set('warehouse_status', $warehouse_status);
			// Commodities
			$products_titles = '';
			for ($i = 1; $i <= $CFG->get('GAME','PRODUCTS'); $i++) {
				if($i < 10) {
					$products_titles = $products_titles."'".$LNG->get('ITEMS','8100'.$i)."',";
				} else {
					$products_titles = $products_titles."'".$LNG->get('ITEMS','810'.$i)."',";
				}
			}
			$TPL->set('products_titles', $products_titles);
			$TPL->set('plot_production', $LNG->get('GENERAL','production'));
			// Plot menus
			$TPL->set('plot_content', $LNG->get('GENERAL','plot'));
			$TPL->set('available', $LNG->get('GENERAL','available'));
			$TPL->set('occupied', $LNG->get('GENERAL','occupied'));
			echo $TPL->get();
		}
	} else {
		echo '<script>window.location = "login.php";</script>';
	}
?>
