/* Grafik dashboard (Chart.js) — data berasal dari database melalui window.ASTRA_CHARTS */
(function () {
    if (typeof Chart === 'undefined' || !window.ASTRA_CHARTS) return;
    var charts = [];
    function palette() {
        var light = document.documentElement.getAttribute('data-theme') === 'light';
        return { text: light ? '#55608a' : '#8f9cc6', grid: light ? 'rgba(30,60,140,.1)' : 'rgba(125,165,255,.1)' };
    }
    function build() {
        charts.forEach(function (c) { c.destroy(); }); charts = [];
        var p = palette(), a = window.ASTRA_CHARTS.activity, d = window.ASTRA_CHARTS.distribution;
        Chart.defaults.font.family = "'Space Grotesk', sans-serif"; Chart.defaults.color = p.text;

        var ctx = document.getElementById('activityChart');
        if (ctx) {
            var g = ctx.getContext('2d').createLinearGradient(0, 0, 0, 290);
            g.addColorStop(0, 'rgba(34,211,238,.9)'); g.addColorStop(1, 'rgba(139,92,246,.35)');
            charts.push(new Chart(ctx, {
                type: 'bar',
                data: { labels: a.labels, datasets: [
                    { type: 'bar', label: 'Total sesi', data: a.total, backgroundColor: g, borderRadius: 8, maxBarThickness: 38 },
                    { type: 'line', label: 'Selesai', data: a.completed, borderColor: '#34d399', backgroundColor: '#34d399', tension: .35, pointRadius: 4 }
                ] },
                options: { responsive: true, maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: p.grid } }, x: { grid: { display: false } } },
                    plugins: { legend: { labels: { usePointStyle: true } } } }
            }));
        }
        var cat = document.getElementById('categoryChart');
        if (cat) {
            charts.push(new Chart(cat, {
                type: 'doughnut',
                data: { labels: d.labels, datasets: [{ data: d.values, borderWidth: 0, backgroundColor: ['#22d3ee', '#8b5cf6', '#ec4899', '#3b82f6', '#fbbf24', '#34d399', '#f87171'] }] },
                options: { responsive: true, maintainAspectRatio: false, cutout: '64%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } } }
            }));
        }
    }
    build();
    window.addEventListener('astra-theme', build);
})();
