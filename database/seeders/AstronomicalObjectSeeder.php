<?php

namespace Database\Seeders;

use App\Models\AstronomicalObject;
use Illuminate\Database\Seeder;

class AstronomicalObjectSeeder extends Seeder
{
    public function run(): void
    {
        $objects = [
            ['OBJ-001', 'Bulan', 'natural_satellite', null,
                'Satu-satunya satelit alami Bumi dan objek langit paling mudah diamati, cocok untuk pengamatan kawah, mare, dan terminator.',
                'Diameter sekitar 3.474 km, jarak rata-rata dari Bumi sekitar 384.400 km. Satu siklus fase berlangsung sekitar 29,5 hari.'],
            ['OBJ-002', 'Mars', 'planet', null,
                'Planet merah dengan kutub es, gunung berapi raksasa Olympus Mons, dan dua satelit kecil, Phobos dan Deimos.',
                'Diameter sekitar 6.779 km. Satu hari Mars (sol) berlangsung sekitar 24 jam 37 menit. Oposisi terjadi kira-kira setiap 26 bulan.'],
            ['OBJ-003', 'Jupiter', 'planet', null,
                'Planet terbesar di Tata Surya. Sabuk awan dan empat satelit Galilea (Io, Europa, Ganymede, Callisto) terlihat bahkan dengan teleskop kecil.',
                'Diameter ekuator sekitar 139.820 km. Bintik Merah Raksasa adalah badai raksasa yang telah diamati selama berabad-abad.'],
            ['OBJ-004', 'Saturnus', 'planet', null,
                'Planet bercincin paling ikonik. Sistem cincinnya tampak jelas dengan teleskop 60 mm ke atas, begitu pula satelit terbesarnya, Titan.',
                'Diameter ekuator sekitar 116.460 km. Kemiringan cincin terhadap Bumi berubah sepanjang siklus orbit sekitar 29 tahun.'],
            ['OBJ-005', 'Venus', 'planet', null,
                'Planet paling terang di langit setelah Bulan. Terlihat sebagai "bintang kejora" atau "bintang senja" dan menunjukkan fase seperti Bulan.',
                'Suhu permukaan sekitar 465 °C akibat efek rumah kaca yang ekstrem. Venus berotasi retrograd dengan sangat lambat.'],
            ['OBJ-006', 'Orion Nebula (M42)', 'nebula', 'Orion',
                'Nebula emisi terang di "pedang" Orion, wilayah pembentukan bintang aktif yang terlihat dengan mata telanjang pada langit gelap.',
                'Berjarak sekitar 1.350 tahun cahaya. Gugus Trapezium di jantungnya menyinari gas di sekitarnya.'],
            ['OBJ-007', 'Andromeda Galaxy (M31)', 'galaxy', 'Andromeda',
                'Galaksi spiral besar terdekat dengan Bima Sakti dan objek terjauh yang dapat dilihat dengan mata telanjang.',
                'Berjarak sekitar 2,5 juta tahun cahaya. Galaksi ini bergerak mendekati Bima Sakti dan diperkirakan bertabrakan beberapa miliar tahun lagi.'],
            ['OBJ-008', 'Pleiades (M45)', 'star_cluster', 'Taurus',
                'Gugus bintang terbuka yang dikenal sebagai "Tujuh Bersaudara", dengan bintang-bintang biru panas yang dikelilingi nebula refleksi.',
                'Berjarak sekitar 444 tahun cahaya, dengan usia diperkirakan sekitar 100 juta tahun.'],
            ['OBJ-009', 'Sirius', 'star', 'Canis Major',
                'Bintang paling terang di langit malam, sistem bintang ganda dengan pendamping katai putih Sirius B.',
                'Magnitudo tampak sekitar -1,46, berjarak sekitar 8,6 tahun cahaya dari Bumi.'],
            ['OBJ-010', 'Betelgeuse', 'star', 'Orion',
                'Bintang maharaksasa merah di bahu Orion, dikenal karena kecerahannya yang berubah-ubah (bintang variabel).',
                'Berjarak sekitar 550-700 tahun cahaya. Pada akhir 2019-2020 mengalami peredupan besar (Great Dimming).'],
            ['OBJ-011', 'Sombrero Galaxy (M104)', 'galaxy', 'Virgo',
                'Galaksi dengan tonjolan pusat terang dan jalur debu gelap yang menyerupai topi sombrero.',
                'Berjarak sekitar 29 juta tahun cahaya. Jalur debunya terlihat jelas pada foto yang diambil teleskop luar angkasa.'],
            ['OBJ-012', 'Crab Nebula (M1)', 'nebula', 'Taurus',
                'Sisa supernova yang dicatat astronom Tiongkok pada tahun 1054 M, mengandung pulsar di pusatnya.',
                'Berjarak sekitar 6.500 tahun cahaya dan masih mengembang sampai sekarang.'],
            ['OBJ-013', 'Whirlpool Galaxy (M51)', 'galaxy', 'Canes Venatici',
                'Galaksi spiral dengan lengan yang tampak jelas, sedang berinteraksi dengan galaksi pendamping NGC 5195.',
                'Berjarak sekitar 23 juta tahun cahaya.'],
            ['OBJ-014', 'Ring Nebula (M57)', 'nebula', 'Lyra',
                'Nebula planetari berbentuk cincin, sisa lapisan gas bintang yang mati. Cocok untuk menguji teleskop kecil.',
                'Berjarak sekitar 2.300 tahun cahaya dengan diameter sudut sekitar 1 menit busur.'],
            ['OBJ-015', 'Great Hercules Cluster (M13)', 'star_cluster', 'Hercules',
                'Gugus bola paling terang di langit utara, berisi ratusan ribu bintang tua yang rapat.',
                'Berjarak sekitar 22.000 tahun cahaya dengan magnitudo tampak sekitar 5,8.'],
            ['OBJ-016', 'Hujan Meteor Perseid', 'phenomenon', 'Perseus',
                'Hujan meteor tahunan yang berasal dari puing komet 109P/Swift-Tuttle, dengan puncak sekitar 12-13 Agustus.',
                'Radian berada di rasi Perseus. Pengamatan terbaik dilakukan di langit gelap tanpa teleskop.'],
        ];

        foreach ($objects as [$code, $name, $category, $constellation, $description, $info]) {
            AstronomicalObject::updateOrCreate(['code' => $code], [
                'name' => $name, 'category' => $category, 'constellation' => $constellation,
                'description' => $description, 'astronomical_info' => $info,
            ]);
        }
    }
}
