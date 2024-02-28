@extends('layouts.app')
@section('title','Data Peminjaman')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card">
              
                <div class="card-header">
                

                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <label for="status">Status Peminjaman</label>
                            <select class="form-control" name="status_pinjam" id="status" onchange="table.loadDataTables(this.value)">
                                <option value="1">Dipinjam</option>
                                <option value="2">Dikembalikan</option>
                                {{-- <option value="3">Pending Ke</option> --}}
                                <option value="4">Pending Dikembalikan</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 d-flex align-items-center justify-content-end">

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

                                    <th>Nama Peminjam</th>

                                <th>Tanggal Pinjam</th>
                                <th>Tanggal dikembalikan</th>
                                <th>Status</th>
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
                <form method="POST" action="{{ route('peminjaman.store') }}" onsubmit="createOrUpdate(this, event)"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="buku_id">
                    <input type="hidden" name="_method">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Pinjam Buku</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="example-text-input" class="form-label"><strong>Status</strong></label>
                            <select class="form-control" id="input_status_sekolah" name="status">
                                <option value="2">Kembali</option>
                            </select>

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
@endsection

@push('scripts')
    <script>
        const table = {
            baseURL: `{{ route('peminjaman.tables') }}`,
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
                            <td class="td-nama"></td>
                            <td class="td-tanggal-pinjaman"></td>
                            <td class="td-tanggal-kembali"></td>
                            <td class="td-status"></td>
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
                    htmlRow.find('.td-judul').text(row.buku.judul);
                    htmlRow.find('.td-nama').text(row.user.nama_lengkap);
                    htmlRow.find('.td-tanggal-pinjaman').text(row.tanggal_peminjaman);
                    htmlRow.find('.td-tanggal-kembali').text(row.tanggal_pengembalian ? row
                        .tanggal_pengembalian : "Belum Dikembalikan");
                    let statusText = '';
                    switch (row.status) {
                        case 1:
                            statusText = 'Dipinjam';
                            break;
                        case 2:
                            statusText = 'Dikembalikan';
                            break;
                        case 4:
                            statusText = 'Pending Dikembalikan';
                            break;
                        default:
                            statusText = 'Unknown';
                            break;
                    }
                    htmlRow.find('.td-status').text(statusText);
                    htmlRows += '<tr>' + htmlRow.html() + '</tr>';
                });

                this.element.find('tbody').html(htmlRows);
            },
            loadDataTables(status = null) {
                this.setLoadingState();
                const nama = $('#nama').val();
                axios({
                        url: this.baseURL,
                        method: 'GET',
                        params: {
                            keyword: $('input[name="keyword"]').val(),
                            status: status,
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


            modalBootstrap.show();
        };

        const createOrUpdate = (form, event) => {
            event.preventDefault();
            $form = $(form);

            $form.find('[type="submit"]')
                .addClass('disabled')
                .attr('disabled', 'disabled')
                .html('Sedang Mengirim Data');

            let data = new FormData();

            data.append('_token', $form.find('[name="_token"]').val());
            data.append('_method', $form.find('[name="_method"]').val());
            data.append('id', $form.find('[name="id"]').val());
            data.append('buku_id', $form.find('[name="buku_id"]').val());
            data.append('user_id', $form.find('[name="user_id"]').val());
            data.append('status', $form.find('[name="status"]').val());


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
            console.log(json.buku_id);
            $modalElement.find('.modal-title').html('Edit Data');
            $modalElement.find('[name="id"]').val(json.id);
            $modalElement.find('[name="_method"]').val('PUT');
            $modalElement.find('[name="user_id"]').val(json.user_id);
            $modalElement.find('[name="buku_id"]').val(json.buku_id);


            modalBootstrap.show();

        }

        const deleteRow = (element, event) => {

            event.preventDefault();
            showDeleteConfirmation()
                .then(result => {
                    if (result.status) {
                        showLoadingSwal();
                        axios({
                                url: `peminjaman`,
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
@endpush
