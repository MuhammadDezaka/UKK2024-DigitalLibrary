<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Repositories\KategoriRepository;
use App\Http\Requests\KategoriBukuRequest;

class KategoriController extends Controller
{

    protected $configrepo;

    public function __construct(KategoriRepository $repo)
    {
        $this->configrepo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.kategori.index');
    }

    public function tables(Request $request)
    {
      

        return $this->configrepo->getCmsTableWhere();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KategoriBukuRequest $request)
    {
        $request = $request->validated();
        // dd($request);
        $store = $this->configrepo->create($request);
        return ['status' => 'success'];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(KategoriBukuRequest $request)
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

    public function getkategori($bukuId){
        $buku = Buku::find($bukuId);
        $kategori = $this->configrepo->findAll();

        $kategoriBuku = $buku->kategori->pluck('id')->toArray();

        $kategoriData = $kategori->map(function ($kategori) use ($kategoriBuku) {
            return [
                'id' => $kategori->id,
                'name' => $kategori->nama,
                'checked' => in_array($kategori->id, $kategoriBuku),
            ];
        });

        return ['kategori' => $kategoriData];

    }

    public function updateKategori(Request $request ,$bukuId){
        $buku = Buku::find($bukuId);

        if (!$buku) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $kategori = $request->input('kategori', []);

        try {
            $buku->kategori()->sync($kategori);
        } catch (\Exception $e) {
        
            return $e->getMessage();
        }

        return response()->json(['message' => 'Permissions updated successfully']);
    }
}
