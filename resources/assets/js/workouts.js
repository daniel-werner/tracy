

/*!*******************************************************************************
 |	Workouts v1.0.0
 |
 ********************************************************************************/

require('./point_utils');

;(function( root ){

	var Workouts = function()
	{
		if( Workouts.instance !== null )
		{
			return Workouts.instance;
		}

		Workouts.instance = this;
	};

	Workouts.instance = null;
	Workouts.getInstance = function()
	{
		return this.instance;
	};

	Workouts.prototype = {
		init: function(){
			this.bind();
			this.showMaps();
			this.showAnalysisChart();
		},
		bind: function(){
			$("input[type=file]").change(function () {
				var fieldVal = $(this).val();

				// Change the node's value by removing the fake path (Chrome)
				fieldVal = fieldVal.replace("C:\\fakepath\\", "");

				if (fieldVal != undefined || fieldVal != "") {
					$(this).next(".custom-file-label").attr('data-content', fieldVal);
					$(this).next(".custom-file-label").text(fieldVal);
				}

			});
		},
		setWokroutData: function(data){
			this.data = data;
		},
		showMaps: function(){
			var _this = this;

			$(".workout-map").each(function(index, item){
				var workoutId = item.id;

				var lat = _this.data[index].points.length ? _this.data[index].points[0].lat : 0;
				var lng =_this.data[index].points.length ? _this.data[index].points[0].lng : 0;
				var map = L.map(workoutId).setView([lat, lng], 15);

				var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
				var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
				var osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 25, attribution: osmAttrib});

				map.addLayer(osm);

				var polyline = L.polyline(_this.data[index].points, {color: 'blue'}).addTo(map);
				map.fitBounds(polyline.getBounds());

			});
		},
		showAnalysisChart: function(){

			var speedData = {
				name: 'Speed',
				yAxis: 0,
				data: []
			};

			var heartRateData = {
				name: 'Heart Rate',
				yAxis: 1,
				data: []
			};

			var elevationData = {
				name: 'Elevation',
				yAxis: 2,
				data: []
			};

			var speed = 0;
			var distanceSum = 0;
			var duration = 0;
			var firstPoint = this.data[0].points[0],
				prevPoint = firstPoint;

			this.data[0].points.forEach( function(point, index){

					if(prevPoint){
						var distance = PointUtils.distance( prevPoint, point );
						if( distance > 0 ) {
							distanceSum += distance;
							speed = PointUtils.speed(prevPoint, point);
							var x = distanceSum / 1000;

							prevPoint = point;

							speedData.data.push({
								x: x,
								y: speed,
								duration: PointUtils.timeDifferenceFormatted(firstPoint, point)
							});
							heartRateData.data.push({
								x: x,
								y: point.heart_rate,
							});

							elevationData.data.push({
								x: x,
								y: point.elevation
							});
						}
				}
			});

			var series = [
				speedData,
				heartRateData,
				elevationData
			];

			$('.analysis-chart').each(function(index, chartItem){
				var chart = new Highcharts.Chart( {
					chart: {
						renderTo: chartItem,
						type: 'spline',
						backgroundColor:'#F8F8F8'
					},
					title: {
						text: 'Workout Analysis'
					},

					tooltip: {
						formatter: function () {
							var s = 'Duration: ' + this.points[0].point.duration + '<br/>' +
								'Distance: ' + Math.round( this.x * 100 ) / 100 + ' ';

							$.each(this.points, function () {
								s += '<br/>' + this.series.name + ': ' +
									this.y;
							});

							return s;
						},
						shared: true
					},

					yAxis: [{
						title: {
							text: 'Speed (km/h)',
							style: {
								color: Highcharts.getOptions().colors[0]
							}
						}

					}, {
						title: {
							text: 'Heart Rate (bpm)',
							style: {
								color: Highcharts.getOptions().colors[1]
							}
						},
						opposite: true

					},
						{
						gridLineWidth: 0,
						title: {
							text: 'Elevation (m)',
							style: {
								color: Highcharts.getOptions().colors[2]
							}
						},
						visible: false,
						opposite: true
					}
					],
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle'
					},

					plotOptions: {
						series: {
							turboThreshold: 20000,
							label: {
								connectorAllowed: false
							},
							marker:{
								enabled:false
							},
							connectNulls: true
						}
					},

					series: series,

					responsive: {
						rules: [{
							condition: {
								//maxWidth: 500
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

		}
	};

	// Expose classes
	root.Workouts = Workouts;
})( window );
