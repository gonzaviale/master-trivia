

function buscarUbicacionJugador(ciudad, pais) {
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${ciudad},${pais}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const latitud = data[0].lat;
                const longitud = data[0].lon;
                mostrarUbicacionEnMapa(latitud, longitud);
            } else {
                console.error('Ubicación no encontrada');
            }
        })
        .catch(error => console.error('Error:', error));
}

function mostrarUbicacionEnMapa(latitud, longitud) {
    var mapa = L.map('mapa').setView([latitud, longitud], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(mapa);
    L.marker([latitud, longitud]).addTo(mapa);
}