@extends('layouts.app')

@section('title', 'Modul Praktikum')

@push('style')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
@endpush

@section('main')
    <div class="main-content" style="min-height: 593px;">
        <section class="section">
        <div class="section-header">
            <h1>Editor</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Forms</a></div>
              <div class="breadcrumb-item">Editor</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title"> {{ $data->nm_bahan}}</h2>
          <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                  <ul class="nav nav-pills" id="myTab3" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link " id="home-tab3" href="/bahan/show/{{$data->id_bahan}}" role="tab" aria-controls="home" aria-selected="true">Detail</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active show" id="profile-tab3"  href="/bahan/stok/{{$data->id_bahan}}" role="tab" aria-controls="profile" aria-selected="false">Kartu Stok</a>
                      </li>
                    </ul>
                  </div>
                  {{ Form::open(array('id'=>'formStoreStok', 'data-action' => route('bahan.store_stok') )) }}
                  {{ Form::hidden('id_bahan', $data->id_bahan) }}
                  <div class="card-body">
                    <div id="messages">
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tipe</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::select("tipe", array("penambahan" => "penambahan", "pengurangan" => "pengurangan") ,null, ["class" => "form-control"]) }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jumlah</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::number("jumlah", null, ["class" => "form-control"]) }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Satuan</label>
                      <div class="col-sm-12 col-md-7 col-form-label">
                      {{ $data->satuan}}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keterangan</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::textarea("keterangan", null, ["class" => "form-control", "style" => "height: 150px;"]) }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                      <div class="col-sm-12 col-md-7">
                        <button class="btn btn-primary" type="submit" id="simpanButton">Simpan</button>
                      </div>
                    </div>
                  </div>
                  {{ Form::close() }}
                </div>
              </div>
            </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4>Transaksi Bahan</h4>
                </div>
                <div class="card-body p-3">
                  <div class="table-responsive table-invoice">
                  <table class="table table-bordered yajra-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>#penambahan</th>
                            <th>#pengurangan</th>
                            <th>Stok</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>  

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        </section>
    </div>

      <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ Form::open(array('id'=>'formDeleteStok', 'data-action' => route('bahan.destroy_stok') )) }}
            {{ Form::hidden('id_deleted_stok', null) }}
            <div class="modal-body">
                Apakan Anda yakin akan menghapus data ini ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger" id="deleteButton">Ok</button>
            </div>
            {{ Form::close() }}
            </div>
        </div>
        </div>


    
    <script type="text/javascript">
        $(function () {
            
            var table = $('.yajra-datatable').DataTable({
                ordering: false,
                processing: true,
               
                initComplete: function(){
                 this.api().page('last').draw('page')
              },
                ajax: "{{ route('bahan.list_stok',['id' => $data->id_bahan]) }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'penambahan', name: 'penambahan'},
                    {data: 'pengurangan', name: 'pengurangan'},
                    {data:'stok_akhir', name: 'stok_akhir'},
                    {data: 'keterangan', name: 'keterangan'},
                    {
                        data: 'action', 
                        name: 'action', 
                        width:"15%"
                    
                    
                    },
                ]
            });

            $(".yajra-datatable").DataTable().page('last').draw('page');

            var form = '#formStoreStok';

            $(form).on('submit', function(event){
                event.preventDefault();
                $('#simpanButton').addClass('btn-progress');
                var url = $(this).attr('data-action');
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(response)
                    {
                        $(form).trigger("reset");
                        table.ajax.reload();
                        $("#messages").empty().append(`<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                <span>×</span>
                                </button>
                                `+response.success+`
                            </div>
                        </div>`);
                        $('#simpanButton').removeClass('btn-progress');
                    },
                    error: function(response) {
                    }
                });
            });

            var deleteForm = "#formDeleteStok";
            $(deleteForm).on('submit', function(event){
                event.preventDefault();
                $('#deleteButton').addClass('btn-progress');
                var url = $(this).attr('data-action');
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(response)
                    {
                        $(deleteForm).trigger("reset");
                        table.ajax.reload();
                        $('#exampleModal').modal('toggle');
                        $("#messages").empty().append(`<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                <span>×</span>
                                </button>
                                `+response.success+`
                            </div>
                        </div>`);
                        $('#deleteButton').removeClass('btn-progress');
                    },
                    error: function(response) {
                    }
                });
            });
            
        });

        function openDeleteStok(id){
            $("input[name=id_deleted_stok]").val(id);
            $('#exampleModal').modal('toggle');
        }

       
    </script>

@endsection
