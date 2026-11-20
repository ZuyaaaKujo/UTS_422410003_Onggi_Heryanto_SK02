<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\Models\{AstronomicalObject,ObservationLocation,ObservationSession,SessionLocationDetail,Equipment,EquipmentCategory};
printf("objects=%d locations=%d equipment=%d categories=%d sessions=%d details=%d pivot=%d\n",
 AstronomicalObject::count(),ObservationLocation::count(),Equipment::count(),EquipmentCategory::count(),ObservationSession::count(),SessionLocationDetail::count(),DB::table('equipment_session')->count());
$s = ObservationSession::with(['astronomicalObject','locationDetail.observationLocation','equipment.category'])->first();
echo "belongsTo object: ".$s->astronomicalObject->name."\n";
echo "hasOne detail: ".get_class($s->locationDetail)." -> location ".$s->locationDetail->observationLocation->name."\n";
echo "detail->session back: ".$s->locationDetail->observationSession->code."\n";
echo "belongsToMany equipment: ".$s->equipment->pluck('name')->implode(' | ')." (pivot qty ".$s->equipment->first()->pivot->quantity_used.")\n";
echo "equipment->sessions: ".Equipment::find(1)->sessions()->count()." sesi; kategori: ".Equipment::find(1)->category->name."\n";
echo "status: "; print_r(ObservationSession::selectRaw('status,count(*) c')->groupBy('status')->pluck('c','status')->all());
echo "availability: "; print_r(Equipment::selectRaw('availability_status s,count(*) c')->groupBy('availability_status')->pluck('c','s')->all());
// One-to-One dijaga UNIQUE:
try { DB::table('session_location_details')->insert(['observation_session_id'=>$s->id,'observation_location_id'=>1,'weather_condition'=>'x','visibility_condition'=>'y','created_at'=>now(),'updated_at'=>now()]); echo "FAIL: duplikat detail lolos\n"; }
catch (Throwable $e) { echo "OK unique one-to-one: ditolak DB\n"; }
// transaksi rollback:
$before = ObservationSession::count();
try { app(App\Services\ObservationSessionService::class)->create(['code'=>'TX-1','name'=>'t','astronomical_object_id'=>1,'observation_date'=>'2026-12-01','start_time'=>'20:00','end_time'=>'22:00','status'=>'scheduled'],['observation_location_id'=>9999,'weather_condition'=>'a','visibility_condition'=>'b'],[]); }
catch (Throwable $e) { echo "OK transaksi gagal -> rollback; sesi sebelum=$before sesudah=".ObservationSession::count()."\n"; }
