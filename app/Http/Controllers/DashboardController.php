<?php

namespace App\Http\Controllers;

use App\Repositories\AdminUserRepository;
use App\Repositories\BukuRepository;
use Illuminate\Http\Request;
use App\Repositories\PeminjamanRepository;

class DashboardController extends Controller
{

    protected $peminjamanrepo;
    protected $bukurepo;
    protected $userrepo;

    public function __construct(PeminjamanRepository $repo_pinjam,BukuRepository $repo_buku,AdminUserRepository $repo_user)
    {
        $this->peminjamanrepo = $repo_pinjam;
        $this->bukurepo = $repo_buku;
        $this->userrepo = $repo_user;
    }
    
    public function index(){
        return view('admin.dashboard.home.index');
    }

    public function getData(){
        $peminjaman = $this->peminjamanrepo->count();
        $belum_dikembalikan = $this->peminjamanrepo->count(['status' => 1]);
        $total_buku = $this->bukurepo->count();
        $total_user = $this->userrepo->count();

        return [
            'total_buku' => $total_buku,
            'total_user' => $total_user,
            'total_pinjam' => $peminjaman,
            'total_belum' => $belum_dikembalikan,
        ];
    }
}
