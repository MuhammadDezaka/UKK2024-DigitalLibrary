<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PeminjamanRequest;
use App\Repositories\PeminjamanRepository;

class PeminjamanController extends Controller
{

    protected $configrepo;

    public function __construct(PeminjamanRepository $repo)
    {
        $this->configrepo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query_peminjaman = Peminjaman::select('buku_id')->where('tanggal_pengembalian',null)->get()->toArray();
        $query1 = Buku::select('id','judul')->whereNotIn('id',$query_peminjaman)->get();
        // dd($query_peminjaman);
        if(Auth::user()->hasRole('peminjam')){
            return view('admin.dashboard.peminjaman.index',compact('query1'));
        }
        return view('admin.dashboard.peminjaman.admin-index');
    }

    public function tables(Request $request)
    {
      
        $relasi = ['Buku','User'];
        $where = [];
        if(Auth::user()->hasRole('peminjam')){
            $where += ['user_id' => Auth::user()->id];
        }

        if($request->status != null){
            $where += ['status' => $request->status];
        }
        return $this->configrepo->getCmsTableWhere($relasi,$where);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeminjamanRequest $request)
    {
        $request = $request->validated();
        // dd($request);

        $request['tanggal_peminjaman'] = Carbon::now();
        if (!isset( $request['status'])) {
            $request['status'] = 1; // Set default status to 3
        }
        if($this->configrepo->checkIfExist($request['buku_id'])){
            return response()->json(['status' => 'error', 'message' => 'Buku yang sama sudah Anda pinjam.'],400);
        }
        $store = $this->configrepo->create($request);
        return ['status' => 'success'];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PeminjamanRequest $request)
    {
        $request = $request->validated();
        $request['tanggal_pengembalian'] = Carbon::now();

        if (!isset( $request['status'])) {
            $request['status'] = 4; // Set default status to 3
        }
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
}
