import re, urllib.request, urllib.parse, http.cookiejar, sys
B='http://127.0.0.1:8000'
cj=http.cookiejar.CookieJar()
class NoRedir(urllib.request.HTTPRedirectHandler):
    def redirect_request(self,*a,**k): return None
op=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))
opn=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj),NoRedir)
res=[]
def ok(name,cond,extra=''):
    res.append(cond); print(('PASS ' if cond else 'FAIL ')+name+(' '+extra if (extra and not cond) else ''))
def get(path):
    try:
        r=op.open(B+path); return r.status,r.read().decode(),r.url
    except urllib.error.HTTPError as e: return e.code,e.read().decode(),path
def token(html): return re.search(r'name="_token" value="([^"]+)"',html).group(1)
def post(path,data,tok_from=None,method=None):
    if tok_from is None: tok_from=path
    d=dict(data); d['_token']=token(get(tok_from)[1])
    if method: d['_method']=method
    body=urllib.parse.urlencode(d,doseq=True).encode()
    try:
        r=opn.open(urllib.request.Request(B+path,body,{'Referer':B+tok_from})); return r.status,r.read().decode(),None
    except urllib.error.HTTPError as e:
        return e.code,e.read().decode(),e.headers.get('Location')

# 1. Semua halaman GET
for p in ['/','/sessions','/sessions/1','/sessions/create','/sessions/1/edit','/astronomical-objects','/astronomical-objects/1','/astronomical-objects/create','/astronomical-objects/1/edit',
          '/locations','/locations/1','/locations/create','/locations/1/edit','/equipment','/equipment/1','/equipment/create','/equipment/1/edit',
          '/equipment-categories','/equipment-categories/create','/equipment-categories/1/edit','/search?q=orion','/search?q=zzzzqq','/sessions?page=2','/sessions/export']:
    s,h,_=get(p); ok('GET '+p,s==200 and 'Exception' not in h[:3000] and 'Whoops' not in h[:3000],str(s))
s,h,_=get('/'); ok('dashboard memuat data DB (OBS-2026)','OBS-2026-' in h and 'Orion' in h or 'Jupiter' in h)
ok('dashboard punya chart data','"labels"' in h)
# 2. Pencarian & filter
s,h,_=get('/sessions?search=OBS-2026-005'); ok('search kode',"OBS-2026-005" in h and 'OBS-2026-006' not in h)
s,h,_=get('/sessions?search=Saturnus'); ok('search nama tak ada hasil nama lain', 'Cincin Saturnus' in h)
s,h,_=get('/sessions?status=cancelled'); ok('filter status',h.count('OBS-2026-')>=1 and 'Dibatalkan' in h and 'Pengamatan Mars Menjelang' in h and 'Survei Cincin' not in h)
s,h,_=get('/sessions?object=4'); ok('filter objek (Saturnus)','Survei Cincin Saturnus' in h and 'Pengamatan Mars Menjelang' not in h)
s,h,_=get('/sessions?date=1999-01-01'); ok('filter tanggal kosong -> empty state','Tidak ada sesi ditemukan' in h)
s,h,_=get('/astronomical-objects?category=galaxy'); ok('filter kategori objek','Andromeda' in h and 'Jupiter' not in h)
s,h,_=get('/astronomical-objects?search=orion'); ok('search objek','Orion Nebula' in h and 'Sirius' not in h)
s,h,_=get('/locations?bortle=dark'); ok('filter bortle gelap','LOC-007' in h and 'LOC-010' not in h and 'LOC-001' not in h)
s,h,_=get('/locations?country=Chile'); ok('filter negara','LOC-008' in h and 'LOC-001' not in h)
s,h,_=get('/equipment?category=5'); ok('filter kategori equipment','Filter Lunar' in h and 'Dobsonian' not in h)
s,h,_=get('/equipment?availability=maintenance'); ok('filter ketersediaan','CGEM' in h or 'GoTo' in h)
s,h,_=get('/equipment?condition=needs_repair'); ok('filter kondisi','EQ-013' in h and 'EQ-001' not in h)
# 3. Validasi
s,h,loc=post('/sessions',{'code':'','name':'','status':'x'},'/sessions/create'); ok('validasi sesi -> redirect balik',s==302 and loc.endswith('/sessions/create'),str((s,loc)))
s,h,_=get('/sessions/create'); ok('pesan error tampil','field' in h.lower() or 'wajib' in h.lower() or 'harus' in h.lower() or 'is-invalid' in h)
# 4. CREATE sesi + one-to-one + many-to-many
data={'code':'OBS-2026-900','name':'Uji Sesi E2E','astronomical_object_id':'3','observation_date':'2026-12-24','start_time':'20:00','end_time':'23:00','status':'scheduled','description':'d','notes':'n',
 'location[observation_location_id]':'3','location[weather_condition]':'Cerah','location[visibility_condition]':'Baik','location[temperature]':'10.5','location[humidity]':'60','location[light_pollution_level]':'3','location[environment_notes]':'angin tenang',
 'equipment[1][selected]':'1','equipment[1][quantity_used]':'1','equipment[1][usage_notes]':'utama','equipment[9][selected]':'1','equipment[9][quantity_used]':'2','equipment[9][usage_notes]':'kamera'}
s,h,loc=post('/sessions',data,'/sessions/create'); ok('store sesi',s==302 and '/sessions/' in (loc or '') and not loc.endswith('/create'),str((s,loc)))
sid=loc.rstrip('/').split('/')[-1]
s,h,_=get('/sessions/'+sid); ok('show sesi baru: detail lokasi + pivot','angin tenang' in h and 'kamera' in h and 'Uji Sesi E2E' in h and 'Berhasil' not in 'x')
# stok melebihi
d2=dict(data); d2['code']='OBS-2026-901'; d2['equipment[9][quantity_used]']='99'
s,h,loc=post('/sessions',d2,'/sessions/create'); ok('validasi stok melebihi -> ditolak',s==302 and loc.endswith('/create'),str((s,loc)))
# perlengkapan maintenance ditolak
d3=dict(data); d3['code']='OBS-2026-902'; d3['equipment[13][selected]']='1'; d3['equipment[13][quantity_used]']='1'
s,h,loc=post('/sessions',d3,'/sessions/create'); ok('perlengkapan maintenance ditolak',s==302 and loc.endswith('/create'),str((s,loc)))
# kode duplikat
d4=dict(data); s,h,loc=post('/sessions',d4,'/sessions/create'); ok('kode unik ditolak',s==302 and loc.endswith('/create'),str((s,loc)))
# UPDATE: ganti perlengkapan & lokasi
upd=dict(data); upd['name']='Uji Sesi E2E (edit)'; upd['status']='active'; upd['location[observation_location_id]']='4'
for k in list(upd):
    if k.startswith('equipment[9]'): del upd[k]
upd['equipment[3][selected]']='1'; upd['equipment[3][quantity_used]']='1'
s,h,loc=post('/sessions/'+sid,upd,'/sessions/'+sid+'/edit',method='PUT'); ok('update sesi',s==302,str((s,loc)))
s,h,_=get('/sessions/'+sid); ok('update tercermin (nama, lokasi baru, equipment sync)','(edit)' in h and 'Dieng' in h and 'Dobsonian' in h and 'Camera Astrophotography' not in h)
s,h,_=get('/equipment/3'); ok('availability: equipment di sesi aktif = in_use','Sedang Digunakan' in h)
s,h,_=get('/equipment/9'); ok('availability: equipment dilepas = tersedia','Tersedia' in h)
# DELETE sesi
s,h,loc=post('/sessions/'+sid,{},'/sessions',method='DELETE'); ok('delete sesi',s==302)
s,h,_=get('/sessions/'+sid); ok('sesi terhapus -> 404',s==404)
s,h,_=get('/equipment/9'); ok('availability kembali setelah delete', 'Sedang Digunakan' not in h)
# 5. CRUD objek
od={'code':'OBJ-900','name':'Uji Objek','category':'planet','constellation':'','description':'x','astronomical_info':'y','image_url':''}
s,h,loc=post('/astronomical-objects',od,'/astronomical-objects/create'); ok('store objek',s==302 and not loc.endswith('/create'),str((s,loc))); oid=loc.rstrip('/').split('/')[-1]
s,h,loc=post('/astronomical-objects',dict(od,image_url='bukan-url'),'/astronomical-objects/create'); ok('validasi image_url / kode duplikat',s==302 and loc.endswith('/create'))
s,h,loc=post('/astronomical-objects/'+oid,dict(od,name='Uji Objek 2'),'/astronomical-objects/'+oid+'/edit',method='PUT'); ok('update objek',s==302)
s,h,_=get('/astronomical-objects/'+oid); ok('objek ter-update','Uji Objek 2' in h)
s,h,loc=post('/astronomical-objects/1',{},'/astronomical-objects',method='DELETE'); s,h,_=get('/astronomical-objects'); ok('pesan error hapus berelasi','masih dipakai' in h); s2,h2,_=get('/astronomical-objects/1'); ok('hapus objek berelasi ditolak (masih ada)',s2==200)
s,h,loc=post('/astronomical-objects/'+oid,{},'/astronomical-objects',method='DELETE'); s2,_,_=get('/astronomical-objects/'+oid); ok('hapus objek bebas relasi',s2==404)
# 6. CRUD lokasi
ld={'code':'LOC-900','name':'Uji Lokasi','country':'Indonesia','province':'P','city':'C','latitude':'-6.2','longitude':'106.8','elevation':'100','sky_condition':'Gelap','light_pollution_level':'4','description':''}
s,h,loc=post('/locations',ld,'/locations/create'); ok('store lokasi',s==302 and not loc.endswith('/create'),str((s,loc))); lid=loc.rstrip('/').split('/')[-1]
s,h,loc=post('/locations',dict(ld,code='LOC-901',latitude='200',light_pollution_level='12'),'/locations/create'); ok('validasi koordinat/bortle',s==302 and loc.endswith('/create'))
s,h,_=post('/locations/'+lid,dict(ld,name='Uji Lokasi 2'),'/locations/'+lid+'/edit',method='PUT'); s,h,_=get('/locations/'+lid); ok('update lokasi','Uji Lokasi 2' in h and 'locationsMap' in h)
s,h,_=post('/locations/1',{},'/locations',method='DELETE'); s2,_,_=get('/locations/1'); ok('hapus lokasi berelasi ditolak',s2==200)
s,h,_=post('/locations/'+lid,{},'/locations',method='DELETE'); s2,_,_=get('/locations/'+lid); ok('hapus lokasi bebas relasi',s2==404)
# 7. CRUD equipment & kategori
cd={'name':'Kategori Uji','description':'d'}
s,h,loc=post('/equipment-categories',cd,'/equipment-categories/create'); ok('store kategori',s==302)
s,h,_=get('/equipment-categories?search=Uji'); ok('search kategori','Kategori Uji' in h); cid=re.search(r'equipment-categories/(\d+)/edit',h).group(1)
s,h,loc=post('/equipment-categories',cd,'/equipment-categories/create'); ok('kategori duplikat ditolak',loc and loc.endswith('/create'))
ed={'code':'EQ-900','name':'Uji Alat','equipment_category_id':cid,'brand':'B','model':'M','quantity':'2','condition':'good','availability_status':'available','description':''}
s,h,loc=post('/equipment',ed,'/equipment/create'); ok('store equipment',s==302 and not loc.endswith('/create'),str((s,loc))); eid=loc.rstrip('/').split('/')[-1]
s,h,loc=post('/equipment',dict(ed,code='EQ-901',condition='rusak',quantity='-1'),'/equipment/create'); ok('validasi equipment',loc and loc.endswith('/create'))
s,h,_=post('/equipment/'+eid,dict(ed,name='Uji Alat 2'),'/equipment/'+eid+'/edit',method='PUT'); s,h,_=get('/equipment/'+eid); ok('update equipment','Uji Alat 2' in h)
s,h,_=post('/equipment-categories/'+cid,{},'/equipment-categories',method='DELETE'); s2,h2,_=get('/equipment-categories?search=Uji'); ok('hapus kategori berisi ditolak','Kategori Uji' in h2)
s,h,_=post('/equipment/1',{},'/equipment',method='DELETE'); s2,_,_=get('/equipment/1'); ok('hapus equipment berelasi ditolak',s2==200)
s,h,_=post('/equipment/'+eid,{},'/equipment',method='DELETE'); s2,_,_=get('/equipment/'+eid); ok('hapus equipment',s2==404)
s,h,_=post('/equipment-categories/'+cid,{},'/equipment-categories',method='DELETE'); s2,h2,_=get('/equipment-categories?search=Uji'); ok('hapus kategori kosong','Kategori Uji' not in h2)
# 8. CSRF & CSV
try:
    r=opn.open(urllib.request.Request(B+'/sessions/1',urllib.parse.urlencode({'_method':'DELETE'}).encode())); c=r.status
except urllib.error.HTTPError as e: c=e.code
ok('CSRF aktif (tanpa token -> 419)',c==419,str(c))
s,h,_=get('/sessions/export?status=completed'); ok('export CSV isi','Kode,"Nama Sesi"' in h or 'Kode,Nama Sesi' in h)
print('\n%d/%d lulus'%(sum(res),len(res)))
