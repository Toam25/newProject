$(function () {

    let $map = document.querySelector('#map')
    class LeafletMap {

        constructor() {
            this.map = null;
            this.bounds = [];
            this.text = " ";
            this.shipLayer = null;
        }
        async load(element) {

            return new Promise((resolve, reject) => {
                this.map = L.map(element);
                this.shipLayer = L.layerGroup();
                L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 18,
                    id: 'mapbox/streets-v11',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: 'pk.eyJ1IjoidG9hbTI1IiwiYSI6ImNrbnIzN2hzbTBic2wycG83dXYzZGE1ajgifQ.0bMW2544pp9K8o-E2hb3Ww'
                }).addTo(this.map);
                resolve()
            })

        }

        addMarker(lat, lng, text) {
            let point = [lat, lng];
            this.bounds.push(point);
            this.shipLayer.addLayer(L.marker([lat, lng]));
            return new LeafletMarker(point, text, this.map);
        }
        center() {
            this.map.fitBounds(this.bounds);
        }
        addBindMarker(lat, lng, text) {
            this.map.setView([lat, lng]);
            L.marker([lat, lng]).addTo(this.map)
                .bindPopup(text)
                .openPopup();
        }
        removeLayer(marker) {
            this.map.removeLayer(marker);
        }
    }

    class LeafletMarker {
        constructor(point, text, map) {
            this.text = text;
            this.popup = L.marker(point).addTo(map).bindPopup(text).openPopup();

            this.popup = L.popup({
                autoClose: false,
                closeOnEscapeKey: false,
                closeOnClick: false,
                closeButton: false,
                className: 'marker',
                maxWidth: 400,

            })
                .setLatLng(point)
                .setContent(text)
                .openOn(map)
        }
        // setActive() {
        //     this.popup.getElement().classList.add('is-active');
        // }
        // unsetActive() {
        //     this.popup.getElement().classList.remove('is-active');
        // }
        // addEventListener(event, cb) {
        //     this.popup.addEventListener('add', () => {
        //         this.popup.getElement().addEventListener(event, cb)
        //     })
        // }
        // setContent(text) {
        //     this.popup.setContent(text);
        //     this.popup.getElement().classList.add('is-expanded')
        //     this.popup.update();
        // }
        // resetContent() {
        //     this.popup.setContent(this.text);
        //     this.popup.getElement().classList.remove('is-expanded')
        //     this.popup.update();
        // }
    }

    const initMap = async function () {
        let map = new LeafletMap();
        let hoverMarker = null;
        let activeMarker = null;
        let markers = [];
        await map.load($map);

        Array.from(document.querySelectorAll('.js_market')).forEach(item => {

            if (item.dataset.lat !== "") {


                let marker = map.addMarker(item.dataset.lat, item.dataset.log, item.dataset.name);

                markers.push(L.marker([item.dataset.lat, item.dataset.log]));

                // item.addEventListener('mouseover', function () {


                //     if (hoverMarker !== null) {
                //         hoverMarker.unsetActive();
                //     }
                //     marker.setActive();
                //     hoverMarker = marker;
                // })
                // item.addEventListener('mouseleave', function () {
                //     if (hoverMarker !== null) {
                //         hoverMarker.unsetActive();
                //     }
                // })
                // marker.addEventListener('click', function () {
                //     if (activeMarker !== null) {
                //         activeMarker.resetContent();
                //     }
                //     marker.setContent(item.innerHTML)
                //     activeMarker = marker;
                // });
            }
        });

        $('#geolocate_me').on('click', function () {
            navigator.geolocation.getCurrentPosition(function (position) {
                map.addBindMarker(position.coords.latitude, position.coords.longitude, "Vous êtes ici");

            });
        });

        markers.forEach(element => {
            map.removeLayer(element);

        });

        console.log(markers);
        for (var i = 0; i < markers.length; i++) {
            map.removeLayer(markers[i]);
        }
        $('#search_in_map').on('blur', function (e) {
            e.preventDefault();
            let text = $(this).val();
            $.ajax({
                url: "/galery_marchande/shop",
                type: 'GET',
                data: {
                    q: text
                },
                dataType: 'html',
                beforeSend: () => {
                },
                success: (data) => {
                    $('#container_list_shop').html(data);

                    markers.forEach(element => {
                        map.removeLayer(element);

                    });
                    Array.from(document.querySelectorAll('.js_market')).forEach(item => {

                        if (item.dataset.lat !== "") {
                            let marker = map.addMarker(item.dataset.lat, item.dataset.log, item.dataset.name);


                            markers.push([item.dataset.lat, item.dataset.log]);
                            item.addEventListener('mouseover', function () {


                                if (hoverMarker !== null) {
                                    hoverMarker.unsetActive();
                                }
                                marker.setActive();
                                hoverMarker = marker;
                            })
                            item.addEventListener('mouseleave', function () {
                                if (hoverMarker !== null) {
                                    hoverMarker.unsetActive();
                                }
                            })
                            // marker.addEventListener('click', function () {
                            //     if (activeMarker !== null) {
                            //         activeMarker.resetContent();
                            //     }
                            //     marker.setContent(item.innerHTML)
                            //     activeMarker = marker;
                            // });
                        }
                    });

                },
                error: () => {
                    toastr.error('Erreur de connexion au serveur');
                    $(this).prop('disabled', false);
                }
            });
        });

        $('#search_in_map').on('blur', function (e) {
            e.preventDefault();
            let text = $(this).val();
            $.ajax({
                url: "/galery_marchande/shop/nbr",
                type: 'GET',
                data: {
                    q: text
                },
                dataType: 'json',
                beforeSend: () => {
                },
                success: (data) => {
                    $('#nbr_result').text(data.nbr);

                },
                error: () => {
                }
            });
        });
        map.center();
    }
    if ($map !== null) {
        initMap();
    }

});
