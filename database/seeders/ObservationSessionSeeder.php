<?php

namespace Database\Seeders;

use App\Models\AstronomicalObject;
use App\Models\Equipment;
use App\Models\ObservationLocation;
use App\Models\ObservationSession;
use App\Services\ObservationSessionService;
use Illuminate\Database\Seeder;

class ObservationSessionSeeder extends Seeder
{
    public function run(ObservationSessionService $service): void
    {
        $objects = AstronomicalObject::pluck('id', 'code');
        $locations = ObservationLocation::pluck('id', 'code');
        $equipment = Equipment::pluck('id', 'code');

        // [offset hari dari hari ini, nama, objek, lokasi, mulai, selesai, cuaca, visibilitas, suhu, kelembapan, catatan, perlengkapan [kode, jumlah, catatan]]
        $rows = [
            [-150, 'Pengamatan Kawah Bulan Fase Kuartir Pertama', 'OBJ-001', 'LOC-001', '19:00', '21:30', 'Cerah Berawan', 'Baik', 18.5, 78, 'Terminator Bulan jelas pada kawah Copernicus.', [['EQ-004', 1, 'Pengamatan visual'], ['EQ-016', 1, null], ['EQ-007', 1, null]]],
            [-135, 'Survei Cincin Saturnus', 'OBJ-004', 'LOC-002', '20:00', '23:00', 'Cerah', 'Sangat Baik', 16.0, 55, 'Celah Cassini terlihat pada perbesaran 150x.', [['EQ-001', 1, 'Tracking GoTo'], ['EQ-006', 1, null], ['EQ-007', 1, null]]],
            [-120, 'Astrofotografi Nebula Orion', 'OBJ-006', 'LOC-003', '22:00', '02:00', 'Cerah', 'Sangat Baik', 8.0, 60, 'Total 120 frame, exposure 60 detik.', [['EQ-002', 1, 'Teleskop utama'], ['EQ-009', 1, 'Kamera imaging'], ['EQ-011', 1, 'Guiding'], ['EQ-012', 1, null], ['EQ-018', 1, null]]],
            [-105, 'Pengamatan Satelit Galilea Jupiter', 'OBJ-003', 'LOC-004', '20:30', '23:00', 'Cerah Berawan', 'Baik', 6.5, 70, 'Keempat satelit Galilea terlihat berjajar.', [['EQ-003', 1, null], ['EQ-005', 1, null], ['EQ-007', 1, null]]],
            [-92, 'Fotometri Betelgeuse', 'OBJ-010', 'LOC-010', '21:00', '23:30', 'Cerah', 'Cukup', 27.0, 74, 'Pengukuran magnitudo dengan kamera DSLR, polusi cahaya tinggi.', [['EQ-010', 1, null], ['EQ-014', 1, null], ['EQ-015', 1, 'Penjejakan sederhana']]],
            [-80, 'Pencitraan Galaksi Andromeda', 'OBJ-007', 'LOC-005', '21:30', '01:00', 'Cerah', 'Baik', 12.0, 65, 'Lengan spiral terlihat pada hasil stacking.', [['EQ-002', 1, null], ['EQ-009', 1, null], ['EQ-012', 1, null], ['EQ-020', 1, 'Sumber daya']]],
            [-68, 'Pengamatan Visual Pleiades', 'OBJ-008', 'LOC-006', '20:00', '22:00', 'Cerah', 'Sangat Baik', 27.5, 80, 'Nebula refleksi tampak halus pada okuler sudut lebar.', [['EQ-004', 1, null], ['EQ-005', 1, null]]],
            [-55, 'Pengamatan Mars Menjelang Oposisi', 'OBJ-002', 'LOC-001', '21:00', '23:30', 'Hujan Ringan', 'Buruk', 17.0, 92, 'Sesi dibatalkan karena hujan ringan dan awan tebal.', [['EQ-001', 1, null], ['EQ-008', 1, null]]],
            [-45, 'Pencitraan Planet Venus', 'OBJ-005', 'LOC-010', '18:00', '19:30', 'Cerah Berawan', 'Cukup', 28.0, 70, 'Fase sabit Venus terdokumentasi dengan kamera planetari.', [['EQ-001', 1, null], ['EQ-008', 1, 'Perbesaran tinggi'], ['EQ-011', 1, 'Kamera pencitraan cepat']]],
            [-35, 'Survei Gugus Bola M13', 'OBJ-015', 'LOC-009', '23:00', '02:00', 'Cerah', 'Sangat Baik', 8.5, 40, 'Bintang pinggiran gugus terurai pada 200x.', [['EQ-003', 1, null], ['EQ-005', 1, null], ['EQ-006', 1, null]]],
            [-27, 'Pengamatan Nebula Cincin M57', 'OBJ-014', 'LOC-007', '22:00', '00:30', 'Cerah', 'Sangat Baik', 2.0, 30, 'Struktur cincin tampak jelas dengan filter.', [['EQ-001', 1, null], ['EQ-006', 1, null], ['EQ-018', 1, null]]],
            [-19, 'Astrofotografi Galaksi Sombrero', 'OBJ-011', 'LOC-008', '03:00', '06:00', 'Cerah', 'Sangat Baik', 10.0, 25, 'Jalur debu terekam dengan detail tinggi.', [['EQ-002', 1, null], ['EQ-009', 1, null], ['EQ-012', 1, null], ['EQ-011', 1, null]]],
            [-12, 'Pengamatan Sisa Supernova M1', 'OBJ-012', 'LOC-002', '21:00', '00:00', 'Cerah Berawan', 'Baik', 15.5, 58, 'Filamen nebula samar tampak pada teleskop Dobsonian.', [['EQ-003', 1, null], ['EQ-006', 1, null]]],
            [-6, 'Pengamatan Kuartir Akhir Bulan', 'OBJ-001', 'LOC-004', '23:30', '02:00', 'Berawan', 'Cukup', 7.0, 82, 'Pengamatan terhambat awan tipis.', [['EQ-004', 1, null], ['EQ-016', 1, null]]],
            [-2, 'Pengamatan Sirius dan Sirius B', 'OBJ-009', 'LOC-001', '20:00', '22:00', 'Cerah', 'Baik', 18.0, 68, 'Upaya memisahkan Sirius B dari silau Sirius A.', [['EQ-001', 1, null], ['EQ-008', 1, null]]],
            [0, 'Pengamatan Jupiter Malam Ini', 'OBJ-003', 'LOC-003', '19:30', '23:59', 'Cerah', 'Sangat Baik', 9.0, 55, 'Sesi sedang berlangsung. Fokus pada sabuk awan dan Bintik Merah.', [['EQ-003', 1, 'Teleskop utama'], ['EQ-006', 1, null], ['EQ-007', 1, null]]],
            [0, 'Astrofotografi Bima Sakti dan Orion', 'OBJ-006', 'LOC-005', '21:00', '23:59', 'Cerah', 'Baik', 13.0, 62, 'Menggunakan star tracker untuk eksposur panjang.', [['EQ-010', 1, null], ['EQ-015', 1, null], ['EQ-014', 1, null]]],
            [3, 'Pengamatan Mars Pasca-Oposisi', 'OBJ-002', 'LOC-006', '20:00', '22:30', 'Cerah', 'Baik', 27.0, 78, 'Persiapan observasi detail kutub Mars.', [['EQ-001', 1, null], ['EQ-007', 1, null]]],
            [8, 'Pencitraan Nebula Kepiting', 'OBJ-012', 'LOC-009', '22:00', '01:00', 'Cerah', 'Baik', 7.0, 45, 'Jadwal sesi astrofotografi narrowband.', [['EQ-002', 1, null], ['EQ-009', 1, null], ['EQ-012', 1, null]]],
            [15, 'Survei Galaksi Whirlpool', 'OBJ-013', 'LOC-002', '22:30', '01:30', 'Cerah', 'Baik', 15.0, 50, 'Target: interaksi M51 dan NGC 5195.', [['EQ-003', 1, null], ['EQ-005', 1, null]]],
            [21, 'Edukasi Publik: Bulan dan Planet', 'OBJ-001', 'LOC-010', '18:30', '21:00', 'Cerah Berawan', 'Cukup', 28.0, 72, 'Sesi pengamatan untuk masyarakat umum.', [['EQ-004', 2, 'Dua teleskop untuk pengunjung'], ['EQ-016', 2, null]]],
        ];

        foreach ($rows as $i => [$offset, $name, $objCode, $locCode, $start, $end, $weather, $vis, $temp, $hum, $notes, $gear]) {
            $status = match (true) {
                $i === 7 => 'cancelled',
                $offset < 0 => 'completed',
                $offset === 0 => 'active',
                default => 'scheduled',
            };

            $equipmentData = [];
            foreach ($gear as [$eqCode, $qty, $usage]) {
                $equipmentData[$equipment[$eqCode]] = ['quantity_used' => $qty, 'usage_notes' => $usage];
            }

            $location = ObservationLocation::find($locations[$locCode]);

            $service->create([
                'code' => sprintf('OBS-2026-%03d', $i + 1),
                'name' => $name,
                'astronomical_object_id' => $objects[$objCode],
                'observation_date' => now()->addDays($offset)->toDateString(),
                'start_time' => $start,
                'end_time' => $end,
                'status' => $status,
                'description' => "Sesi pengamatan {$name} di {$location->name}.",
                'notes' => $notes,
            ], [
                'observation_location_id' => $locations[$locCode],
                'weather_condition' => $weather,
                'visibility_condition' => $vis,
                'temperature' => $temp,
                'humidity' => $hum,
                'light_pollution_level' => $location->light_pollution_level,
                'environment_notes' => $notes,
            ], $equipmentData);
        }
    }
}
