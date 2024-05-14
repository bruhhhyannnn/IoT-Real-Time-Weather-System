<!-- 
    Agbayani, Leane Joyce
    Agustin, Yves Kristian
    Barruga, Abijah McGuiller
    Castillo, Keiren Aizel
    Dadia, Christian Joshua
    Dionisio, Erika Mae
    Esteban, Karl Cedrick
    Esguerra, Desiree
    Galing, Jhon Paul
    Mangapit, Bryan Jesus
    Sambrano, Alexia
    Sibayan, Alvin Jay

! Complete Midterm Project ! 
-->

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Weather System</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="shortcut icon" type="image/x-icon" href="icon-weather.ico">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
	<style>
		* {
			color: #FFF;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			text-align: center;
			margin: 0px auto;
		}

		html {
			display: inline-block;
		}

		body {
			background-color: #203864;
		}

		h1 {
			font-size: 4.0rem;
		}

		#space,
		#heat_classification {
			margin-bottom: 50px;
		}

		.chart {
			width: 100%;
			min-height: 450px;
		}

		.row {
			margin: 0 !important;
			padding-top: 100px;
		}

		.table {
			margin-bottom: 200px;
		}

		.table th {
			font-size: 20px;
			background: #666;
			color: #FFF;
			padding: 10px;
			border: 1px solid #000;
		}

		.table td {
			padding: 10px;
			background: #d4d4d4;
			color: #000;
			font-size: 16px;
			text-align: center;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1>Real Time Weather System</h1>
				<h3 id="space">using ESP8266 with DHT11 Sensor</h3>
				<p>A partial fulfillment of the requirements in IT 171 - IT Elective subject.</p>
			</div>
			<div class="col-md-6">
				<div id="chart_temperature" class="chart"></div>
			</div>
			<div class="col-md-6">
				<div id="chart_humidity" class="chart"></div>
			</div>
		</div>
		<div class="col-md-12 text-center">
			<h3>Location: <span id="location">MMSU</span></h3>
			<h3>Heat Index: <span id="heat_index"></span></h3>
			<h4>Heat Classification: <span id="heat_classification"></span></h4>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table" cellspacing="5" cellpadding="5">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Location</th>
							<th scope="col">Temperature &deg;C</th>
							<th scope="col">Humidity &#37;</th>
							<th scope="col">Heat Index &deg;C</th>
							<th scope="col">Date & Time</th>
						</tr>
					</thead>
					<tbody id="table-body">
						<!-- Table data will be inserted here dynamically -->
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
		google.charts.load('current', {
			'packages': ['gauge']
		});
		google.charts.setOnLoadCallback(drawTemperatureChart);
		google.charts.setOnLoadCallback(drawHumidityChart);

		function drawTemperatureChart() {
			var data = google.visualization.arrayToDataTable([
				['Label', 'Value'],
				['Temperature', 0],
			]);
			var options = {
				width: 1600,
				height: 480,
				redFrom: 70,
				redTo: 100,
				yellowFrom: 40,
				yellowTo: 70,
				greenFrom: 0,
				greenTo: 40,
				minorTicks: 5
			};
			var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
			chart.draw(data, options);

			function refreshData() {
				$.ajax({
					url: 'getData.php',
					dataType: 'json',
					success: function(responseText) {
						var var_temperature = parseFloat(responseText.temperature).toFixed(2);
						var data = google.visualization.arrayToDataTable([
							['Label', 'Value'],
							['Temperature', eval(var_temperature)],
						]);
						chart.draw(data, options);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown + ': ' + textStatus);
					}
				});
			}
			setInterval(refreshData, 1000);
		}

		function drawHumidityChart() {
			var data = google.visualization.arrayToDataTable([
				['Label', 'Value'],
				['Humidity', 0],
			]);
			var options = {
				width: 1600,
				height: 480,
				redFrom: 70,
				redTo: 100,
				yellowFrom: 40,
				yellowTo: 70,
				greenFrom: 0,
				greenTo: 40,
				minorTicks: 5
			};
			var chart = new google.visualization.Gauge(document.getElementById('chart_humidity'));
			chart.draw(data, options);

			function refreshData() {
				$.ajax({
					url: 'getData.php',
					dataType: 'json',
					success: function(responseText) {
						var var_humidity = parseFloat(responseText.humidity).toFixed(2);
						var data = google.visualization.arrayToDataTable([
							['Label', 'Value'],
							['Humidity', eval(var_humidity)],
						]);
						chart.draw(data, options);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown + ': ' + textStatus);
					}
				});
			}
			setInterval(refreshData, 1000);
		}

		function updateTableAndHeatIndex() {
			$.ajax({
				url: 'getTableData.php',
				dataType: 'json',
				success: function(data) {
					var tableBody = $('#table-body');
					tableBody.empty();
					var latestRow = data[0]; // Fetch the first row (latest)
					$('#heat_index').text(latestRow.heat_index);
					$('#location').text(latestRow.location);
					$('#heat_classification').text(updateHeatClassification(latestRow.heat_index));
					$.each(data, function(index, row) {
						tableBody.append('<tr>' +
							'<th scope="row">' + (index + 1) + '</th>' +
							'<td>' + row.location + '</td>' + '<td>' + row.temperature + '</td>' +
							'<td>' + row.humidity + '</td>' +
							'<td>' + row.heat_index + '</td>' +
							'<td>' + row.created_date + '</td>' +
							'</tr>');
					});
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown + ': ' + textStatus);
				}
			});
		}

		function updateHeatClassification(temperature) {
			var classification = '';
			var color = '';
			if (temperature >= 27 && temperature <= 32) {
				classification = '27 - 32 Caution';
				color = '#ffff01';
			} else if (temperature >= 32 && temperature <= 41) {
				classification = '33 - 41 Extreme Caution';
				color = 'hsl(48, 100%, 50%)';
			} else if (temperature >= 41 && temperature <= 54) {
				classification = '41 - 54 Danger';
				color = '#ff6501';
			} else if (temperature >= 54) {
				classification = '54 - Above Extreme Danger';
				color = '#cc0001';
			}
			// Set the text and color
			$('#heat_classification').text(classification).css('color', color);
			return classification;
		}

		setInterval(updateTableAndHeatIndex, 1000);

		$(window).resize(function() {
			drawTemperatureChart();
			drawHumidityChart();
		});
	</script>
</body>

</html>