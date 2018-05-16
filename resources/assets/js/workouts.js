

/*!*******************************************************************************
 |	Workouts v1.0.0
 |
 ********************************************************************************/

require('./point_utils');

;(function( root ){

	var Workouts = function()
	{

		this.defaults = {
			'mode': 'list'
		};

		this.options = {};

		this.data = null;
		this.maps = {};
	};

	Workouts.prototype = {
		init: function(options){

			this.options = $.extend( this.defaults, options );

			this.bind();
			this.showMaps();
			if(this.options.mode === 'details'){
				this.showAnalysisChart();
			}

		},
		setWokroutData: function(data){
			this.data = data;
		},
		showMaps: function(){
			var _this = this;

			this.data.forEach( function( item, index ) {

				var workoutId = item.id;

				var lat = _this.data[index].points.length ? _this.data[index].points[0].lat : 0;
				var lng = _this.data[index].points.length ? _this.data[index].points[0].lng : 0;
				var map = L.map('workout-map-' + workoutId).setView([lat, lng], 15);

				_this.maps[workoutId] = map;

				var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
				var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
				var osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 25, attribution: osmAttrib});

				map.addLayer(osm);

				var polyline = L.polyline(_this.data[index].points, {color: 'blue'}).addTo(map);
				map.fitBounds(polyline.getBounds());

			});
		},
		getChartSeries: function(data){
			var speedData = {
				id: 'speed',
				name: 'Speed',
				yAxis: 0,
				data: []
			};

			var heartRateData = {
				id: 'heart_rate',
				name: 'Heart Rate',
				yAxis: 1,
				data: []
			};

			var elevationData = {
				id: 'elevation',
				name: 'Elevation',
				yAxis: 2,
				type: 'area',
				data: []
			};

			var speed = 0;
			var distanceSum = 0;
			var firstPoint = data.points[0],
				prevPoint = firstPoint;

			data.points.forEach( function(point, index){

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
							duration: PointUtils.timeDifferenceFormatted(firstPoint, point),
							coordinates: point.coordinates
						});
						heartRateData.data.push({
							x: x,
							y: point.heart_rate
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

			return series;
		},
		setMarkerOnMap: function( coordinates, workoutId ){
			var map = this.maps[workoutId]

			map.eachLayer(function(layer){
				if( layer.options.id === 'marker' ){
					layer.remove();
				}
			});

			L.marker(coordinates, {id:'marker'}).addTo(map);
		},
		showAnalysisChart: function(){
			var _this = this;

			Highcharts.setOptions({
				colors: ["#7cb5ec", "#9B1D43", "#CCCCCC", "#f7a35c", "#8085e9", "#f15c80", "#e4d354", "#2b908f", "#f45b5b", "#91e8e1"]
			});

			this.data.forEach( function(workout, index ){
				var chart = new Highcharts.Chart( {
					chart: {
						renderTo: 'analysis-chart-' + workout.id,
						type: 'spline',
						backgroundColor:'#F8F8F8'
					},
					title: {
						text: 'Workout Analysis'
					},

					tooltip: {
						formatter: function () {

							var unitMap = {
								speed: 'km/h',
								heart_rate: 'bpm',
								elevation: 'm'
							};

							_this.setMarkerOnMap(this.points[0].point.coordinates, workout.id);

							var s = 'Duration: ' + this.points[0].point.duration + '<br/>' +
								'Distance: ' + Math.round( this.x * 100 ) / 100 + ' km';

							$.each(this.points, function () {
								s += '<br/>' + this.series.name + ': ' + this.y + ' ' + unitMap[this.series.options.id];
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
						},
						labels: {
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
						labels: {
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
							tickInterval: 10,
							min: Math.round(workout.minelevation/10)*10,
							max: Math.round(workout.maxelevation/10)*10,
							height: '40%',
							top: '60%',
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

					series: _this.getChartSeries( workout ),

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

	root.Workouts = Workouts;
})( window );
