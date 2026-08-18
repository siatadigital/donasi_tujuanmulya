@extends('layouts.default')
@section('title','create post')
@section('head')
	<style>
		body{
			background-color: #fbfbfb;
		}
		article img{
			max-width: 100%;
			margin-bottom:30px;
		}
		article{
			font-size: 16px;
		}
		.input-title-blog{
			text-align: center;
			font-size: 40px;
			height: 55px;
			border: none;
			box-shadow: none !important;
		}
		.input-title-blog:focus,.input-title-blog:hover..input-title-blog:active{
			outline: none !important;
			border: none;
			box-shadow: none !important;
		}
		#eventCoverBlock{
			background-image: url(http://localhost:8000/images/bg-login.jpg);
			height: 500px;
			background-position: center center;
			background-size: cover;
			/*background-attachment: fixed;*/
		}
		.coverformblock{
			/*margin-top: -400px;*/
			background-color: white;
			min-height: 400px;
			padding: 30px 33px;
		    border: solid #CCCCCC 1px;
		}
		#map {
        height: 400px;
      }
      .controls {
        background-color: #fff;
        border-radius: 2px;
        border: 1px solid transparent;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        height: 29px;
        margin-left: 17px;
        margin-top: 10px;
        outline: none;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      .controls:focus {
        border-color: #4d90fe;
      }
      #btn-change-cover{
		position: absolute;
		top: 100px !important;
		left: 40px !important;
      }
	</style>
	<script src="{{ asset('js/blog-create.js') }}"></script>
	<script src="{{ asset('lib/moment/min/moment.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('lib/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}">
	<script src="{{ asset('lib/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
@stop
@section('content')

	<div class="hidden">
		{!! Form::file('cover',['id'=>'upload-poster']) !!}
	</div>

	</div>
	<div class="container" style="padding-bottom:50px;padding-top:50px;">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 coverformblock">
				<article>
                    {!! Form::model($event, ['url' => route('event.putEdit', $event['slug']), 'method'=>'PUT']) !!}
						@include('contents.event._form')
					{!! Form::close() !!}
				</article>
			</div>
		</div>
	</div>
	<script src="{{ asset('js/user-setting.js') }}"></script>
	<script>
		$(document).ready(function(){
			$("#btnSave").click(function(){
				var cover = $("#browse-cover").val();
				var lat = $("#lat").val();
				if(cover == "")
				{
					alert("you must change cover !");
					$("#btn-change-cover").focus();
				}else if(lat == "")
				{
					alert("you mark location on maps !");
				}else{
					$("#formEvent").submit();
				}
			})
		})
		var map;
		var markers = [];

		function initMap() {
			var geocoder = new google.maps.Geocoder();

			map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -7.2574719, lng: 112.75208829999997},
			zoom: 13
			});

			var input = document.getElementById('pac-input');
			var autocomplete = new google.maps.places.Autocomplete(input);
			autocomplete.bindTo('bounds', map);

			map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

			var infowindow = new google.maps.InfoWindow();
			var marker = new google.maps.Marker({
				map: map
			});

			markers.push(marker);

			map.addListener('click', function(event) {
				deleteOverlays();
				placeMarker(event.latLng);
				getCity(event.latLng);
				$("#lat").val( event.latLng.lat() );
				$("#lng").val( event.latLng.lng() );
			});

			function placeMarker(location) {
	            marker = new google.maps.Marker({
	                position: location,
	                map: map
	            });

	            // add marker in markers array
	            markers.push(marker);
	        }

	        // Sets the map on all markers in the array.
			function setMapOnAll(map) {
			  for (var i = 0; i < markers.length; i++) {
			    markers[i].setMap(map);
			  }
			}

	        // Deletes all markers in the array by removing references to them
	        function deleteOverlays() {
	            setMapOnAll(null)
	            markers = [];
	        }

	        function getCity(latLng) {
	        	geocoder.geocode(
	        		{'latLng': latLng},
	        		function(results,status) {
	        			if (status == google.maps.GeocoderStatus.OK) {
	        				if (results[0]) {
	        					for (var i = 0, len = results[0].address_components.length; i < len; i++) {
	        						var ac = results[0].address_components[i];
	        						if (ac.types.indexOf("administrative_area_level_2") >= 0) {
	        							// alert(ac.long_name);
	        							$("#city").val(ac.long_name);
	        						}
	        					}
	        				}
	        			}
	        		}
		        )
	        }

			marker.addListener('click', function() {
			infowindow.open(map, marker);
			});

			autocomplete.addListener('place_changed', function() {
			infowindow.close();
			var place = autocomplete.getPlace();
			if (!place.geometry) {
			  return;
			}

			if (place.geometry.viewport) {
			  map.fitBounds(place.geometry.viewport);
			} else {
			  map.setCenter(place.geometry.location);
			  map.setZoom(17);
			}

			// Set the position of the marker using the place ID and location.
			marker.setPlace({
			  placeId: place.place_id,
			  location: place.geometry.location
			});
			marker.setVisible(true);

			infowindow.setContent("<div><strong>You Choice</strong></div>" + place.formatted_address);
			$("#lat").val(place.geometry.location.H);
			$("#lng").val(place.geometry.location.L);
			infowindow.open(map, marker);
			});
		}
		$(document).ready(function(){
            $("#date").datetimepicker({format: 'YYYY-MM-D HH:mm:ss'});
		})
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiB2e6nuAnjEox-xPzUBL7Tw8vzHs4FIY&libraries=places&signed_in=true&callback=initMap"
        async defer></script>
@stop
