<?php

namespace App\Http\Controllers;

use App\Http\Requests\BukuRequest;
use App\Models\Buku;
use App\Repositories\BukuRepository;
use Illuminate\Http\Request;

class BukuController extends Controller
{

    protected $configrepo;

    public function __construct(BukuRepository $repo)
    {
        $this->configrepo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.buku.index');
    }

    public function tables(Request $request)
    {
        
        return $this->configrepo->getCmsTableWhere();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BukuRequest $request)
    {
        $validatedData = $request->validated();
        $kategori = explode(',',$request->input('kategori'));
     
        
        //save images 
        if($request->file('cover')){
            $validatedData['cover'] = $request->file('cover')->store('books-cover');
        }
      
        // Create buku
        $buku = $this->configrepo->create($validatedData);
        $buku->save();

        // Attach categories to buku
        if (isset($kategori) && is_array($kategori)) {
            foreach ($kategori as $kategoriId) {
                $buku->kategori()->attach($kategoriId);
            }

            return ['status' => 'success'];
        } else {

            return response()->json(['error' => 'Failed to create buku'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(BukuRequest $request)
    {

        $validatedData = $request->validated();
        $kategori = explode(',',$request->input('kategori'));

        if($request->file('cover')){
            $validatedData['cover'] = $request->file('cover')->store('books-cover');
        }
      
        $buku = $this->configrepo->update($validatedData['id'], $validatedData);

            return ['status' => 'success'];
      
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

    public function detail(Request $request)
    {
        $id = $request->query('id');
        $relasi = ['kategori','ulasan.user','koleksi'];
        $data = $this->configrepo->findWithRelation($id,$relasi);

        return view('admin.dashboard.buku.books',compact('data'));
    }

    public function getData(Request $request){
        $search = null;
        $kategori = null;
        if($request->judul != null){
             $search = $request->judul;
        }

        $data = $this->configrepo->findAll('judul',$search);

        return $data;


    }
}
