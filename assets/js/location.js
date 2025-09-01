
//	initializeSingleListingMap();

console.log("location js loaded");

jQuery(document).ready(function($) {
	
    function uclInsertMarker(map, position){
        
        var image = circartsnet_location_settings.maps_icon_url;
        var marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: image
        });
    }

    function initializeSingleListingMap() {
	    console.log(circartsnet_location_settings);
        var lat = circartsnet_location_settings.latitude;
        var lon = circartsnet_location_settings.longitude;
        var zoom = parseInt(circartsnet_location_settings.zoom);
        var map_type = circartsnet_location_settings.map_type;
        var myLatLng = new google.maps.LatLng(lat, lon);
        var mapProp = {
            center:myLatLng,
            zoom: zoom,
            mapTypeId: map_type,
            minZoom: zoom - 5,
            maxZoom: zoom + 5,
            styles: (circartsnet_location_settings.maps_styles != '') ? JSON.parse(circartsnet_location_settings.maps_styles) : '',
        };

        var map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);
        map.setTilt(0);

        uclInsertMarker(map, myLatLng);
    }
    if (circartsnet_location_settings.latitude != 'disable' && circartsnet_location_settings.use_map_from == 'google_maps') {
        google.maps.event.addDomListener(window, 'load', initializeSingleListingMap);
    }

    if (circartsnet_location_settings.use_map_from == 'leaflet') {
        if ("ontouchstart" in document.documentElement) {
            var dragging = false;
        } else {
            var dragging = true;
        }        
    	var property_map = L.map('map-canvas', {scrollWheelZoom: false, dragging: dragging}).setView([circartsnet_location_settings.latitude, circartsnet_location_settings.longitude], parseInt(circartsnet_location_settings.zoom));
        
        L.tileLayer(circartsnet_location_settings.leaflet_styles.provider, {
                maxZoom: 21,
            }).addTo(property_map);
        var propertyIcon = L.icon({
            iconUrl: circartsnet_location_settings.maps_icon_url,
            iconSize: circartsnet_location_settings.icons_size,
            iconAnchor: circartsnet_location_settings.icons_anchor,
        });

        var marker = L.marker([circartsnet_location_settings.latitude, circartsnet_location_settings.longitude], {icon: propertyIcon}).addTo(property_map);


        if (circartsnet_location_settings.maps_styles != '') {
            // console.log(circartsnet_location_settings.maps_styles);
            // L.geoJSON(JSON.parse(circartsnet_location_settings.maps_styles)).addTo(property_map);
        }
    }
});
