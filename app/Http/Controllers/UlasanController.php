<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UlasanRequest;
use App\Models\UlasanBuku;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UlasanRepository;

class UlasanController extends Controller
{

    protected $configrepo;

    public function __construct(UlasanRepository $repo)
    {
        $this->configrepo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.ulasan.index');
    }

    public function tables(Request $request)
    {
      
        $relasi = ['Buku'];
        $where = ['user_id' => Auth::user()->id];
        return $this->configrepo->getCmsTableWhere($relasi,$where);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UlasanRequest $request)
    {
        $request = $request->validated();
        // dd($request);
        $store = $this->configrepo->create($request);
        return ['status' => 'success'];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UlasanRequest $request)
    {
        $request = $request->validated();
        $update = $this->configrepo->update($request['id'], $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $data = $this->configrepo->find($request->id);
        if (is_null($data)) {
            abort(404);
        }

        $this->configrepo->delete($request->id);
        return ['status' => 'success'];
    }

    public function checkIfExist(Request $request){
        $bukuId = $request->query('bukuId');
        // dd($bukuId);
        if($this->configrepo->checkIfExist($bukuId)){
            return response()->json(['status' => 'success', 'message' => 'Ulasan sudah ada.'],202);
        }
          return response()->json(['status' => 'success', 'message' => 'Ulasan Belum Ada.'],200);
    }

    public function getRate(Request $request){
        $bukuId = $request->query('bukuId');
        $rate = DB::select('
            SELECT FLOOR(AVG(rating)) AS rating FROM ulasan_buku WHERE buku_id = '.$bukuId.'
        ');

        return $rate;
    }
}
