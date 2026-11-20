/* ASTRA OBSERVATORY — perilaku UI global */
(function () {
    'use strict';
    var root = document.documentElement;

    // Loading animation
    var loader = document.getElementById('page-loader');
    window.addEventListener('load', function () { loader && loader.classList.add('is-hidden'); });
    window.addEventListener('pageshow', function () { loader && loader.classList.add('is-hidden'); });
    document.addEventListener('submit', function (e) {
        if (!e.defaultPrevented && e.target.method && e.target.method.toLowerCase() !== 'dialog') { loader && loader.classList.remove('is-hidden'); }
    });

    // Tema gelap/terang
    var toggle = document.getElementById('theme-toggle');
    function syncIcon() {
        if (!toggle) return;
        var light = root.getAttribute('data-theme') === 'light';
        toggle.innerHTML = '<i class="bi ' + (light ? 'bi-moon-stars' : 'bi-brightness-high') + '"></i>';
        window.dispatchEvent(new Event('astra-theme'));
    }
    syncIcon();
    toggle && toggle.addEventListener('click', function () {
        var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        root.setAttribute('data-theme', next); root.setAttribute('data-bs-theme', next);
        try { localStorage.setItem('astra-theme', next); } catch (e) {}
        syncIcon();
    });

    // Jam topbar (berdasarkan waktu server)
    var clock = document.getElementById('clock-time');
    if (clock) {
        var offset = new Date(clock.dataset.now).getTime() - Date.now();
        setInterval(function () {
            var d = new Date(Date.now() + offset);
            clock.textContent = d.toLocaleTimeString('id-ID', { hour12: false, timeZone: 'Asia/Jakarta' }).replace(/\./g, ':');
        }, 1000);
    }

    // Toast
    document.querySelectorAll('.astra-toast').forEach(function (el) { new bootstrap.Toast(el).show(); });

    // Modal konfirmasi hapus
    var modalEl = document.getElementById('deleteModal');
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-delete-url]');
            if (!btn) return;
            document.getElementById('deleteForm').setAttribute('action', btn.dataset.deleteUrl);
            document.getElementById('deleteModalName').textContent = '"' + btn.dataset.deleteName + '"';
            modal.show();
        });
    }

    // Fallback gambar bila URL eksternal gagal dimuat
    document.addEventListener('error', function (e) {
        var img = e.target;
        if (img.tagName === 'IMG' && img.dataset.fallback && img.src !== img.dataset.fallback) { img.src = img.dataset.fallback; }
    }, true);

    // Hitung naik pada kartu statistik
    document.querySelectorAll('[data-count]').forEach(function (el) {
        var target = parseInt(el.dataset.count, 10);
        if (isNaN(target) || target === 0 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        var start = null;
        function step(ts) {
            start = start || ts;
            var p = Math.min((ts - start) / 700, 1);
            el.textContent = Math.round(target * p);
            if (p < 1) requestAnimationFrame(step);
        }
        el.textContent = '0'; requestAnimationFrame(step);
    });

    // Pemilih perlengkapan (Many-to-Many)
    var counter = document.getElementById('eqSelectedCount');
    function refreshEq() { if (counter) counter.textContent = document.querySelectorAll('.eq-check:checked').length; }
    document.querySelectorAll('.eq-check').forEach(function (cb) {
        cb.addEventListener('change', function () { cb.closest('.eq-pick').classList.toggle('is-selected', cb.checked); refreshEq(); });
    });
    refreshEq();
})();
