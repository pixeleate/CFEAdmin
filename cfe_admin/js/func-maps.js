$(function() {

var image = {
    url: "img/cfe-marker.png", //url
    scaledSize: new google.maps.Size(20, 20), //scaled size
    origin: new google.maps.Point(0, 0), //origin
    anchor: new google.maps.Point(0, 0) //anchor
};
  
$('#issues-map').gmap().bind('init', function() { 
  // This URL won't work on your localhost, so you need to change it
  // see http://en.wikipedia.org/wiki/Same_origin_policy
  $.getJSON( 'http://pixeleate.com/propuestacfe/api/v1/CFEFallasMap', function(data) { 
    $.each( data.markers, function(i, marker) {
      $('#issues-map').gmap('addMarker', { 
        'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
        'icon': image,
        'bounds': true 
      }).click(function() {
        $('#issues-map').gmap('openInfoWindow', { 'content': marker.content }, this);
      });
    });
  });
});

$('#fails-map').gmap().bind('init', function() { 
  // This URL won't work on your localhost, so you need to change it
  // see http://en.wikipedia.org/wiki/Same_origin_policy
  $.getJSON( 'http://pixeleate.com/propuestacfe/api/v1/CFEFallasMap', function(data) { 
    $.each( data.markers, function(i, marker) {
      $('#fails-map').gmap('addMarker', { 
        'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
        'icon': image,
        'bounds': true 
      }).click(function() {
        $('#fails-map').gmap('openInfoWindow', { 'content': marker.content }, this);
      });
    });
  });
});



}); 