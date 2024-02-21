function initialize_ucl_maps() {

    var map = new google.maps.Map(document.getElementById('map-canvas'), {
        center: new google.maps.LatLng(ucl_map_settings.def_lat, ucl_map_settings.def_long),
        scrollwheel: false,
        zoom: parseInt(ucl_map_settings.zoom_level)
    });

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(ucl_map_settings.def_lat, ucl_map_settings.def_long),
        map: map,
        icon: ucl_map_settings.drag_icon,
        draggable: true
    });
    
    google.maps.event.addListener(marker, 'drag', function(event) {
        jQuery('.ucl_listing_latitude').val(event.latLng.lat());
        jQuery('.ucl_listing_longitude').val(event.latLng.lng());
    });
    google.maps.event.addListener(marker, 'dragend', function(event) {
        jQuery('.ucl_listing_latitude').val(event.latLng.lat());
        jQuery('.ucl_listing_longitude').val(event.latLng.lng());
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
                    icon: ucl_map_settings.drag_icon,
                    draggable: true
                });
                var location = place.geometry.location;
                var n_lat = location.lat();
                var n_lng = location.lng();

                jQuery('.ucl_listing_latitude').val(n_lat);
                jQuery('.ucl_listing_longitude').val(n_lng);

                marker.bindTo('map', searchBox, 'map');
                google.maps.event.addListener(marker, 'map_changed', function(event) {
                    if (!this.getMap()) {
                        this.unbindAll();
                    }
                });
                google.maps.event.addListener(marker, 'drag', function(event) {
                    jQuery('.ucl_listing_latitude').val(event.latLng.lat());
                    jQuery('.ucl_listing_longitude').val(event.latLng.lng());
                });
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    jQuery('.ucl_listing_latitude').val(event.latLng.lat());
                    jQuery('.ucl_listing_longitude').val(event.latLng.lng());
                });
                bounds.extend(place.geometry.location);
            }(place));

        }
        map.fitBounds(bounds);
        searchBox.set('map', map);
        map.setZoom(Math.min(map.getZoom(), parseInt(ucl_map_settings.zoom_level)));

    });
}
if (ucl_map_settings.use_map_from == 'google_maps') {
    google.maps.event.addDomListener(window, 'load', initialize_ucl_maps);
}
jQuery(document).ready(function($) {
    if (ucl_map_settings.use_map_from == 'leaflet' && $('#map-canvas').length != 0) {
        var listing_map = L.map('map-canvas').setView([ucl_map_settings.def_lat, ucl_map_settings.def_long], parseInt(ucl_map_settings.zoom_level));
        
        L.tileLayer(ucl_map_settings.leaflet_styles.provider, {
                maxZoom: 21,
            }).addTo(listing_map);
        var propertyIcon = L.icon({
            iconUrl: ucl_map_settings.drag_icon,
            iconSize: [72, 60],
            iconAnchor: [36, 47],
        });
        var marker = L.marker([ucl_map_settings.def_lat, ucl_map_settings.def_long], {icon: propertyIcon, draggable: true}).addTo(listing_map);
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
            jQuery('.ucl_listing_latitude').val(marker.getLatLng().lat);
            jQuery('.ucl_listing_longitude').val(marker.getLatLng().lng);
        }).addTo(listing_map);

        marker.on('dragend', function (e) {
            jQuery('.ucl_listing_latitude').val(marker.getLatLng().lat);
            jQuery('.ucl_listing_longitude').val(marker.getLatLng().lng);
        });
        marker.on('drag', function (e) {
            jQuery('.ucl_listing_latitude').val(marker.getLatLng().lat);
            jQuery('.ucl_listing_longitude').val(marker.getLatLng().lng);
        });

        jQuery('.leaflet-control-geocoder-form input').keypress(function(e){
            if ( e.which == 13 ) e.preventDefault();
        });
    }    
});