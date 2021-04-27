$(function () {
    let marker = null;
    let map = L.map('map');
    if ($('#longitude').val() && $('#latitude').val()) {
        let lon = parseFloat($('#longitude').val());
        let lat = parseFloat($('#latitude').val());
        addMarker([lat, lon]);
        map.setView([lat, lon], 13)
        getAdressByLongLat(lat, lon);
    }
    else {
        map.setView([51.505, -0.09], 13);
    }

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'/*'https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png'/*'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}'*/, {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'/*cMap data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>'*/,
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        //  accessToken: 'pk.eyJ1IjoidG9hbTI1IiwiYSI6ImNrbnIzN2hzbTBic2wycG83dXYzZGE1ajgifQ.0bMW2544pp9K8o-E2hb3Ww'
    }).addTo(map);

    map.on('click', (e) => {
        let pos = e.latlng;
        addMarker(pos)
        getAdressByLongLat(pos.lat, pos.lng);
        $('#longitude').val(pos.lng);
        $('#latitude').val(pos.lat);
    })

    function addMarker(pos) {
        if (marker !== null) {
            map.removeLayer(marker);
        }
        marker = L.marker(pos, {
            draggable: true
        })
        marker.on('dragend', (e) => {
            pos = e.target.getLatLng();
            $('#longitude').val(pos.lng);
            $('#latitude').val(pos.lat);
            getAdressByLongLat(pos.lat, pos.lng);
        })

        marker.addTo(map);
    }
    $('#ville').on('blur', function (e) {
        e.preventDefault();
        getLongLatByAddress($(this).val());
    });
    $('#locate_me').on('click', function (e) {
        e.preventDefault();
        $('.locate_me_form').addClass('locate_me_ajax');
        navigator.geolocation.getCurrentPosition((position) => {
            addMarker([position.coords.latitude, position.coords.longitude]);
            $('#longitude').val(position.coords.longitude);
            $('#latitude').val(position.coords.latitude);
            getAdressByLongLat(position.coords.latitude, position.coords.longitude);
            map.setView([position.coords.latitude, position.coords.longitude], 13);
        });

    });

    function getAdressByLongLat(lat, log) {
        $.ajax({
            url: "https://nominatim.openstreetmap.org/reverse.php?format=jsonv2&lat=" + lat + "&lon=" + log + "&zoom=18",
            type: 'GET',
            dataType: 'json',
            beforeSend: () => {
            },
            success: (data) => {

                let address = data.address;
                let city = address.city ? address.city : ""
                let suburb = address.suburb ? address.suburb : ""
                let postcode = address.postcode ? address.postcode : ""
                $('#ville').val(
                    address.country +
                    `\n` + city + ` `
                    + postcode
                    + `\n` + address.state +
                    `\n` + suburb

                );
                $('.locate_me_form').removeClass('locate_me_ajax');
            },
            error: () => {
                toastr.error('Erreur de connexion au serveur');
                $(this).prop('disabled', false);
                $('.locate_me_form').removeClass('locate_me_ajax');

            }
        });
    }

    function getLongLatByAddress(city) {
        $.ajax({
            url: "https://nominatim.openstreetmap.org/search.php?q=" + city + "&polygon_geojson=1&format=jsonv2",
            type: 'GET',
            dataType: 'json',
            beforeSend: () => {
            },
            success: (data) => {
                data = data[0];
                if (data) {
                    addMarker([data.lat, data.lon])
                    map.setView([data.lat, data.lon]);
                    $('#longitude').val(data.lon);
                    $('#latitude').val(data.lat);
                }
                else {
                    toastr.error('Adresse introvalble');
                }
            },
            error: () => {
                toastr.error('Erreur de connexion au serveur');
            }
        });
    }
})