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
          <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Tambah Modul Praktikum</h4>
                  </div>
                  {{ Form::open(array('id'=>'formTambahMdPraktikum', 'data-action' => route('mdpraktikum.store') )) }}
                  {{ Form::hidden('id_praktikum', null) }}
                  <div class="card-body">
                    <div id="messages">
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Modul Praktikum</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::text("nm_praktikum", null, ["class" => "form-control"]) }}
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
                  <h4>Modul Praktikum</h4>
                </div>
                <div class="card-body p-3">
                  <div class="table-responsive table-invoice">
                  <table class="table table-bordered yajra-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id</th>
                            <th>Modul Praktikum</th>
                            <th>Keterangan</th>
                            <th>Dibuat</th>
                            <th>Status</th>
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
            {{ Form::open(array('id'=>'formDeleteMdPraktikum', 'data-action' => route('mdpraktikum.destroy') )) }}
            {{ Form::hidden('id_deleted_praktikum', null) }}
            <div class="modal-body">
                Apakan Anda yakin akan menghapus <text id="delete_nm_praktikum"></text> ?
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
                serverSide: true,
                ajax: "{{ route('mdpraktikum.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'id_praktikum', name:'id_praktikum', visible: false},
                    {data: 'nm_praktikum', name: 'nm_praktikum'},
                    {data: 'keterangan', name: 'keterangan'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'formated_status', name: 'formated_status'},
                    {
                        data: 'action', 
                        name: 'action', 
                        width:"15%"
                    
                    
                    },
                ]
            });

            $('.yajra-datatable tbody').on('click', '.edit-md', function () {
                var data = table.row($(this).parents('tr')).data();
                $("input[name='id_praktikum']").val(data.id_praktikum);
                $("input[name='nm_praktikum']").val(data.nm_praktikum);
                $("textarea[name='keterangan']").val(data.keterangan);
            });



            var form = '#formTambahMdPraktikum';

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
                        $("#messages").append(`<div class="alert alert-success alert-dismissible show fade">
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

            var deleteForm = "#formDeleteMdPraktikum";
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
                        $("#messages").append(`<div class="alert alert-success alert-dismissible show fade">
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

        function openDeleteMdPraktikum(id, name){
            $('#delete_nm_praktikum').text(name);
            $("input[name=id_deleted_praktikum]").val(id);
            $('#exampleModal').modal('toggle');
        }

       
    </script>

@endsection
