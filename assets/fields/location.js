function initialize_circartsnet_maps() {

    var map = new google.maps.Map(document.getElementById('map-canvas'), {
        center: new google.maps.LatLng(circartsnet_map_settings.def_lat, circartsnet_map_settings.def_long),
        scrollwheel: false,
        zoom: parseInt(circartsnet_map_settings.zoom_level)
    });

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(circartsnet_map_settings.def_lat, circartsnet_map_settings.def_long),
        map: map,
        icon: circartsnet_map_settings.drag_icon,
        draggable: true
    });
    
    google.maps.event.addListener(marker, 'drag', function(event) {
        jQuery('.circartsnet_listing_latitude').val(event.latLng.lat());
        jQuery('.circartsnet_listing_longitude').val(event.latLng.lng());
    });
    google.maps.event.addListener(marker, 'dragend', function(event) {
        jQuery('.circartsnet_listing_latitude').val(event.latLng.lat());
        jQuery('.circartsnet_listing_longitude').val(event.latLng.lng());
    });

    var searchBox = new google.maps.places.SearchBox(document.getElementById('search-map'));
    var geocoder = new google.maps.Geocoder();

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
                    icon: circartsnet_map_settings.drag_icon,
                    draggable: true
                });
                var location = place.geometry.location;
                var n_lat = location.lat();
                var n_lng = location.lng();

                jQuery('.circartsnet_listing_latitude').val(n_lat);
                jQuery('.circartsnet_listing_longitude').val(n_lng);

                marker.bindTo('map', searchBox, 'map');
                google.maps.event.addListener(marker, 'map_changed', function(event) {
                    if (!this.getMap()) {
                        this.unbindAll();
                    }
                });
                google.maps.event.addListener(marker, 'drag', function(event) {
                    jQuery('.circartsnet_listing_latitude').val(event.latLng.lat());
                    jQuery('.circartsnet_listing_longitude').val(event.latLng.lng());
                });
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    jQuery('.circartsnet_listing_latitude').val(event.latLng.lat());
                    jQuery('.circartsnet_listing_longitude').val(event.latLng.lng());
                });
                bounds.extend(place.geometry.location);
            }(place));

        }
        map.fitBounds(bounds);
        searchBox.set('map', map);
        map.setZoom(Math.min(map.getZoom(), parseInt(circartsnet_map_settings.zoom_level)));

    });

    setup_postcode_autofill({
        provider: 'google',
        map: map,
        marker: marker,
        geocoder: geocoder
    });
}
if (circartsnet_map_settings.use_map_from == 'google_maps') {
    google.maps.event.addDomListener(window, 'load', initialize_circartsnet_maps);
}

jQuery(document).ready(function($) {
    if (circartsnet_map_settings.use_map_from == 'leaflet' && $('#map-canvas').length != 0) {
            var listing_map = L.map('map-canvas').setView([circartsnet_map_settings.def_lat, circartsnet_map_settings.def_long], parseInt(circartsnet_map_settings.zoom_level));

            L.tileLayer(circartsnet_map_settings.leaflet_styles.provider, {
                 attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 21,
             }).addTo(listing_map);

            var propertyIcon = L.icon({
                iconUrl: circartsnet_map_settings.drag_icon,
                iconSize: [72, 60],
                iconAnchor: [36, 47],
            });
        var marker = L.marker(
            [circartsnet_map_settings.def_lat, circartsnet_map_settings.def_long], {
                icon: propertyIcon, 
                draggable: true
            }).addTo(listing_map);

        setTimeout(function() {
            listing_map.invalidateSize();
        }, 1000);
        marker.on("moveend", (e) => {
            jQuery('.circartsnet_listing_latitude').val(marker.getLatLng().lat);
            jQuery('.circartsnet_listing_longitude').val(marker.getLatLng().lng);
        });

        setup_postcode_autofill({
            provider: 'leaflet',
            map: listing_map,
            marker: marker
        });
    }    
});

function setup_postcode_autofill(options) {
    var postcodeInput = document.getElementById('listing_zipcode');
    if (!postcodeInput) {
        postcodeInput = document.querySelector('[name="circartsnet_data[listing_zipcode]"]');
    }
    if (!postcodeInput || !document.getElementById('map-canvas')) {
        return;
    }

    var lastLookup = '';
    var handler = debounce(function() {
        var raw = postcodeInput.value ? postcodeInput.value.trim() : '';
        if (!raw || raw === lastLookup) {
            return;
        }

        var match = detect_postcode(raw);
        if (!match) {
            return;
        }
        lastLookup = raw;

        if (options.provider === 'google' && options.geocoder) {
            geocode_google(options.geocoder, raw, match.country, function(lat, lng) {
                apply_position(options, lat, lng);
            });
            return;
        }

        if (options.provider === 'leaflet') {
            geocode_leaflet(raw, match.country, function(lat, lng) {
                apply_position(options, lat, lng);
            });
        }
    }, 700);

    postcodeInput.addEventListener('input', handler);
    postcodeInput.addEventListener('blur', handler);
}

function detect_postcode(value) {
    var ukRegex = /^[A-Z]{1,2}\d[A-Z\d]?\s?\d[A-Z]{2}$/i;
    var usRegex = /^\d{5}(-\d{4})?$/;

    if (ukRegex.test(value)) {
        return { country: 'gb' };
    }
    if (usRegex.test(value)) {
        return { country: 'us' };
    }
    return null;
}

function geocode_google(geocoder, value, country, callback) {
    var countryLabel = country === 'gb' ? 'United Kingdom' : 'United States';
    geocoder.geocode({ address: value + ', ' + countryLabel }, function(results, status) {
        if (status === 'OK' && results && results[0]) {
            var location = results[0].geometry.location;
            callback(location.lat(), location.lng());
        }
    });
}

function geocode_leaflet(value, country, callback) {
    var params = new URLSearchParams({
        format: 'json',
        limit: '1',
        q: value,
        countrycodes: country
    });

    fetch('https://nominatim.openstreetmap.org/search?' + params.toString(), {
        method: 'GET'
    })
        .then(function(response) {
            if (!response.ok) {
                return null;
            }
            return response.json();
        })
        .then(function(results) {
            if (!results || !results.length) {
                return;
            }
            var result = results[0];
            var lat = parseFloat(result.lat);
            var lng = parseFloat(result.lon);
            if (Number.isNaN(lat) || Number.isNaN(lng)) {
                return;
            }
            callback(lat, lng);
        })
        .catch(function() {});
}

function apply_position(options, lat, lng) {
    if (!options || !options.marker || !options.map) {
        return;
    }

    if (options.provider === 'google') {
        var newPosition = new google.maps.LatLng(lat, lng);
        options.marker.setPosition(newPosition);
        options.map.setCenter(newPosition);
    } else if (options.provider === 'leaflet') {
        options.marker.setLatLng([lat, lng]);
        options.map.setView([lat, lng], Math.max(options.map.getZoom(), 10));
    }

    jQuery('.circartsnet_listing_latitude').val(lat);
    jQuery('.circartsnet_listing_longitude').val(lng);
}

function debounce(fn, wait) {
    var timer;
    return function() {
        var context = this;
        var args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() {
            fn.apply(context, args);
        }, wait);
    };
}
