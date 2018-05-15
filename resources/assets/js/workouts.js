$(document).ready(function(e){

});

/*!*******************************************************************************
 |	Workouts v1.0.0
 |
 ********************************************************************************/

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

			this.data[0].points.forEach( function(item, index){
				if( index % 5 === 0 ){
					heartRateData.data.push( item.heart_rate );
					elevationData.data.push( item.elevation );
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
						renderTo: chartItem
					},
					title: {
						text: 'Workout Analysis'
					},

					yAxis: [{ // Primary yAxis
						title: {
							text: 'Speed (km/h)',
							style: {
								color: Highcharts.getOptions().colors[0]
							}
						}

					}, { // Secondary yAxis
						title: {
							text: 'Heart Rate (bpm)',
							style: {
								color: Highcharts.getOptions().colors[1]
							}
						},
						opposite: true

					}, { // Tertiary yAxis
						gridLineWidth: 0,
						title: {
							text: 'Elevation (m)',
							style: {
								color: Highcharts.getOptions().colors[2]
							}
						},
						opposite: true
					}],
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle'
					},

					plotOptions: {
						series: {
							label: {
								connectorAllowed: false
							},
							pointStart: 2010,
							marker:{
								enabled:false
							},
							connectNulls: true
						}
					},

					series: series,
					//	[{
					//	name: 'Installation',
					//	data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
					//}, {
					//	name: 'Manufacturing',
					//	data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
					//}, {
					//	name: 'Sales & Distribution',
					//	data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
					//}, {
					//	name: 'Project Development',
					//	data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
					//}, {
					//	name: 'Other',
					//	data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
					//}],

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
