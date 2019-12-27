;(function( root ) {
// (mean) radius of Earth (meters)

	var R = 6378137;

	var squared = function (x) {
		return x * x
	};

	var toRad = function (x) {
		return x * Math.PI / 180.0
	};

	var PointUtils = function(){
	};

	PointUtils.distance = function( pointA, pointB){
		var aLat = pointA.latitude || pointA.lat;
		var bLat = pointB.latitude || pointB.lat;
		var aLng = pointA.longitude || pointA.lng || pointA.lon;
		var bLng = pointB.longitude || pointB.lng || pointB.lon;

		var dLat = toRad(bLat - aLat);
		var dLon = toRad(bLng - aLng);

		var f = squared(Math.sin(dLat / 2.0)) + Math.cos(toRad(aLat)) * Math.cos(toRad(bLat)) * squared(Math.sin(dLon / 2.0))
		var c = 2 * Math.atan2(Math.sqrt(f), Math.sqrt(1 - f));

		return R * c;
	};

	PointUtils.speed = function( pointA, pointB ){
		var distance = PointUtils.distance( pointA, pointB),
			timeDiff = PointUtils.timeDifference( pointA, pointB );

		var speed = Math.round( ( distance / timeDiff ) * 3.6 );

		return speed;
	};

	PointUtils.timeDifference = function(pointA, pointB){
		var timeA = Date.parse(pointA.time),
			timeB = Date.parse(pointB.time),
			timeDiff = ( timeB - timeA ) / 1000;

		return timeDiff;
	};

	PointUtils.timeDifferenceFormatted = function(pointA, pointB){
		var date = new Date(null),
			timeDiff = PointUtils.timeDifference(pointA, pointB);

		date.setSeconds(timeDiff);
		return date.toISOString().substr(11, 8);
	};

	root.PointUtils = PointUtils;

}(window));