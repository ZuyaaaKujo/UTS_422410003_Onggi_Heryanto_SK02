<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Teleskop' => 'Teleskop optik utama: refraktor, reflektor, katadioptrik, dan Dobsonian.',
            'Lensa & Okuler' => 'Okuler dan lensa Barlow untuk mengubah perbesaran.',
            'Kamera & Pencitraan' => 'Kamera astrofotografi dan kamera pemandu.',
            'Mount & Tripod' => 'Mount, tripod, dan star tracker untuk penjejakan benda langit.',
            'Filter' => 'Filter Bulan, Matahari, dan filter polusi cahaya.',
            'Aksesoris Pendukung' => 'Perangkat pendukung seperti sumber daya dan pemanas embun.',
        ];

        $ids = [];
        foreach ($categories as $name => $description) {
            $ids[$name] = EquipmentCategory::updateOrCreate(['name' => $name], ['description' => $description])->id;
        }

        $items = [
            ['EQ-001', 'Optical Telescope Schmidt-Cassegrain 8"', 'Teleskop', 'Celestron', 'NexStar 8SE', 1, 'excellent', 'available', 'Teleskop 203 mm dengan mount GoTo altazimuth.'],
            ['EQ-002', 'Refractor Telescope 80 mm ED', 'Teleskop', 'Sky-Watcher', 'Evostar 80ED', 2, 'excellent', 'available', 'Refraktor apokromatik 80 mm untuk visual dan astrofotografi.'],
            ['EQ-003', 'Dobsonian Telescope 10"', 'Teleskop', 'Sky-Watcher', 'Skyliner 250PX', 1, 'good', 'available', 'Dobsonian 254 mm untuk objek redup (deep sky).'],
            ['EQ-004', 'Reflector Telescope 130 mm', 'Teleskop', 'Celestron', 'AstroMaster 130EQ', 3, 'good', 'available', 'Reflektor Newtonian untuk pembelajaran dan pengamatan publik.'],
            ['EQ-005', 'Eyepiece 18 mm 82°', 'Lensa & Okuler', 'Explore Scientific', '82° 18mm', 4, 'excellent', 'available', 'Okuler sudut pandang lebar.'],
            ['EQ-006', 'Eyepiece Zoom 8-24 mm', 'Lensa & Okuler', 'Baader', 'Hyperion Mark IV', 3, 'good', 'available', 'Okuler zoom serbaguna untuk planet dan deep sky.'],
            ['EQ-007', 'Barlow Lens 2x', 'Lensa & Okuler', 'Celestron', 'X-Cel LX 2x', 3, 'good', 'available', 'Lensa Barlow 2x, mengalikan perbesaran okuler.'],
            ['EQ-008', 'Barlow Lens 2,5x', 'Lensa & Okuler', 'Tele Vue', 'Powermate 2.5x', 1, 'excellent', 'available', 'Pengganda fokus untuk pencitraan planet.'],
            ['EQ-009', 'Camera Astrophotography Color', 'Kamera & Pencitraan', 'ZWO', 'ASI294MC Pro', 2, 'excellent', 'available', 'Kamera CMOS berpendingin untuk deep sky.'],
            ['EQ-010', 'Camera DSLR Full-Frame', 'Kamera & Pencitraan', 'Canon', 'EOS 6D Mark II', 2, 'good', 'available', 'DSLR untuk astrofotografi lanskap dan Bima Sakti.'],
            ['EQ-011', 'Guide Camera Monokrom', 'Kamera & Pencitraan', 'ZWO', 'ASI120MM Mini', 2, 'good', 'available', 'Kamera pemandu untuk autoguiding.'],
            ['EQ-012', 'Telescope Mount Equatorial', 'Mount & Tripod', 'Sky-Watcher', 'EQ6-R Pro', 2, 'excellent', 'available', 'Mount ekuatorial GoTo untuk beban berat.'],
            ['EQ-013', 'Telescope Mount GoTo', 'Mount & Tripod', 'Celestron', 'CGEM II', 1, 'needs_repair', 'maintenance', 'Sedang diperbaiki: motor deklinasi perlu diganti.'],
            ['EQ-014', 'Tripod Kayu Heavy Duty', 'Mount & Tripod', 'Manfrotto', '055', 3, 'good', 'available', 'Tripod untuk kamera dan teleskop kecil.'],
            ['EQ-015', 'Star Tracker Portable', 'Mount & Tripod', 'Sky-Watcher', 'Star Adventurer 2i', 2, 'excellent', 'available', 'Penjejak bintang portabel untuk lensa kamera.'],
            ['EQ-016', 'Filter Lunar ND 13%', 'Filter', 'Celestron', 'Moon Filter 1.25"', 3, 'good', 'available', 'Filter netral untuk mengurangi silau Bulan.'],
            ['EQ-017', 'Filter Solar Safety Film', 'Filter', 'Baader', 'AstroSolar ND 5.0', 2, 'excellent', 'available', 'Film filter surya putih untuk pengamatan Matahari yang aman.'],
            ['EQ-018', 'Filter Light Pollution', 'Filter', 'Optolong', 'L-Pro', 2, 'good', 'available', 'Filter untuk mengurangi polusi cahaya pada astrofotografi.'],
            ['EQ-019', 'Dew Heater Strip', 'Aksesoris Pendukung', 'Celestron', 'Dew Heater 8"', 2, 'fair', 'maintenance', 'Kabel pemanas sedang diperiksa karena konektor longgar.'],
            ['EQ-020', 'Power Tank Lithium', 'Aksesoris Pendukung', 'Celestron', 'PowerTank Lithium LT', 3, 'good', 'available', 'Baterai 12 V portabel untuk mount dan kamera.'],
        ];

        foreach ($items as [$code, $name, $cat, $brand, $model, $qty, $cond, $avail, $desc]) {
            Equipment::updateOrCreate(['code' => $code], [
                'name' => $name, 'equipment_category_id' => $ids[$cat], 'brand' => $brand, 'model' => $model,
                'quantity' => $qty, 'condition' => $cond, 'availability_status' => $avail, 'description' => $desc,
            ]);
        }
    }
}
