<!DOCTYPE html>
<html>
	<head>
		<title>
			[@application_title]
		</title>
		<link rel='icon' type='image/png' href='90002.png'>
		<link rel='stylesheet' type='text/css' href='game.css'>
		[@metatags]
	</head>
	<body>
		<center>
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
						[@city]
					</b>
					<br>
					<small>
						[@season_days]
					</small>
					<br>
					<br>
					<table>
						<tr>
							<td>
								[@season]
							</td>
							<td>
								:
							</td>
							<td>
								[@year]
							</td>
						</tr>
						<tr>
							<td>
								[@treasury]
							</td>
							<td>
								:
							</td>
							<td>
								[@money_amount]
							</td>
						</tr>
						<tr>
							<td>
								[@warehouse]
							</td>
							<td>
								:
							</td>
							<td>
								[@warehouse_amount]
							</td>
						</tr>
						<tr>
							<td>
								[@population]
							</td>
							<td>
								:
							</td>
							<td>
								[@citizens]
							</td>
						</tr>
						<tr>
							<td>
								[@buildings]
							</td>
							<td>
								:
							</td>
							<td>
								[@total_buildings]
							</td>
						</tr>
						<tr>
							<td colspan='3'>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan='3'>
								[@production]
							</td>
						</tr>	
						<tr>
							<td colspan='3'>
								[@products]
							</td>
						</tr>
						<tr>
							<td colspan='3'>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan='3' align='center'>
								[@score]
								<h1>
									[@score_result]
								</h1>
							</td>
						</tr>
						<tr>
							<td align='center' colspan='3'>
								<form name='game' method='post' action='game.php'>
									<input type='submit' class='button_short' value='&nbsp;[@continue]&nbsp;'>
								</form>
								<br>
								<form name='game' method='post' action='login.php'>
									<input type='hidden' name='action' value='delete'>
									<input type='hidden' name='id' value='[@id]'>
									<input type='submit' class='button_short' value='&nbsp;[@delete]&nbsp;'>
								</form>
								<br>
								<form name='game' method='post' action='login.php'>
									<input type='hidden' name='action' value='logout'>
									<input type='submit' class='button_short' value='&nbsp;[@logoff]&nbsp;'>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div>
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
