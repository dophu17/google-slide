<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Title of the document</title>

</head>

<body>
	<div id="chart" style="width: 49%; display: inline-block;"></div>
	<div id="image-url" style="width: 49%; display: inline-block;">image-url</div>
	Content of the document......
	<button id="get-image">Get image this chart</button>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var exportUrl = 'http://export.highcharts.com/';
		 	var data = [29.9, 71.5, 106.4];
		 	var categories = ["Jan", "Feb", "Mar"];

			$('#get-image').click(function(e){
				var optionsStr = JSON.stringify({
					title: {
				        text: 'Solar Employment Growth by Sector, 2010-2016'
				    },
				    subtitle: {
				        text: 'Source: thesolarfoundation.com'
				    },
				    yAxis: {
				        title: {
				            text: 'Number of Employees'
				        }
				    },
	                xAxis: {
		                categories: categories
		            },
	                series: [{
	                	data: data
		            }]
		        }),
		        dataString = encodeURI('async=true&type=image/jpeg&width=700&options=' + optionsStr);

		        if (window.XDomainRequest) {
		            var xdr = new XDomainRequest();
		            xdr.open("post", exportUrl+ '?' + dataString);
		            xdr.onload = function () {
		                console.log(xdr.responseText);
		                $('#image-url').html('<img src="' + exporturl + xdr.responseText + '"/>');
		            };
		            xdr.send();
		        } else {
		            $.ajax({
		                type: 'POST',
		                data: dataString,
		                url: exportUrl,
		                success: function (data) {
		                    console.log('get the file from relative url: ', exportUrl + data);
		                    $('#image-url').html('<img src="' + exportUrl + data + '"/>');
		                },
		                error: function (err) {
		                    debugger;
		                    console.log('error', err.statusText)
		                }
		            });
		        }
			});

			Highcharts.chart('chart', {
			    title: {
			        text: 'Solar Employment Growth by Sector, 2010-2016'
			    },

			    subtitle: {
			        text: 'Source: thesolarfoundation.com'
			    },

			    yAxis: {
			        title: {
			            text: 'Number of Employees'
			        }
			    },
			    legend: {
			        layout: 'vertical',
			        align: 'right',
			        verticalAlign: 'middle'
			    },

			    xAxis: {
	                "categories": categories
	            },
                series: [{
	                "data": data
	            }],

			    responsive: {
			        rules: [{
			            condition: {
			                maxWidth: 500
			            },
			            chartOptions: {
			                legend: {
			                    layout: 'horizontal',
			                    align: 'center',
			                    verticalAlign: 'bottom'
			                }
			            }
			        }]
			    }

			});
		});
	</script>
</body>

</html>