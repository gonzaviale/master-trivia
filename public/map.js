var mymap = L.map('mapa').setView([0, 0], 10);

L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
}).addTo(mymap);

var marker;

mymap.on('click', function(e) {
    if (marker) {
        mymap.removeLayer(marker);
    }
    marker = L.marker(e.latlng).addTo(mymap);

    // Reverse geocoding to get country and city
    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + e.latlng.lat + '&lon=' + e.latlng.lng)
        .then(response => response.json())
        .then(data => {
            document.getElementById('pais').value = data.address.country;
            document.getElementById('ciudad').value = data.address.city || data.address.town || data.address.village;
        })
        .catch(error => console.error('Error:', error));
});

