@extends('layouts.app')
@section('main')
    <div class="row">
        <div class="col-12">
            @if (Auth::user()->hasRole('peminjam'))
                <input type="text" class="form-control mb-3" placeholder="cari judul buku..." oninput="buku.loadData(this.value);">
                    <div id="buku"></div>
                
            @else
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="data_buku"></h3>

                                <p>Total Buku</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-book"></i>
                            </div>
                            <a href="{{ route('buku.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="data_pinjam"></h3>

                                <p>Total Peminjaman</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('peminjaman.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 id="data_belum"></h3>

                                <p>Total belum dikembalikan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('peminjaman.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="data_user"></h3>

                                <p>Total Akun</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('admin-user.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->


                </div>
            @endif
        </div>
        <!-- /.col -->
    </div>

@endsection

@push('scripts')
    <script>
        const dataAdmin = axios({
                url: `{{ route('home.GetData') }}`,
                method: 'GET',
            })
            .then(resultJson => {
                console.log(resultJson.data);
                $('#data_buku').text(resultJson.data.total_buku)
                $('#data_pinjam').text(resultJson.data.total_pinjam)
                $('#data_belum').text(resultJson.data.total_belum)
                $('#data_user').text(resultJson.data.total_user)
            })
            .catch(errorResponse => {
                handleErrorRequest(errorResponse);
            });

        const buku = {
            baseURL: `{{ route('buku.getData') }}`,
            element: $('#buku'),
            render(resultJson) {
                let htmlRows = ``
                resultJson.forEach(row => {
            
                    let htmlRow = $(`
                    <a href="/buku/detail?id=${row.id}">
                       <img src="${row.cover}" alt="" width="150px" height="200px" class="ml-3">
                    </a>
                    `);
                    htmlRows += `<a href="/buku/detail?id=${row.id}">` + htmlRow.html() + `</a>`;
                });
                this.element.html(htmlRows);
            },
            loadData(search = null) {
                console.log(search);
                axios({
                        url: this.baseURL,
                        method: 'GET',
                        params: {
                            judul: search,
                        }
                    })
                    .then(resultJson => {
                        this.render(resultJson.data);
                    })
                    .catch(errorResponse => {
                        handleErrorRequest(errorResponse);
                        console.log(errorResponse)
                    });
            }
        }
        buku.loadData();
      
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

            getFile("cover");

            function getFile(name) {
                let file = $form.find(`[name="${name}"]`)[0];
                if (file && file.files.length > 0) {
                    data.append(`${name}`, file.files[0]);
                }
            }


            console.log(selectedCategories);
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
@endpush
