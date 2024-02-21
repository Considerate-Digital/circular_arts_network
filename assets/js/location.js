jQuery(document).ready(function($) {

    function uclInsertMarker(map, position){
        
        var image = ucl_location_settings.maps_icon_url;
        var marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: image
        });
    }

    function initializeSingleListingMap() {
        var lat = ucl_location_settings.latitude;
        var lon = ucl_location_settings.longitude;
        var zoom = parseInt(ucl_location_settings.zoom);
        var map_type = ucl_location_settings.map_type;
        var myLatLng = new google.maps.LatLng(lat, lon);
        var mapProp = {
            center:myLatLng,
            zoom: zoom,
            mapTypeId: map_type,
            minZoom: zoom - 5,
            maxZoom: zoom + 5,
            styles: (ucl_location_settings.maps_styles != '') ? JSON.parse(ucl_location_settings.maps_styles) : '',
        };

        var map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);
        map.setTilt(0);

        uclInsertMarker(map, myLatLng);
    }
    if (ucl_location_settings.latitude != 'disable' && ucl_location_settings.use_map_from == 'google_maps') {
        google.maps.event.addDomListener(window, 'load', initializeSingleListingMap);
    }
    if (ucl_location_settings.use_map_from == 'leaflet') {
        if ("ontouchstart" in document.documentElement) {
            var dragging = false;
        } else {
            var dragging = true;
        }        
    	var property_map = L.map('map-canvas', {scrollWheelZoom: false, dragging: dragging}).setView([ucl_location_settings.latitude, ucl_location_settings.longitude], parseInt(ucl_location_settings.zoom));
        
        L.tileLayer(ucl_location_settings.leaflet_styles.provider, {
                maxZoom: 21,
            }).addTo(property_map);
        var propertyIcon = L.icon({
            iconUrl: ucl_location_settings.maps_icon_url,
            iconSize: ucl_location_settings.icons_size,
            iconAnchor: ucl_location_settings.icons_anchor,
        });

        var marker = L.marker([ucl_location_settings.latitude, ucl_location_settings.longitude], {icon: propertyIcon}).addTo(property_map);


        if (ucl_location_settings.maps_styles != '') {
            // console.log(ucl_location_settings.maps_styles);
            // L.geoJSON(JSON.parse(ucl_location_settings.maps_styles)).addTo(property_map);
        }
    }
});