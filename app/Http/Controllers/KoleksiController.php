<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\KoleksiRequest;
use App\Repositories\KoleksiRepository;

class KoleksiController extends Controller
{

    protected $configrepo;

    public function __construct(KoleksiRepository $repo)
    {
        $this->configrepo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.koleksi.index');
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
    public function store(KoleksiRequest $request)
    {
        $request = $request->validated();
        // dd($request);
        $store = $this->configrepo->create($request);
        return ['status' => 'success'];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(KoleksiRequest $request)
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
        $where = ['buku_id' => $bukuId, 'user_id' => Auth::user()->id];
        if($this->configrepo->checkIfExist($bukuId)){
           return $this->configrepo->findWhere($where);
        }

       return response()->json('not found', 202);

    }
    
}
