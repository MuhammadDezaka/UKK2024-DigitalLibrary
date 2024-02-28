@extends('layouts.app')
@section('main')
    <style>
        /* Additional CSS for comment section */
        .comment-section {
            margin-top: 50px;
        }
    </style>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <img src="{{ asset($data->cover) }}" alt="Book Cover" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h1>{{ $data->judul }}</h1>
                <h2>Subtitle</h2>
                @foreach ($data->kategori as $kategoris)
                    <span class="badge bg-primary">{{ $kategoris->nama }}</span>
                @endforeach

                <p>{{ $data->deskripsi}} </p>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Publisher:</strong> {{ $data->penerbit }}</p>
                        <p><strong>Writer:</strong> {{ $data->penulis }}</p>

                       
                             <button class="btn btn-info " id="btn-hapus" type="submit" onclick="deleteRow(this, event)" ><i class="fa fa-bookmark "></i></button>
                      
                            <form method="POST" action="{{ route('koleksi.store') }}"
                                onsubmit="createOrUpdate(this, event)" class="">
                                @csrf
                                <input type="hidden" name="buku" value="{{ $data->id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="_method">
                                <button class="btn btn-warning " type="submit" id="btn-add"><i class="fa fa-bookmark"></i></button>
                            </form>
                        


                    </div>
                    <div class="col-md-6">
                        <p><strong>Year of Release:</strong> {{ $data->tahun_terbit }}</p>
                    </div>
                    <div class="col-md-6" id="rating">
                        {{-- <i class="fa fa-star text-primary"></i>
                        <i class="fa fa-star  text-primary"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment Section -->
    <div class="container comment-section mt-5">
        <h2>Ulasan dan Rating</h2>
        <!-- Example Comment -->
        @foreach ($data->ulasan as $ulasans)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><strong>{{ $ulasans->user->username }}</strong></h5>
                    <p class="card-text">Rating: {{ $ulasans->rating }} out of 5</p>
                    <p class="card-text">Review: {{ $ulasans->ulasan }}</p>
                </div>
            </div>
        @endforeach

        <!-- Add your comment form here -->
    </div>
@endsection
@push('scripts')
    <script>
  
        const createOrUpdate = (form, event) => {
            event.preventDefault();
            $form = $(form);

            let data = new FormData();

            data.append('_token', $form.find('[name="_token"]').val());
            data.append('_method', $form.find('[name="_method"]').val());
            data.append('id', $form.find('[name="id"]').val());
            data.append('buku_id', $form.find('[name="buku"]').val());
            data.append('user_id', $form.find('[name="user_id"]').val());


            axios({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    data: data
                })
                .then(responseJson => {
                    checkkoleksi();
                    $('#btn-add').removeClass('btn-warning')
                    $('#btn-add').addClass('btn-info')
                    Swal.fire('Berhasil', 'Data Berhasil Ditambahkan ke Koleksi', 'success');
                })
                .catch(errorResponse => {
                    console.log(errorResponse)
                    handleErrorRequest(errorResponse);
                });

        };

        const deleteRow = (element, event) => {

            event.preventDefault();
            showDeleteConfirmation()
                .then(result => {
                    if (result.status) {
                        showLoadingSwal();
                        axios({
                                url: `{{ route('koleksi.delete') }}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: element.getAttribute('data-id')
                                }
                            })
                            .then(resultJson => {
                                checkkoleksi();
                                Swal.close();
                                Swal.fire('Success', 'Berhasil Menghapus Data', 'success');
                            })
                            .catch(errorResponse => {
                                Swal.close();
                                handleErrorRequest(errorResponse);
                            });
                    }
                })
        };

        const checkkoleksi = (bukuId) => {
            axios({
                    url: `{{ route('koleksi.check') }}`,
                    method: 'GET',
                    params: {
                        bukuId: `{{ $data->id }}`
                    }

                })
                .then(responseJson => {
                    //200 berarti ada
                    if(responseJson.status == 200){
                        $('#btn-hapus').removeClass('d-none')
                        $('#btn-add').addClass('d-none')
                        $('#btn-hapus').attr('data-id',responseJson.data.id)
                    } else if(responseJson.status == 202){
                        //202 berarti tidak ada koleksi
                        $('#btn-hapus').addClass('d-none')
                        $('#btn-add').removeClass('d-none')
                    }
                  
                })
                .catch(errorResponse => {
                    console.log(errorResponse)
                    handleErrorRequest(errorResponse);
                });
        }
        checkkoleksi()



        const getRate = () => {
            axios({
                    url: `{{ route('ulasan.rate') }}`,
                    method: 'GET',
                    params: {
                        bukuId: `{{ $data->id }}`
                    }

                })
                .then(responseJson => {
                    var rating = responseJson.data[0]['rating']
                    let rating_star = '';
                    let rating_nonstar = '';
                   for(let i = 0; i < rating ;i++){
                    rating_star += '<i class="fa fa-star text-primary"></i>';
                   }
                   for(let j = rating; j < 5; j++){
                    rating_star += '<i class="fa fa-star"></i>';
                   }
                   $('#rating').html(rating_star);
                //    $('#rating').html(rating_star);
            
                })
                .catch(errorResponse => {
                    console.log(errorResponse)
                    handleErrorRequest(errorResponse);
                });
        }
        getRate()
    </script>
@endpush
