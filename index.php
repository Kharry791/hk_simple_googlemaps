<?php
/**
 * Created by Harry Kurniawan.
 * Date: 5/30/16
 * Time: 1:05 PM
 */

/*
	definisi variable untuk google map api key
	- isikan dengan api_key maps
	https://developers.google.com/maps/documentation/javascript/get-api-key
*/
define('API_KEY', '');
/*
	definisi variable apps base url
	- isikan dengan base url sesuai dengan kebutuhan
*/
define('BASE_URL', '');
?>

<html>
<head>
	<title>Maps Example</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-rc1/jquery.min.js"></script>
	<!--
		memanggil google maps api
		callback = fungsi bebas untuk inisialisasi map
	-->
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo API_KEY; ?>&callback=initMap"
	        type="text/javascript"></script>
</head>
<body>
<div id="div-map" style="width: 100%; height: 100%;">

</div>
</body>
<script>
	/*
		fungsi untuk menginisalisasi map
	*/
	function initMap() {
		/*
			definisi map pertama kali
		*/
		var _mapProp = {
			center: new google.maps.LatLng(-6.2444212, 106.9805215),
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		/*
			embed map ke dalam element berdasarkan ID
		*/
		var _map = new google.maps.Map(document.getElementById('div-map'), _mapProp);
		/*
			pemanggilan custom layer maps
			https://developers.google.com/kml/
		*/
		var _kmlUrl = 'http://harrykrn.com/test/peta.kml';
		/*
			setup kml
		*/
		var _kmlOptions = {
			suppressInfoWindows: true,
			preserveViewport: false,
			map: _map
		};
		/*
			embed kml ke dalam maps
		*/
		var _kmlLayer = new google.maps.KmlLayer(_kmlUrl, _kmlOptions);
		/*
			variable untuk menampung url request
		*/
		var _url = '<?php echo BASE_URL ?>model.php?action=getAllRoute';
		/*
		  _marker = variable untuk marker option
		  _dummyLat = variable untuk menampung latitude dari request response
		  _dummyLng = variable untuk menampung longitude dari request response
		*/
		var _marker;
		var _dummyLat = new Array();
		var _dummyLng = new Array();
		$.get(_url, function (data, status) {
			$.each(JSON.parse(data), function (index, value) {
				_dummyLat.push(value.lat);
				_dummyLng.push(value.lng);
				if (value.terminal == 1) {
					_marker = new google.maps.Marker({
						position: new google.maps.LatLng(value.lat, value.lng),
						map: _map,
						icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
					});
				}
			});
			/*
				_i = variable bebas untuk memanipulasi pembacaan array
			  _j = variable bebas untuk memanipulasi pembacaan array
				_movingMarkers1 = variable untuk menampung marker dari pergerakan dummy pertama (purple)
			  _movingMarkers2 = variable untuk menampung marker dari pergerakan dummy kedua (yellow)
			*/
			var _i = 0;
			var _j = 50;
			var _movingMarkers1;
			var _movingMarkers2;
			setInterval(function () {
				if (_i <= _dummyLat.length) {
					if (_i > 0) {
						_movingMarkers1.setMap(null);
					}
					_movingMarkers1 = new google.maps.Marker({
						position: new google.maps.LatLng(_dummyLat[_i], _dummyLng[_i]),
						map: _map,
						icon: 'http://maps.google.com/mapfiles/ms/icons/purple-dot.png'
					});
					_i++;
				} else {
					return false;
				}
				if (_j <= _dummyLat.length) {
					if (_j > 50) {
						_movingMarkers2.setMap(null);
					}
					_movingMarkers2 = new google.maps.Marker({
						position: new google.maps.LatLng(_dummyLat[_j], _dummyLng[_j]),
						map: _map,
						icon: 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
					});
					_j = _j + 5;
				} else {
					return false;
				}
			}, 2000);
		});
		/*
		  variable untuk menampung url request
		*/
		_url = '<?php echo BASE_URL ?>model.php?action=getCar';
		/*
		 _staticMarkers = variable untuk menampung marker response dari request (blue), sbg contoh untuk data kordinat dari database
		*/
		var _staticMarkers;
		$.get(_url, function (data, status) {
			$.each(JSON.parse(data), function (index, value) {
				_staticMarkers = new google.maps.Marker({
					position: new google.maps.LatLng(value.lat, value.lng),
					map: _map,
					icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
				});
			});
		});
	}
</script>
</html>
