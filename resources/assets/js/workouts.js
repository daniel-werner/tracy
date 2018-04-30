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
		}
	};

	// Expose classes
	root.Workouts = Workouts;
})( window );
