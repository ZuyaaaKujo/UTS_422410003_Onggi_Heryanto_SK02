<?php

namespace Database\Seeders;

use App\Models\ObservationLocation;
use Illuminate\Database\Seeder;

class ObservationLocationSeeder extends Seeder
{
    public function run(): void
    {
        // Koordinat dan elevasi dibulatkan; tingkat polusi cahaya (skala Bortle) merupakan estimasi.
        $locations = [
            ['LOC-001', 'Observatorium Bosscha', 'Indonesia', 'Jawa Barat', 'Lembang', -6.8247, 107.6168, 1310, 'Pinggiran Kota', 6,
                'Observatorium astronomi tertua di Indonesia, berdiri sejak 1923 di Lembang.'],
            ['LOC-002', 'Observatorium Nasional Timau', 'Indonesia', 'Nusa Tenggara Timur', 'Kupang', -9.5800, 124.3800, 1300, 'Sangat Gelap', 2,
                'Lokasi observatorium nasional di dataran tinggi Timau dengan langit sangat gelap.'],
            ['LOC-003', 'Gunung Bromo (Penanjakan)', 'Indonesia', 'Jawa Timur', 'Probolinggo', -7.9425, 112.9530, 2329, 'Gelap', 3,
                'Kawasan Taman Nasional Bromo Tengger Semeru, ideal untuk astrofotografi Bima Sakti.'],
            ['LOC-004', 'Dataran Tinggi Dieng', 'Indonesia', 'Jawa Tengah', 'Wonosobo', -7.2000, 109.9100, 2000, 'Dataran Tinggi Cerah', 4,
                'Dataran tinggi dengan udara kering pada musim kemarau.'],
            ['LOC-005', 'Gunung Batur', 'Indonesia', 'Bali', 'Kintamani', -8.2428, 115.3750, 1717, 'Gelap', 4,
                'Puncak kaldera dengan cakrawala terbuka ke arah timur dan selatan.'],
            ['LOC-006', 'Pulau Pari', 'Indonesia', 'DKI Jakarta', 'Kepulauan Seribu', -5.8667, 106.6167, 3, 'Pesisir Cerah', 3,
                'Pulau kecil di Kepulauan Seribu dengan cakrawala laut yang luas dan sedikit polusi cahaya.'],
            ['LOC-007', 'Mauna Kea Observatories', 'Amerika Serikat', 'Hawaii', 'Hilo', 19.8207, -155.4681, 4205, 'Sangat Gelap', 1,
                'Kompleks observatorium internasional di puncak Mauna Kea.'],
            ['LOC-008', 'ESO Paranal Observatory', 'Chile', 'Antofagasta', 'Taltal', -24.6272, -70.4042, 2635, 'Sangat Gelap', 1,
                'Lokasi Very Large Telescope milik European Southern Observatory di Gurun Atacama.'],
            ['LOC-009', 'Observatorio del Teide', 'Spanyol', 'Kepulauan Canary', 'Tenerife', 28.3000, -16.5100, 2390, 'Sangat Gelap', 2,
                'Observatorium di lereng Gunung Teide dengan langit jernih di atas lapisan awan.'],
            ['LOC-010', 'Planetarium dan Observatorium Jakarta', 'Indonesia', 'DKI Jakarta', 'Jakarta Pusat', -6.1893, 106.8383, 8, 'Perkotaan', 9,
                'Fasilitas edukasi di pusat kota; cocok untuk Bulan dan planet terang meskipun polusi cahaya sangat tinggi.'],
        ];

        foreach ($locations as [$code, $name, $country, $province, $city, $lat, $lng, $elev, $sky, $bortle, $desc]) {
            ObservationLocation::updateOrCreate(['code' => $code], [
                'name' => $name, 'country' => $country, 'province' => $province, 'city' => $city,
                'latitude' => $lat, 'longitude' => $lng, 'elevation' => $elev,
                'sky_condition' => $sky, 'light_pollution_level' => $bortle, 'description' => $desc,
            ]);
        }
    }
}
