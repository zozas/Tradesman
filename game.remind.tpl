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
						[@remind]
					</b>
					<br>
					<br>
					<form name='login' method='post' action='login.php'>
						<input type='hidden' name='action' value='reset'>
						<table>
							<tr>
								<td align='right'>
									[@email]
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<input type='text' name='login_email' value=''>
								</td>
							</tr>
							<tr>
								<td colspan='3'>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan='3' align='center'>
									<input type='submit' class='button_short' value='&nbsp;[@send]&nbsp;'>
								</td>
							</tr>
						</table>
					</form>
					<table>
						<tr>
							<td align='center'>
								<form name='game' method='post' action='login.php'>
									<input type='submit' class='button_short' value='&nbsp;[@return]&nbsp;'>
								</form>
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
