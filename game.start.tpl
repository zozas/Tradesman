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
							<td>
								[@tax]
							</td>
							<td>
								:
							</td>
							<td>
								[@total_tax]
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
			[@application_copyright]
		</center>
	</body>
</html>
