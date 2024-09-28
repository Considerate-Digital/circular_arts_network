
//	initializeSingleListingMap();

console.log("location js loaded");

jQuery(document).ready(function($) {
	
    function uclInsertMarker(map, position){
        
        var image = can_location_settings.maps_icon_url;
        var marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: image
        });
    }

    function initializeSingleListingMap() {
	    console.log(can_location_settings);
        var lat = can_location_settings.latitude;
        var lon = can_location_settings.longitude;
        var zoom = parseInt(can_location_settings.zoom);
        var map_type = can_location_settings.map_type;
        var myLatLng = new google.maps.LatLng(lat, lon);
        var mapProp = {
            center:myLatLng,
            zoom: zoom,
            mapTypeId: map_type,
            minZoom: zoom - 5,
            maxZoom: zoom + 5,
            styles: (can_location_settings.maps_styles != '') ? JSON.parse(can_location_settings.maps_styles) : '',
        };

        var map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);
        map.setTilt(0);

        uclInsertMarker(map, myLatLng);
    }
    if (can_location_settings.latitude != 'disable' && can_location_settings.use_map_from == 'google_maps') {
        google.maps.event.addDomListener(window, 'load', initializeSingleListingMap);
    }

    if (can_location_settings.use_map_from == 'leaflet') {
        if ("ontouchstart" in document.documentElement) {
            var dragging = false;
        } else {
            var dragging = true;
        }        
    	var property_map = L.map('map-canvas', {scrollWheelZoom: false, dragging: dragging}).setView([can_location_settings.latitude, can_location_settings.longitude], parseInt(can_location_settings.zoom));
        
        L.tileLayer(can_location_settings.leaflet_styles.provider, {
                maxZoom: 21,
            }).addTo(property_map);
        var propertyIcon = L.icon({
            iconUrl: can_location_settings.maps_icon_url,
            iconSize: can_location_settings.icons_size,
            iconAnchor: can_location_settings.icons_anchor,
        });

        var marker = L.marker([can_location_settings.latitude, can_location_settings.longitude], {icon: propertyIcon}).addTo(property_map);


        if (can_location_settings.maps_styles != '') {
            // console.log(can_location_settings.maps_styles);
            // L.geoJSON(JSON.parse(can_location_settings.maps_styles)).addTo(property_map);
        }
    }
});
