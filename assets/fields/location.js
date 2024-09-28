function initialize_can_maps() {

    var map = new google.maps.Map(document.getElementById('map-canvas'), {
        center: new google.maps.LatLng(can_map_settings.def_lat, can_map_settings.def_long),
        scrollwheel: false,
        zoom: parseInt(can_map_settings.zoom_level)
    });

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(can_map_settings.def_lat, can_map_settings.def_long),
        map: map,
        icon: can_map_settings.drag_icon,
        draggable: true
    });
    
    google.maps.event.addListener(marker, 'drag', function(event) {
        jQuery('.can_listing_latitude').val(event.latLng.lat());
        jQuery('.can_listing_longitude').val(event.latLng.lng());
    });
    google.maps.event.addListener(marker, 'dragend', function(event) {
        jQuery('.can_listing_latitude').val(event.latLng.lat());
        jQuery('.can_listing_longitude').val(event.latLng.lng());
    });

    var searchBox = new google.maps.places.SearchBox(document.getElementById('search-map'));

    // map.controls[google.maps.ControlPosition.TOP_LEFT].push(document.getElementById('search-map'));
    google.maps.event.addListener(searchBox, 'places_changed', function() {
        searchBox.set('map', null);


        var places = searchBox.getPlaces();

        var bounds = new google.maps.LatLngBounds();
        var i, place;
        for (i = 0; place = places[i]; i++) {
            (function(place) {
                var marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map: map,
                    icon: can_map_settings.drag_icon,
                    draggable: true
                });
                var location = place.geometry.location;
                var n_lat = location.lat();
                var n_lng = location.lng();

                jQuery('.can_listing_latitude').val(n_lat);
                jQuery('.can_listing_longitude').val(n_lng);

                marker.bindTo('map', searchBox, 'map');
                google.maps.event.addListener(marker, 'map_changed', function(event) {
                    if (!this.getMap()) {
                        this.unbindAll();
                    }
                });
                google.maps.event.addListener(marker, 'drag', function(event) {
                    jQuery('.can_listing_latitude').val(event.latLng.lat());
                    jQuery('.can_listing_longitude').val(event.latLng.lng());
                });
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    jQuery('.can_listing_latitude').val(event.latLng.lat());
                    jQuery('.can_listing_longitude').val(event.latLng.lng());
                });
                bounds.extend(place.geometry.location);
            }(place));

        }
        map.fitBounds(bounds);
        searchBox.set('map', map);
        map.setZoom(Math.min(map.getZoom(), parseInt(can_map_settings.zoom_level)));

    });
}
if (can_map_settings.use_map_from == 'google_maps') {
    google.maps.event.addDomListener(window, 'load', initialize_can_maps);
}
jQuery(document).ready(function($) {
    if (can_map_settings.use_map_from == 'leaflet' && $('#map-canvas').length != 0) {
        var listing_map = L.map('map-canvas').setView([can_map_settings.def_lat, can_map_settings.def_long], parseInt(can_map_settings.zoom_level));
        
        L.tileLayer(can_map_settings.leaflet_styles.provider, {
                maxZoom: 21,
            }).addTo(listing_map);
        var propertyIcon = L.icon({
            iconUrl: can_map_settings.drag_icon,
            iconSize: [72, 60],
            iconAnchor: [36, 47],
        });
        var marker = L.marker([can_map_settings.def_lat, can_map_settings.def_long], {icon: propertyIcon, draggable: true}).addTo(listing_map);
        setTimeout(function() {
            listing_map.invalidateSize();
        }, 1000);

        var geocoder = L.Control.geocoder({
            defaultMarkGeocode: false
        })
        .on('markgeocode', function(event) {
            var center = event.geocode.center;
            listing_map.setView(center, listing_map.getZoom());
            marker.setLatLng(center);
            jQuery('.can_listing_latitude').val(marker.getLatLng().lat);
            jQuery('.can_listing_longitude').val(marker.getLatLng().lng);
        }).addTo(listing_map);

        marker.on('dragend', function (e) {
            jQuery('.can_listing_latitude').val(marker.getLatLng().lat);
            jQuery('.can_listing_longitude').val(marker.getLatLng().lng);
        });
        marker.on('drag', function (e) {
            jQuery('.can_listing_latitude').val(marker.getLatLng().lat);
            jQuery('.can_listing_longitude').val(marker.getLatLng().lng);
        });

        jQuery('.leaflet-control-geocoder-form input').keypress(function(e){
            if ( e.which == 13 ) e.preventDefault();
        });
    }    
});