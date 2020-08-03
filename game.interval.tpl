<!DOCTYPE html>
<html>
	<head>
		<title>
			[@application_title]
		</title>
		<link rel='icon' type='image/png' href='90002.png'>
		<link rel='stylesheet' type='text/css' href='game.css'>
		<script type='text/javascript'>
			function view_next() {
				window.location = "game.php";
			};
			function view_end() {
				window.location = "game.php?action=endgame";
			};
		</script>
		[@metatags]
	</head>
	<body>
		<center>
			<br>
			<div class='menu_information'>
				<div class='menu_item_information'>
					<img src='90001.png' class='logo_small'>
					[@city]
				</div>
				<button class='button_short' onclick='[@view]();' title='[@continue]'><img src='70010.png' title='[@continue]'></button>
			</div>
			<br>
			<div class='menu_information'>
				<div class='menu_item_information'>
					<img src='70004.png' title='[@treasury]'>
					[@money_amount]&nbsp;<small>+[@warehouse_amount]</small>&nbsp;
				</div>
				<div class='menu_item_information'>
					<img src='70006.png' title='[@season]'>
					[@year]
				</div>
				<div class='menu_item_information'>
					<img src='70007.png' title='[@population]'>
					[@citizens]
				</div>
			</div>
			<div id='summary' width='320' height='320'>
				<br>
				<div class='menu_item_information'>
					<b>
						[@summary_content]
					</b>
					<br>
					<small>
						[@city]
						<br>
						<br>
						[@season_days]
						<br>
						[@season_remain]
					</small>
					<br>
					<br>
					<table>
						<thead>
							<tr>
								<td colspan='3'>
									<b>
										[@population]
									</b>
									<hr>
									[@citizen_summary]
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan='3'>
									<b>
										[@food]
									</b>
									<hr>
									[@food_summary]
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan='3'>
									<b>
										[@production]
									</b>
									<hr>
									[@production_summary]
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan='3'>
									<b>
										[@real_estate]
									</b>
									<hr>
									[@real_estate_summary]
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan='3'>
									<b>
										[@score]
									</b>
									<hr>
									[@score_result]
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<div id='warehouse' width='320' height='320'>
				<br>
				<br>
				<div class='menu_item_information'>
					<b>
						[@warehouse_content]
					</b>
					<br>
					<br>
					<table>
						<thead>
							<tr>
								<td>
									[@warehouse_commodities]
								</td>
								<td>
									[@warehouse_quantity]
								</td>
								<td>
									[@warehouse_prices]
								</td>
								<td>
									[@warehouse_total]
								</td>
								<td>
									[@warehouse_balance]
								</td>
								<td>
									[@warehouse_trend]
								</td>
							</tr>
						</thead>
						[@warehouse_status]
						<tfoot>
							<tr>
								<td colspan='3' align='right'>
									<b>
										[@warehouse_total_value]
									</b>
								</td>
								<td align='center'>
									<b>
										<span id='money_total_amount'>
											[@total_value]
										</span>
									</b>
								</td>
								<td colspan='2'>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div id='statistics' width='320' height='320'>
				<br>
				<br>
				<div class='menu_item_information'>
					<b>
						[@statistics_content]
					</b>
					<br>
					<br>
					<table>
						<thead>
							<tr>
								<td>
									<b>
										[@statistics_score]
									</b>
									<hr>
									<table>
										[@statistics_score_summary]
									</table>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<b>
										[@statistics_money]
									</b>
									<hr>
									<table>
										[@statistics_money_summary]
									</table>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<b>
										[@statistics_citizens]
									</b>
									<hr>
									<table>
										[@statistics_citizens_summary]
									<table>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<b>
										[@statistics_experience]
									</b>
									<hr>
									[@statistics_citizen_experience_summary]
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<b>
										[@statistics_buildings]
									</b>
									<hr>
									<table>
										[@statistics_buildings_summary]
									</table>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<b>
										[@statistics_prices]
									</b>
									<hr>
									<table>
										[@statistics_prices_summary]
									</table>
								</td>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</center>
	</body>
</html>
