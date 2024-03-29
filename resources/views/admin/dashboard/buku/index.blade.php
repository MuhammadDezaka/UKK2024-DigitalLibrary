@extends('layouts.app')
@section('title','Data Buku')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">


                    <div class="row">
                        <div class="col-md-6 col-sm-12">

                        </div>
                        <div class="col-md-6 col-sm-12 d-flex align-items-center justify-content-end">
                            {{-- <a href="" class="" data-bs-toggle="modal"  data-bs-target="#myModal">Tambah Data</a> --}}
                            <button type="button" onclick="showCreateModal(event)"
                                class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#myModal">
                                Tambah Data
                            </button>

                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Judul Buku</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun Terbit</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>


                    </table>
                    <nav id="pagination-container" aria-label="Page navigation">
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('buku.store') }}" onsubmit="createOrUpdate(this, event)"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="_method">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="example-text-input" class="form-label"><strong>Judul</strong></label>
                            <input class="form-control" type="text" id="input_nama_sekolah" name="judul">
                        </div>

                    
                           
                            <div class="form-group mb-3">
                                <label for="example-text-input" class="form-label" for="custom-file"><strong>Cover Buku</strong></label>
            
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="customFile" name="cover">
                                  <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                       


                        <div class="mb-3">
                            <label for="example-text-input" class="form-label"><strong>Penulis</strong></label>
                            <input class="form-control" type="text" id="input_nama_sekolah" name="penulis">
                        </div>
                        <div class="mb-3">
                            <label for="example-text-input" class="form-label"><strong>Penerbit</strong></label>
                            <input class="form-control" type="text" id="input_nama_sekolah" name="penerbit">
                        </div>
                        <div class="mb-3 kategori-select">
                            <label for="example-text-input" class="form-label"><strong>Kategori</strong></label>
                            <br>
                            @foreach (App\Models\Kategori::all() as $kategori)
                                <input type="checkbox" name="kategori[]" value="{{ $kategori->id }}" class="check-input">
                                <label for="{{ $kategori->nama . $kategori->id }}"> {{ $kategori->nama }}</label><br>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <label for="example-text-input" class="form-label"><strong>Tahun terbit</strong></label>
                            <input class="form-control" type="int" id="input_nama_sekolah" name="tahun_terbit">
                        </div>
                        <div class="mb-3">
                            <label for="example-text-input" class="form-label"><strong>Deskripsi singkat buku</strong></label>
                            <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                   


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-success waves-effect waves-light">Simpan Data</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{-- kategori modal --}}
    <div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kategoriModalLabel">Manage kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Permission checkboxes will be inserted here dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savekategoriButton">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const table = {
            baseURL: `{{ route('buku.tables') }}`,
            element: $('#datatables'),
            setLoadingState() {
                this.element.find('tbody').html(`
                    <tr class="text-warning">
                        <td colspan="${this.element.find('thead th').length}">
                            <i class="fa fa-fw fa-circle-notch fa-spin"></i> Mohon tunggu, sedang memuat data ...
                        </td>
                    </tr>
                `);
            },
            render(resultJson) {
                let htmlRows = ``
                let index = 0;
                resultJson.forEach(row => {
                    index += 1

                    let status = ''
                    if (row.status == "1") {
                        status = '<span class="badge bg-success">Aktif</span>'
                    } else {
                        status = '<span class="badge bg-danger">Tidak Aktif</span>'
                    }

                    let htmlRow = $(`
                        <tr>
                            <td class="td-no text-center"></td>
                            <td class="td-judul"></td>
                            <td class="td-penulis"></td>
                            <td class="td-penerbit"></td>
                            <td class="td-tahun_terbit"></td>
                            <td class="td-jumlah-user">
                                <a href="#" data-id="${row.id}" onclick="managePermissions(this, event)" class="btn btn-sm btn-primary">
                                    Manage Kategori
                                </a>
                            </td>
                            <td class="text-center">

                                <a href="#" onclick="editRow(this, event)" data-json='${JSON.stringify(row)}' class="btn btn-sm btn-success">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                                <a href="#" data-id="${row.id}" onclick="deleteRow(this, event)" class="btn btn-sm btn-danger ${ row.total > 0 ? 'disabled' : '' }">
                                    <i class="fa fa-fw fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    `);

                    htmlRow.find('.td-no').text(index);
                    htmlRow.find('.td-judul').text(row.judul);
                    htmlRow.find('.td-penerbit').text(row.penerbit);
                    htmlRow.find('.td-penulis').text(row.penulis);
                    htmlRow.find('.td-tahun_terbit').text(row.tahun_terbit);
                    htmlRows += '<tr>' + htmlRow.html() + '</tr>';
                });

                this.element.find('tbody').html(htmlRows);
            },
            loadDataTables(kategori = null) {
                this.setLoadingState();
                const nama = $('#nama').val();
                axios({
                        url: this.baseURL,
                        method: 'GET',
                        params: {
                            keyword: $('input[name="keyword"]').val(),
                            nama: nama,
                            kategori: kategori
                        }
                    })
                    .then(resultJson => {
                        this.render(resultJson.data.data);
                        renderPagination(resultJson.data.links, $('#pagination-container'), this);
                    })
                    .catch(errorResponse => {
                        handleErrorRequest(errorResponse);
                        console.log(errorResponse)
                    });
            }
        };

        table.loadDataTables();
    </script>


    <script>
        const modalElement = document.querySelector('#myModal');
        const modalBootstrap = new bootstrap.Modal(modalElement);
        const $modalElement = $(modalElement);


        const showCreateModal = (event) => {
            event.preventDefault();

            $modalElement.find('.modal-title').html('Tambah Data');
            $modalElement.find('[name="id"]').val('');
            $modalElement.find('[name="_method"]').val('POST');
            $modalElement.find('[name="judul"]').val('');
            $modalElement.find('[name="penulis"]').val('');
            $modalElement.find('[name="penerbit"]').val('');
            $modalElement.find('[name="tahun_terbit"]').val('');
            $modalElement.find('[name="deskripsi"]').val('');


            modalBootstrap.show();
        };

        const createOrUpdate = (form, event) => {
            event.preventDefault();
            $form = $(form);

            $form.find('[type="submit"]')
                .addClass('disabled')
                .attr('disabled', 'disabled')
                .html('Sedang Mengirim Data');


            let selectedCategories = Array.from(new Set($('.check-input:checked').map(function() {
                return $(this).val();
            }).get()));


            let data = new FormData();

            data.append('_token', $form.find('[name="_token"]').val());
            data.append('_method', $form.find('[name="_method"]').val());
            data.append('id', $form.find('[name="id"]').val());
            data.append('judul', $form.find('[name="judul"]').val());
            data.append('penulis', $form.find('[name="penulis"]').val());
            data.append('penerbit', $form.find('[name="penerbit"]').val());
            data.append('tahun_terbit', $form.find('[name="tahun_terbit"]').val());
            data.append('deskripsi', $form.find('[name="deskripsi"]').val());

            getFile("cover");

            function getFile(name) {
                let file = $form.find(`[name="${name}"]`)[0];
                if (file && file.files.length > 0) {
                    data.append(`${name}`, file.files[0]);
                }
            }
            data.append('kategori', selectedCategories);

            axios({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    data: data
                })
                .then(responseJson => {
                    Swal.fire('Berhasil', 'Data Berhasil Ditambahkan', 'success');
                    modalBootstrap.hide();
                    table.loadDataTables();
                })
                .catch(errorResponse => {
                    console.log(errorResponse)
                    handleErrorRequest(errorResponse);
                })
                .then(() => {
                    $form.find('[type="submit"]')
                        .removeClass('disabled')
                        .removeAttr('disabled')
                        .html('Simpan');
                });
        };



        const editRow = (element, event) => {
            event.preventDefault();


            let data = element.getAttribute('data-json');

            let json = JSON.parse(element.getAttribute('data-json'));

            $modalElement.find('.modal-title').html('Edit Data');
            $modalElement.find('[name="id"]').val(json.id);
            $modalElement.find('[name="_method"]').val('PUT');
            $modalElement.find('[name="judul"]').val(json.judul);
            $modalElement.find('[name="penulis"]').val(json.penulis);
            $modalElement.find('[name="penerbit"]').val(json.penerbit);
            $modalElement.find('[name="tahun_terbit"]').val(json.tahun_terbit);
            $modalElement.find('.kategori-select').addClass('d-none');

            modalBootstrap.show();

        }

        const deleteRow = (element, event) => {

            event.preventDefault();
            showDeleteConfirmation()
                .then(result => {
                    if (result.status) {
                        showLoadingSwal();
                        axios({
                                url: `buku`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: element.getAttribute('data-id')
                                }
                            })
                            .then(resultJson => {
                                Swal.close();
                                Swal.fire('Success', 'Berhasil Menghapus Data', 'success');
                                table.loadDataTables($('#regency').val());
                            })
                            .catch(errorResponse => {
                                Swal.close();
                                handleErrorRequest(errorResponse);
                            });
                    }
                })
        };
    </script>
        <script>
            function managePermissions(button, event) {
                event.preventDefault();
    
                let bukuId = $(button).data('id');
                $('#kategoriModal').data('buku-id', bukuId);
    
                axios.get(`/kategori/${bukuId}`)
                    .then(response => {
                        let checkboxes = '';
    
                        response.data.kategori.forEach(kategori => {
                            checkboxes += `
                            <div class="form-check">
                                <input class="form-check-input" name="input-permission" type="checkbox" id="permission_${kategori.id}" value="${kategori.id}" ${kategori.checked ? 'checked' : ''}>
                                <label class="form-check-label" for="permission_${kategori.id}">
                                    ${kategori.name}
                                </label>
                            </div>
                        `;
                        });
    
                        $('#kategoriModal .modal-body').html(checkboxes);
    
                        $('#kategoriModal').modal('show');
                    })
                    .catch(error => {
                        console.error('Error fetching permissions:', error);
                    });
            }
    

            document.getElementById('savekategoriButton').addEventListener('click', savePermissions);
    
            function savePermissions() {
    
                showLoadingSwal();
    
                let bukuId = $('#kategoriModal').data('buku-id');
    
                let selectedKategori = Array.from(new Set($('.form-check-input:checked').map(function() {
                    return $(this).val();
                }).get()));
    

    
    
                axios.post(`/kategori/${bukuId}`, {
                        kategori: selectedKategori
                    })
                    .then(response => {
                        Swal.fire('Berhasil', 'Berhasil Mengubah Data', 'success');
    
                        $('#kategoriModal').modal('hide');
                    })
                    .catch(error => {
                        console.error('Error saving permissions:', error);
                        handleErrorRequest(error);
                    });
            }
        </script>
@endpush
