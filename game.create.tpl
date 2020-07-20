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
									<input type='range' min='0' max='30000' value='365' class='slider' id='login_end' name='login_end'>
									<br>
									<small>
										<span id='login_end_range'></span>
										[@days]
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
									<input type='range' min='0' max='10000' value='1000' class='slider' id='login_money' name='login_money'>
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
									<input type='range' min='0' max='100' value='3' class='slider' id='login_citizens' name='login_citizens'>
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
									<input type='range' min='0' max='100' value='75' class='slider' id='login_landmass' name='login_landmass'>
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
									<input type='range' min='0' max='100' value='75' class='slider' id='login_mountain' name='login_mountain'>
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
									<input type='range' min='0' max='100' value='50' class='slider' id='login_difficulty' name='login_difficulty'>
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
			</script>
			<br>
			<br>
			<small>
				[@application_product]
				<br>
				version
				[@application_version]
				<br>
				<br>
				Copyright &copy; [@application_copyright] [@application_author]. Released under [@application_deployment].
				<br>
				<table class='copyright'>
					<tr>
						<td>
							<center>
								<img src='90009.png' title='MIT Licence'>
							</center>
							<br>
							Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
							<br>
							<br>
							The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
							<br>
							<br>
							The Software is provided “as is”, without warranty of any kind, express or implied, including but not limited to the warranties of merchantability, fitness for a particular purpose and noninfringement. In no event shall the authors or copyright holders be liable for any claim, damages or other liability, whether in an action of contract, tort or otherwise, arising from, out of or in connection with the software or the use or other dealings in the Software.
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align='center'>
							Developed with &#x2764; on
						</td>
					</tr>
					<tr>
						<td align='center'>
							<img src='90004.png' title='HTML 5'>&nbsp;
							<img src='90005.png' title='JavaScript'>&nbsp;
							<img src='90006.png' title='PHP'>&nbsp;
							<img src='90007.png' title='MySQL'>&nbsp;
							<img src='90008.png' title='CSS 3'>
						</td>
					</tr>
					<tr>
						<td align='center'>
							Source code and contact information at
						</td>
					</tr>
					<tr>
						<td align='center'>
							<a href='https://github.com/zozas'><img src='90010.png' title='Github'></a>
						</td>
					</tr>
				</table>
			</small>
		</center>
	</body>
</html>
