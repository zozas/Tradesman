<!DOCTYPE html>
<html>
	<head>
		<title>
			[@application_title]
		</title>
		<link rel='icon' type='image/png' href='90002.png'>
		<link rel='stylesheet' type='text/css' href='game.css'>
		[@metatags]
		<script>
			function number_format(number, decimals, dec_point, thousands_sep) {
				var n = !isFinite(+number) ? 0 : +number, prec = !isFinite(+decimals) ? 0 : Math.abs(decimals), sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, dec = (typeof dec_point === 'undefined') ? '.' : dec_point, toFixedFix = function (n, prec) {
					var k = Math.pow(10, prec);
					return Math.round(n * k) / k;
				}, s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
				if (s[0].length > 3) {
					s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
				}
				if ((s[1] || '').length < prec) {
					s[1] = s[1] || '';
					s[1] += new Array(prec - s[1].length + 1).join('0');
				}
				return s.join(dec);
			}
		</script>
	</head>
	<body>
		<center>
			<br>
			<div id='plot' width='320' height='320'>
				<br>
				<img src='90001.png' class='logo_big'>
				<br>				
				<big>
					<b>
						[@application_title]
					</b>
				</big>
				<br>
				<small>
					<i>
						[@application_subtitle]
					</i>
				</small>
				<br>
				<br>
				<div class='menu_item_information'>
					<b>
						[@configure_new]
					</b>
					<br>
					<br>
					<form name='login' method='post' action='index.php'>
						<input type='hidden' name='action' value='create'>
						<table>
							<tr>
								<td align='right'>
									[@username]
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='text' name='login_username' value='' required='required' maxlength='10'>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@userpassword]
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='password' name='login_password' value='' required='required' maxlength='10'>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@useremail]
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='email' name='login_email' required='required' value=''>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@city]
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='text' name='login_city' value='' required='required' maxlength='10'>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@reign_range]
									<br>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='range' min='0' max='[@duration_max]' value='[@duration_default]' class='slider' id='login_end' name='login_end'>
									<br>
									<small>
										<span id='login_end_range'></span>
										[@days]
									</small>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@duration_round]
									<br>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='range' min='1' max='[@duration_round_max]' value='[@duration_round_default]' class='slider' id='login_duration' name='login_duration'>
									<br>
									<small>
										<span id='login_duration_range'></span>
										[@minutes]
									</small>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@money_range]
									<br>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='range' min='0' max='[@money_max]' value='[@money_default]' class='slider' id='login_money' name='login_money'>
									<br>
									<small>
										<span id='login_money_range'></span>
										[@money]
									</small>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@citizens_range]
									<br>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='range' min='0' max='[@citizens_max]' value='[@citizens_default]' class='slider' id='login_citizens' name='login_citizens'>
									<br>
									<small>
										<span id='login_citizens_range'></span>
										[@citizens]
									</small>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@landmass_range]
									<br>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='range' min='0' max='[@landmass_max]' value='[@landmass_default]' class='slider' id='login_landmass' name='login_landmass'>
									<br>
									<small>
										<span id='login_landmass_range'></span>
										[@landmass]
									</small>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@mountain_range]
									<br>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='range' min='0' max='[@mountains_max]' value='[@mountains_default]' class='slider' id='login_mountain' name='login_mountain'>
									<br>
									<small>
										<span id='login_mountain_range'></span>
										[@mountain]
									</small>
								</td>
							</tr>
							<tr>
								<td align='right'>
									[@difficulty_range]
									<br>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='range' min='0' max='[@difficulty_max]' value='[@difficulty_default]' class='slider' id='login_difficulty' name='login_difficulty'>
									<br>
									<small>
										<span id='login_difficulty_range'></span>
										[@difficulty]
									</small>
								</td>
							</tr>
							<tr>
								<td colspan='3'>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan='3' align='center'>
									<input type='submit' class='button_short' value='&nbsp;[@create]&nbsp;'>
								</td>
							</tr>
						</table>
					</form>
					<table>
						<tr>
							<td colspan='2' align='center'>
								<form name='game' method='post' action='index.php'>
									<input type='submit' class='button_short' value='&nbsp;[@return]&nbsp;'>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<script>
				var slider_login_end = document.getElementById('login_end');
				var output_login_end = document.getElementById('login_end_range');
				output_login_end.innerHTML = slider_login_end.value;
				slider_login_end.oninput = function() {
					if (this.value == 0) {
						output_login_end.innerHTML = '[@reign_range_info]';
					} else {
						output_login_end.innerHTML = number_format(this.value, 0, '.', ',');
					}
				}
				var slider_login_money = document.getElementById('login_money');
				var output_login_money = document.getElementById('login_money_range');
				output_login_money.innerHTML = slider_login_money.value;
				slider_login_money.oninput = function() {
					output_login_money.innerHTML = number_format(this.value, 0, '.', ',');
				}
				var slider_login_citizens = document.getElementById('login_citizens');
				var output_login_citizens = document.getElementById('login_citizens_range');
				output_login_citizens.innerHTML = slider_login_citizens.value;
				slider_login_citizens.oninput = function() {
					output_login_citizens.innerHTML = number_format(this.value, 0, '.', ',');
				}
				var slider_login_landmass = document.getElementById('login_landmass');
				var output_login_landmass = document.getElementById('login_landmass_range');
				output_login_landmass.innerHTML = slider_login_landmass.value;
				slider_login_landmass.oninput = function() {
					output_login_landmass.innerHTML = number_format(this.value, 0, '.', ',');
				}
				var slider_login_difficulty = document.getElementById('login_difficulty');
				var output_login_difficulty = document.getElementById('login_difficulty_range');
				output_login_difficulty.innerHTML = slider_login_difficulty.value;
				slider_login_difficulty.oninput = function() {
					output_login_difficulty.innerHTML = number_format(this.value, 0, '.', ',');
				}
				var slider_login_mountain = document.getElementById('login_mountain');
				var output_login_mountain = document.getElementById('login_mountain_range');
				output_login_mountain.innerHTML = slider_login_mountain.value;
				slider_login_mountain.oninput = function() {
					output_login_mountain.innerHTML = number_format(this.value, 0, '.', ',');
				}
				var slider_login_duration = document.getElementById('login_duration');
				var output_login_duration = document.getElementById('login_duration_range');
				output_login_duration.innerHTML = slider_login_duration.value;
				slider_login_duration.oninput = function() {
					output_login_duration.innerHTML = number_format(this.value, 0, '.', ',');
				}
			</script>
			<br>
			<br>
			[@application_copyright]
		</center>
	</body>
</html>
