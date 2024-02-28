@extends('layouts.app')
@section('title','Data Koleksi')
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
                            {{-- <button type="button" onclick="showCreateModal(event)"
                                class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#myModal">
                                Tambah Data
                            </button> --}}

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
                <form method="POST" action="{{ route('koleksi.store') }}" onsubmit="createOrUpdate(this, event)"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="_method">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Pinjam Buku</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
             
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
            baseURL: `{{ route('koleksi.tables') }}`,
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

        

                    let htmlRow = $(`
                        <tr>
                            <td class="td-no text-center"></td>
                            <td class="td-judul"></td>
                            <td class="text-center">

                                <a href="#" data-id="${row.id}" onclick="deleteRow(this, event)" class="btn btn-sm btn-danger ${ row.total > 0 ? 'disabled' : '' }">
                                    <i class="fa fa-fw fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    `);

                    htmlRow.find('.td-no').text(index);
                    if(!row.buku.judul){
                        htmlRow.find('.td-judul').text('-');
                    }
                    htmlRow.find('.td-judul').text(row.buku.judul);
                    htmlRows += '<tr>' + htmlRow.html() + '</tr>';
                });

                this.element.find('tbody').html(htmlRows);
            },
            loadDataTables() {
                this.setLoadingState();
                const nama = $('#nama').val();
                axios({
                        url: this.baseURL,
                        method: 'GET',
                        params: {
                            keyword: $('input[name="keyword"]').val(),
                        
                          
                        }
                    })
                    .then(resultJson => {
                        console.log(resultJson.data);
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
      

        const deleteRow = (element, event) => {

            event.preventDefault();
            showDeleteConfirmation()
                .then(result => {
                    if (result.status) {
                        showLoadingSwal();
                        axios({
                                url: `koleksi`,
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
