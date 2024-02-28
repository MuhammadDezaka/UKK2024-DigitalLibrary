<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // $pass = Hash::make('123');
        // $user = User::find(2);
        // Permission::create(['name' => 'view home']);
        Permission::create(['name' => 'manage permissions']);
        Permission::create(['name' => 'manage user']);
        // Permission::create(['name' => 'manage kategori']);
        // Permission::create(['name' => 'manage ulasan']);
        // Permission::create(['name' => 'manage koleksi']);

        // Role::create(['name' => 'admin']);
        // Role::create(['name' => 'petugas']);
        // Role::create(['name' => 'peminjam']);

        // $rolePeminjam = Role::findByName('peminjam');
        // // $rolePeminjam->givePermissionTo('add peminjaman');
        // $rolePeminjam->givePermissionTo('manage koleksi');
        // $rolePeminjam->givePermissionTo('manage ulasan');

        // $rolePeminjam = Role::findByName('admin');
        // $rolePeminjam->givePermissionTo('add peminjaman');
        // $rolePeminjam->givePermissionTo('manage buku');
        // $rolePeminjam->givePermissionTo('manage kategori');
        // $user = User::find(2);
        // $user->assignRole('admin');
    }
}
