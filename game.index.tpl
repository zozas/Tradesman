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
								<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_blank'>
									<input type='hidden' name='cmd' value='_s-xclick' />
									<input type='hidden' name='hosted_button_id' value='LUHFWKT7D5E9A' />
									<input type='submit' class='button_short' value='&nbsp;[@donate]&nbsp;'>
								</form>
							</td>
							<td align='left'>
								[@donation_info]
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
			[@application_copyright]
		</center>
	</body>
</html>
