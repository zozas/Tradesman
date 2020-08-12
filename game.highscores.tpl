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
							<td align='center' colspan='9'>
								<b>
									[@highscores]
								</b>
							</td>
						</tr>
						<tr>
							<td align='center' colspan='9'>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td align='left'>
								<b>
									User
								</b>
							</td>
							<td align='left'>
								<b>
									City
								</b>
							</td>
							<td align='left'>
								<b>
									Day
								</b>
							</td>
							<td align='left'>
								<b>
									Duration
								</b>
							</td>
							<td align='left'>
								<b>
									Money
								</b>
							</td>
							<td align='left'>
								<b>
									Citizens
								</b>
							</td>
							<td align='left'>
								<b>
									Buildings
								</b>
							</td>
							<td align='left'>
								<b>
									Difficulty
								</b>
							</td>
							<td align='left'>
								<b>
									Score
								</b>
							</td>
						</tr>
						[@highscore_entries]
						<tr>
							<td align='center' colspan='9'>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td align='center' colspan='9'>
								<div id='worldmap_marker'>
									<canvas id='worldmap' width='320' height='172' style='border:none;'>
								</div>
								<script>
									var image_map = new Image();
									image_map.src = '70015.png';
									image_map.onload = function () {
										document.getElementById('worldmap').getContext('2d').drawImage(image_map, 0, 0);
										[@highscore_map_entries]
									};
									
								</script>
							</td>
						</tr>
						<tr>
							<td align='center' colspan='9'>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td align='center' colspan='9'>
								<form name='game' method='post' action='index.php'>
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
