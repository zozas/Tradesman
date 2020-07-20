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
					<table>
						<tr>
							<td align='center'>
								<b>
									[@new_game]
								</b>
							</td>
							<td align='left'>
								<hr>
							</td>
						</tr>
						<tr>
							<td align='center'>
								<form name='game' method='post' action='index.php'>
									<input type='hidden' name='action' value='configure_new'>
									<input type='submit' class='button_short' value='&nbsp;[@configure_new]&nbsp;'>
								</form>
							</td>
							<td align='left'>
								[@configure_new_info]
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td align='center'>
								<b>
									[@past_game]
								</b>
							</td>
							<td align='left'>
								<hr>
							</td>
						</tr>
						<tr>
							<td align='center'>
								<form name='game' method='post' action='login.php'>
									<input type='submit' class='button_short' value='&nbsp;[@load]&nbsp;'>
								</form>
							</td>
							<td align='left'>
								[@load_info]
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td align='center'>
								<b>
									[@other]
								</b>
							</td>
							<td align='left'>
								<hr>
							</td>
						</tr>
						<tr>
							<td align='center'>
								<form name='game' method='post' action='index.php'>
									<input type='hidden' name='action' value='highscores'>
									<input type='submit' class='button_short' value='&nbsp;[@highscores]&nbsp;'>
								</form>
							</td>
							<td align='left'>
								[@highscores_info]
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
