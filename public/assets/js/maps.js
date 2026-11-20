/* Peta lokasi pengamatan (Leaflet + OpenStreetMap) */
(function () {
    var el = document.getElementById('locationsMap');
    if (!el) return;
    var points = JSON.parse(el.dataset.points || '[]');

    if (typeof L === 'undefined') {
        el.innerHTML = '<div class="map-fallback"><i class="bi bi-wifi-off fs-1"></i><p class="mb-0 mt-2">Peta tidak dapat dimuat (periksa koneksi internet).<br>' +
            points.map(function (p) { return p.name + ': ' + p.lat.toFixed(4) + ', ' + p.lng.toFixed(4); }).join('<br>') + '</p></div>';
        return;
    }
    var map = L.map(el, { scrollWheelZoom: false }).setView([points[0] ? points[0].lat : -2, points[0] ? points[0].lng : 118], parseInt(el.dataset.zoom || '2', 10));
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

    function color(b) { return b <= 2 ? '#34d399' : b <= 4 ? '#22d3ee' : b <= 6 ? '#fbbf24' : '#f87171'; }
    var bounds = [];
    points.forEach(function (p) {
        var m = L.circleMarker([p.lat, p.lng], { radius: 9, color: '#fff', weight: 2, fillColor: color(p.bortle), fillOpacity: .95 }).addTo(map);
        var safe = document.createElement('div'); safe.textContent = p.name; // hindari injeksi HTML
        m.bindPopup('<strong>' + safe.innerHTML + '</strong><br>Bortle ' + p.bortle + (p.url ? '<br><a href="' + p.url + '">Lihat detail</a>' : ''));
        bounds.push([p.lat, p.lng]);
    });
    if (bounds.length > 1) { map.fitBounds(bounds, { padding: [30, 30] }); }
    map.on('focus', function () { map.scrollWheelZoom.enable(); }); map.on('blur', function () { map.scrollWheelZoom.disable(); });
})();
