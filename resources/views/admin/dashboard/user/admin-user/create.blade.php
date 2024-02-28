<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin-user.store') }}" method="POST" onsubmit="createOrUpdate(this, event)">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="_method">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Nama Lengkap Pengguna <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukan nama">
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Masukan username">
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Email <span class="text-danger">*</span></label>
                        <input type="text" name="email" class="form-control" placeholder="Masukan email">
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Role <span class="text-danger">*</span></label>
                        <select name="role_id" id="role_id" class="form-control" onchange="updateForm(this.value)">
                            <option value="">Pilih Role</option>
                            @foreach (\Facades\App\Repositories\AdminRoleRepository::findAll() as $role)
                                <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="input-password">
                        <div class="form-group mb-3">
                            <label for="name">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Masukan password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Masukan konfirmasi password">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" placeholder="Alamat..." id="alamat" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
