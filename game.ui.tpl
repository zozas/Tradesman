<!DOCTYPE html>
<html>
	<head>
		<title>
			[@application_title]
		</title>
		<link rel='icon' type='image/png' href='90002.png'>
		<script type='text/javascript'>
			var canvas = null;
			var map = [[@map]];
			var buildings = [[@buildings]];
			var buildings_compatibility = [[@buildings_land_compatibility]];
			var buildings_titles = [[@buildings_titles]];
			var buildings_cost = [[@buildings_cost]];
			var warehouse = [[@warehouse]];
			var prices = [[@prices]];
			var money = [@money];
			var tax = [@tax];
			var demolish_cost = [@demolish_cost];
			var products_titles = [[@products_titles]];
			var products_total = [@products_total];
			var number_format_decimal = '[@number_format_decimal]';
			var number_format_thousand = '[@number_format_thousand]';
			var tileW = 64;
			var tileH = 64;
			var mapW = 10;
			var mapH = 10;
			document.addEventListener('DOMContentLoaded', init, false);
			function init() {
				var canvas = document.getElementById('map');
				canvas.addEventListener('mousedown', getPosition, false);
				var warehouse_content = document.getElementById('warehouse');
				warehouse_content.style.display = 'none';
				var plot_content = document.getElementById('plot');
				plot_content.style.display = 'none';
				var statistics_content = document.getElementById('statistics');
				statistics_content.style.display = 'none';
				window.addEventListener('resize', resize, false);
				var total_treasury = 0;
				var i;
				for (i = 0; i < warehouse.length; i++) {
					total_treasury = total_treasury + warehouse[i]*prices[i];
				} 
				document.getElementById('money_amount').innerHTML  = number_format(money, 0, number_format_decimal, number_format_thousand) + ' <small>+' + number_format(total_treasury, 0, number_format_decimal, number_format_thousand) + '</small>&nbsp;';
			};
			window.onload = function() {
				canvas = document.getElementById('map').getContext('2d');
				canvas.width = tileW * mapW;
				canvas.height = tileH * mapH;
				requestAnimationFrame(drawGame);
				canvas.font = 'bold 10pt sans-serif';
				resize();
			};
			function resize() {
				var canvas = document.getElementById('map');
				var canvasRatio = canvas.height / canvas.width;
				var windowRatio = window.innerHeight / window.innerWidth;
				var width;
				var height;
				var max_width = mapW * tileW;
				var max_height=mapH * tileH;
				if (windowRatio < canvasRatio) {
					height = window.innerHeight;
					width = height / canvasRatio;
				} else {
					width = window.innerWidth;
					height = width * canvasRatio;
				}
				if (width > max_width) width = max_width;
				if (height > max_height) height = max_height;
				canvas.style.width = width + 'px';
				canvas.style.height = height + 'px';
			};
			function view_warehouse() {
				var plot_content = document.getElementById('plot');
				plot_content.style.display = 'none';
				var statistics_content = document.getElementById('statistics');
				statistics_content.style.display = 'none';
				var warehouse_content = document.getElementById('warehouse');
				if (warehouse_content.style.display === 'none') {
					warehouse_content.style.display = 'block';
					warehouse_content.scrollIntoView(true);
				} else {
					warehouse_content.style.display = 'none';
					plot_content.scrollIntoView(true);
				}
			};
			function view_statistics() {
				var plot_content = document.getElementById('plot');
				plot_content.style.display = 'none';
				var warehouse_content = document.getElementById('warehouse');
				warehouse_content.style.display = 'none';
				var statistics_content = document.getElementById('statistics');
				if (statistics_content.style.display === 'none') {
					statistics_content.style.display = 'block';
					statistics_content.scrollIntoView(true);
				} else {
					statistics_content.style.display = 'none';
					plot_content.scrollIntoView(true);
				}
			};
			function view_next() {
				window.location = "game.php?action=interval";
			};
			function view_manual() {
				window.open("index.php?action=manual", "_blank");
			}
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
			function engine_ajax(action, action_parameters) {
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {						
						if (this.responseText == 'logoff') {
							logoff();
						}
					}
				};
				xhttp.open("GET", "engine.ajax.php?user_name=[@user_name]&user_password=[@user_password]&action=" + action + "&action_parameters=" + action_parameters, true);
				xhttp.send();
			};
			function logoff() {
				window.location = "login.php?action=logout";
			}
			function warehouse_buy(product_item) {
				if(money > prices[product_item-1]) {
					money = Math.floor(money - prices[product_item-1]*((100 - tax)/100));
					engine_ajax('buy', (product_item-1));
					document.getElementById('money_amount').innerHTML  = number_format(money, 0, number_format_decimal, number_format_thousand);
					warehouse[product_item-1] = warehouse[product_item-1] + 1;
					document.getElementById('p' + product_item + '_quantity').innerHTML  = number_format(warehouse[product_item-1], 0, number_format_decimal, number_format_thousand);
					document.getElementById('p' + product_item + '_value').innerHTML  = number_format((warehouse[product_item-1]*prices[product_item-1]), 0, number_format_decimal, number_format_thousand);
					var total_treasury = 0;
					var i;
					for (i = 0; i < warehouse.length; i++) {
						total_treasury = total_treasury + Math.floor(warehouse[i]*prices[i]*((100 - tax)/100));
					} 
					document.getElementById('money_total_amount').innerHTML  = number_format(total_treasury, 0, number_format_decimal, number_format_thousand);
					document.getElementById('money_amount').innerHTML  = number_format(money, 0, number_format_decimal, number_format_thousand) + ' <small>+' + number_format(total_treasury, 0, number_format_decimal, number_format_thousand) + '</small>&nbsp;';
				}
			};
			function warehouse_sell(product_item) {
				if (warehouse[product_item-1] > 0) {
					money = Math.floor(money + prices[product_item-1]*((100 - tax)/100));
					engine_ajax('sell', (product_item-1));
					document.getElementById('money_amount').innerHTML  = number_format(money, 0, number_format_decimal, number_format_thousand);
					warehouse[product_item-1] = warehouse[product_item-1] - 1;
					document.getElementById('p' + product_item + '_quantity').innerHTML  = number_format(warehouse[product_item-1], 0, number_format_decimal, number_format_thousand);
					document.getElementById('p' + product_item + '_value').innerHTML  = number_format((warehouse[product_item-1]*prices[product_item-1]), 0, number_format_decimal, number_format_thousand);
					var total_treasury = 0;
					var i;
					for (i = 0; i < warehouse.length; i++) {
						total_treasury = total_treasury + Math.floor(warehouse[i]*prices[i]*((100 - tax)/100));
					} 
					document.getElementById('money_total_amount').innerHTML  = number_format(total_treasury, 0, number_format_decimal, number_format_thousand);
					document.getElementById('money_amount').innerHTML  = number_format(money, 0, number_format_decimal, number_format_thousand) + ' <small>+' + number_format(total_treasury, 0, number_format_decimal, number_format_thousand) + '</small>&nbsp;';
				}
			};
			function drawGame(highlight_x = 0, highlight_y =0) {
				if(canvas==null) { return; }
				for(var y = 0; y < mapH; ++y) {
					for(var x = 0; x < mapW; ++x) {
						switch(map[((y*mapW)+x)]) {
							case 0:
								base_image = new Image();
								if (buildings[((y*mapW)+x)] == 0) {
									base_image.src = '00000.png';
									canvas.drawImage(base_image, x*tileW, y*tileH);
								} else {
									base_image.src = '00001.png';
									canvas.drawImage(base_image, x*tileW, y*tileH);
								}
								break;
							case 1:
								base_image = new Image();
								tile_north = Math.abs(map[((y*mapW)+x)-mapH]-1);
								if(isNaN(tile_north)) tile_north = 0;
								if (tile_north > 1) tile_north = 1;
								tile_south = Math.abs(map[((y*mapW)+x)+mapH]-1);
								if(isNaN(tile_south)) tile_south = 0;
								if (tile_south > 1) tile_south = 1;
								tile_east = Math.abs(map[((y*mapW)+x)+1]-1);
								if(isNaN(tile_east)) tile_east = 0;
								if (tile_east > 1) tile_east = 1;
								tile_west = Math.abs(map[((y*mapW)+x)-1]-1);
								if(isNaN(tile_west)) tile_west = 0;
								if (tile_west > 1) tile_west = 1;
								base_image.src = '1'+tile_north+tile_east+tile_south+tile_west+'.png';
								canvas.drawImage(base_image, x*tileW, y*tileH, tileW, tileH);
								break;
							case 2:
								base_image = new Image();
								if (buildings[((y*mapW)+x)] == 0) {
									base_image.src = '20000.png';
									canvas.drawImage(base_image, x*tileW, y*tileH, tileW, tileH);
								} else {
									base_image.src = '20001.png';
									canvas.drawImage(base_image, x*tileW, y*tileH, tileW, tileH);
								}
								break;
							case 3:
								base_image = new Image();
								if (buildings[((y*mapW)+x)] == 0) {
									base_image.src = '30000.png';
									canvas.drawImage(base_image, x*tileW, y*tileH, tileW, tileH);
								} else {
									base_image.src = '30001.png';
									canvas.drawImage(base_image, x*tileW, y*tileH, tileW, tileH);
								}
								break;
						}
						base_image = new Image();
						if (buildings[((y*mapW)+x)] < 10)
							base_image.src = '8000'+buildings[((y*mapW)+x)]+'.png';
						else
							base_image.src = '800'+buildings[((y*mapW)+x)]+'.png';
						canvas.drawImage(base_image, (x*tileW+tileW/16), (y*tileH+tileH/16), tileW/1.4, tileH/1.4);
					}
				}
				requestAnimationFrame(drawGame);
			};
			function plot_build(building_type, current_tile) {
				if (buildings[current_tile] == 0) {
					if (buildings_compatibility[building_type - 1] == map[current_tile]) {
						if (buildings_cost[building_type - 1] < money) {							
							buildings[current_tile] = building_type;
							drawGame();
							var plot_content = document.getElementById('plot');
							plot_content.style.display = 'none';
							var statistics_content = document.getElementById('statistics');
							statistics_content.style.display = 'none';
							money = money - buildings_cost[building_type - 1];
							var total_treasury = 0;
							var i;
							for (i = 0; i < warehouse.length; i++) {
								total_treasury = total_treasury + warehouse[i]*prices[i];
							} 
							document.getElementById('money_amount').innerHTML  = number_format(money, 0, number_format_decimal, number_format_thousand) + ' <small>+' + number_format(total_treasury, 0, number_format_decimal, number_format_thousand) + '</small>&nbsp;';
							engine_ajax('build', current_tile + '-' + building_type);
						}
					}
				}
			};
			function plot_demolish(current_tile) {
				var demolision_cost = Math.floor(0.01*demolish_cost*buildings_cost[buildings[current_tile] - 1]);
				if (buildings[current_tile] != 0) {
					if (demolision_cost < money) {
						buildings[current_tile] = 0;						
						var plot_content = document.getElementById('plot');
						plot_content.style.display = 'none';
						var statistics_content = document.getElementById('statistics');
						statistics_content.style.display = 'none';
						money = money - demolision_cost;
						var total_treasury = 0;
						var i;
						for (i = 0; i < warehouse.length; i++) {
							total_treasury = total_treasury + warehouse[i]*prices[i];
						} 
						document.getElementById('money_amount').innerHTML  = number_format(money, 0, number_format_decimal, number_format_thousand) + ' <small>+' + number_format(total_treasury, 0, number_format_decimal, number_format_thousand) + '</small>&nbsp;';
						drawGame();
						engine_ajax('demolish', current_tile);
					}
				}
			};
			function getPosition(event) {
				var x = new Number();
				var y = new Number();
				var canvas = document.getElementById("map");
				if (event.x != undefined && event.y != undefined) {
					x = event.x;
					y = event.y;
				} else {
					x = event.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
					y = event.clientY + document.body.scrollTop + document.documentElement.scrollTop;
				}
				x = x - canvas.offsetLeft;
				y = y - canvas.offsetTop;
				var current_tile_X;
				var current_tile_Y;
				var current_tile;
				current_tile_X = Math.floor(mapW*x/canvas.clientWidth);
				current_tile_Y = Math.floor(mapH*y/canvas.clientHeight);
				current_tile = (current_tile_Y*mapW)+current_tile_X;
				var warehouse_content = document.getElementById('warehouse');
				warehouse_content.style.display = 'none';
				var plot_options = document.getElementById('plot_options');
				plot_options.innerHTML = '';
				var plot_content = document.getElementById('plot');
				if (plot_content.style.display === 'none')
					plot_content.style.display = 'block';
				var plot_coordinates = document.getElementById('plot_coordinates');
				plot_coordinates.innerHTML = (current_tile+1) + ' <small>(' + (current_tile_X+1) + '<sup>&#8728;</sup>,' + (current_tile_Y+1) + '<sup>&#8728;</sup>)</small>';
				var plot_status = document.getElementById('plot_status');
				if (buildings[current_tile] == 0) {
					plot_status.innerHTML = '[@available]';
					for(let i = 1; i <= products_total; i++) {
						var available_building_title = '';
						var available_building_cost = '';
						var available_building_commodity = '';
						if (buildings_compatibility[i - 1] == map[current_tile]) {
							if(i < 10) {
								available_building_title = "<img src='8000" + i + ".png' title='" + buildings_titles[i-1] + "'>&nbsp;" + buildings_titles[i-1];
								available_building_commodity = "<img src='8100" + i + ".png' title='" + products_titles[i-1] + "'>&nbsp;" + products_titles[i-1];
							} else {
								available_building_title = "<img src='800" + i + ".png' title='" + buildings_titles[i-1] + "'>&nbsp;" + buildings_titles[i-1];
								available_building_commodity = "<img src='810" + i + ".png' title='" + products_titles[i-1] + "'>&nbsp;" + products_titles[i-1];
							}
						}
						available_building_cost = buildings_cost[i - 1];
						if (available_building_title  != '')
							plot_options.innerHTML = plot_options.innerHTML + "<tr><td align='left'>&nbsp;" + available_building_title + "&nbsp;</td><td align='left'>" + available_building_commodity + "&nbsp;</td><td align='center'>" + available_building_cost + "</td><td colspan='2' id='container_button_build_" + i + "'>" + "</td></tr>";
					}
					for(let i = 1; i <= products_total; i++) {
						var element =  document.getElementById('container_button_build_' + i);
						if (typeof(element) != 'undefined' && element != null) {
							if (buildings_cost[i - 1] < money) {
								var button_build = document.createElement('button');
								button_build.classList.add('button_short');
								button_build.id ='button_build_' + i;
								button_build.name ='button_build_' + i;
								button_build.title = '[@build]';
								button_build.innerHTML = '<img src=\'70008.png\' title=\'[@build]\'>';
								element.appendChild(button_build);
								document.getElementById('button_build_' + i).addEventListener('click', function () { plot_build(i, current_tile); } );
							}
						}
					}
				} else {
					plot_status.innerHTML = '[@occupied]';
					var available_building_title = '';
					var available_building_commodity = '';
					var available_building_cost = Math.floor((demolish_cost * buildings_cost[buildings[current_tile] - 1])/100);
					if(buildings[current_tile] < 10) {
						available_building_title = "<img src='8000" + buildings[current_tile] + ".png' title='" + buildings_titles[buildings[current_tile]-1] + "'>&nbsp;" + buildings_titles[buildings[current_tile]-1];
						available_building_commodity = "<img src='8100" + buildings[current_tile] + ".png' title='" + products_titles[buildings[current_tile]-1] + "'>&nbsp;" + products_titles[buildings[current_tile]-1];
						
					} else {
						available_building_title = "<img src='800" + buildings[current_tile] + ".png' title='" + buildings_titles[buildings[current_tile]-1] + "'>&nbsp;" + buildings_titles[buildings[current_tile]-1];
						available_building_commodity = "<img src='810" + buildings[current_tile] + ".png' title='" + products_titles[buildings[current_tile]-1] + "'>&nbsp;" + products_titles[buildings[current_tile]-1];
					}
					plot_options.innerHTML = plot_options.innerHTML + "<tr><td align='left'>&nbsp;" + available_building_title + "&nbsp;</td><td align='center'>" + available_building_commodity + "&nbsp;</td><td align='center'>" + available_building_cost + "</td><td colspan='2' id='container_button_demolish'>" + "</td></tr>";
					if (available_building_cost < money) {
						var button_demolish = document.createElement('button');
						button_demolish.classList.add('button_short');
						button_demolish.id ='button_demolish';
						button_demolish.name ='button_demolish';
						button_demolish.title = '[@demolish]';
						button_demolish.innerHTML = '<img src=\'70009.png\' title=\'[@demolish]\'>';
						button_demolish.onclick = function() { plot_demolish(current_tile); };
						document.getElementById('container_button_demolish').appendChild(button_demolish);
					}
				}
			};
		</script>
		<link rel='stylesheet' type='text/css' href='game.css'>
		[@metatags]
	</head>
	<body>
		<center>
			<canvas id='map' width='640' height='640'>1</canvas>
			<br>
			<div class='menu_information'>
				<div class='menu_item_information'>
					<img src='90001.png' class='logo_small'>
					[@city]
				</div>
				<button class='button_short' onclick="view_warehouse();" title='[@warehouse_content]'><img src='70001.png' title='[@warehouse_content]'></button>
				<button class='button_short' onclick="view_statistics();" title='[@statistics_content]'><img src='70012.png' title='[@statistics_content]'></button>
				<button class='button_short' onclick="view_next();" title='[@continue]'><img src='70010.png' title='[@continue]'></button>
				&nbsp;&nbsp;
				<button class='button_blue_short' onclick="view_manual();" title='[@manual]'><img src='70011.png' title='[@manual]'></button>
				<button class='button_red_short' onclick="logoff();" title='[@logoff]'><img src='70005.png' title='[@logoff]'></button>
				<br>
				<br>
				<div class='menu_item_information'>
					<img src='70004.png' title='[@treasury]'>
					<span id='money_amount'></span>
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
			<div id='plot' width='320' height='320'>
				<br>
				<br>
				<div class='menu_item_information'>
					<b>
						[@plot_content]
					</b>
					<span id='plot_coordinates'></span>
					<br>
					<i>
						<span id='plot_status'></span>
					</i>
					<br>
					<br>
					<table>
						<thead>
							<tr>
								<td>
									[@plot_building]
								</td>
								<td>
									[@plot_production]
								</td>
								<td>
									[@plot_cost]
								</td>
								<td colspan='2'>
								</td>
							</tr>
						</thead>
						<thead id='plot_options'>
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
								<td align='center'>
									[@taxation]
								</td>
								<td>
									[@warehouse_total]
								</td>
								<td colspan='2'>
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
