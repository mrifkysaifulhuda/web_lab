@extends('layouts.app')

@section('title', 'Modul Praktikum')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush


@section('main')
    <div class="main-content" style="min-height: 593px;">
        <section class="section">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Modul Praktikum</h4>
                  </div>
                  {{ Form::open(array('id'=>'formAjukanKebutuhan', 'url' => route('mdpraktikum.ajukan') )) }}
                  {{ Form::hidden('id_praktikum', $data->id_praktikum) }}
                  <div class="card-body">
                    <div id="messages">
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Modul Praktikum</label>
                      <div class="col-sm-12 col-md-7 col-form-label">
                      {{ $data->nm_praktikum}}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keterangan</label>
                      <div class="col-sm-12 col-md-7 col-form-label">
                      {{ $data->keterangan}}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                      <div class="col-sm-12 col-md-7 col-form-label">
                        <div class="badge {{ $data->status == 'Draf' ? 'badge-warning' : 'badge-success'  }}">{{ $data->status}}</div>
                      
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                      <div class="col-sm-12 col-md-7">
                        @if($data->allow_edit)
                        <button class="btn btn-success" type="submit" id="simpanButton">Ajukan</button>
                        @endif
                        <a href="/mdpraktikum" class="btn btn-primary" type="button" id="simpanButton">Kembali</a>
                      </div>
                    </div>
                  </div>
                  {{ Form::close() }}
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0">
                        <div class="card-header pb-0 pt-0" style="min-height:0">
                            <h4>Kebutuhan Alat</h4>
                        </div>
                        @if($data->allow_edit)
                        <div class="card-body pb-0 pt-0">
                            <div id="kebAlatMessages">
                            </div>
                            <div class="row">
                                <label class="col-form-label col-12 col-md-3 col-lg-3">Nama Alat</label>
                                <label class="col-form-label col-12 col-md-3 col-lg-3">Jumlah</label>
                                <label class="col-form-label col-12 col-md-3 col-lg-3"></label>
                            </div>
                            {{ Form::open(array('id'=>'formTambahKebAlat', 'data-action' => route('keb-alat.store') )) }}
                                  {{ Form::hidden('id_praktikum', $data->id_praktikum) }}
                            <div class="form-group row mb-4">
                              <select id="id_alat" name="id_alat" class="form-control col-form-label col-5 col-md-3 col-lg-3 mr-1"></select>
                              <input type="number" name="jumlah_ajuan" class="form-control col-form-label col-5 col-md-3 col-lg-3 mr-1 ml-1">
                              <button class="btn btn-primary" type="buttom" id="simpanKebAlat">Tambah</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                        @endif
                    </div>
                    <div class="card-body p-3">
                      <div class="table-responsive table-invoice">
                        <table class="table table-bordered keb-alat-datatable">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Nama Alat</th>
                                  <th>Kebutuhan</th>
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
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0">
                        <div class="card-header pb-0" style="min-height:0">
                            <h4>Kebutuhan Bahan</h4>
                        </div>
                        @if($data->allow_edit)
                        <div class="card-body pb-0 pt-0">
                            <div id="kebBahanMessages">
                            </div>
                            <div class="row">
                                <label class="col-form-label col-12 col-md-3 col-lg-3">Nama Bahan</label>
                                <label class="col-form-label col-12 col-md-3 col-lg-3">Jumlah</label>
                                <label class="col-form-label col-12 col-md-3 col-lg-3">Satuan</label>
                                <label class="col-form-label col-12 col-md-3 col-lg-3"></label>
                            </div>
                            {{ Form::open(array('id'=>'formTambahKebBahan', 'data-action' => route('keb-bahan.store') )) }}
                                  {{ Form::hidden('id_praktikum', $data->id_praktikum) }}
                            <div class="form-group row mb-4">
                              <select id="id_bahan" name="id_bahan" class="form-control col-form-label col-5 col-md-3 col-lg-3 mr-1"></select>
                              <input type="number" name="jumlah_ajuan" class="form-control col-form-label col-5 col-md-3 col-lg-3 mr-1 ml-1">
                              <select name="satuan" id="satuan" class="form-control col-form-label col-5 col-md-3 col-lg-3 mr-1 ml-1">
                                <option value="gram">gram</option>
                                <option value="mg">mg</option>
                                <option value="liter">liter</option>
                                <option value="mL">mL</option>
                                <option value="lembar">lembar</option>
                              </select>
                              <button class="btn btn-primary" type="buttom" id="simpanKebBahan">Tambah</button>
                            </div>
                            {{ Form::close() }}
                         </div>
                        </div>
                        @endif
                    <div class="card-body p-3">
                      <div class="table-responsive table-invoice">
                        <table class="table table-bordered keb-bahan-datatable">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Nama Bahan</th>
                                  <th>Kebutuhan</th>
                                  <th>Satuan</th>
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
          {{ Form::open(array('id'=>'formDeleteKebAlat', 'data-action' => route('keb-alat.destroy') )) }}
          {{ Form::hidden('id_deleted_keb_alat', null) }}
          <div class="modal-body">
              Apakan Anda yakin akan menghapus <text id="delete_alat"></text> ?
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger" id="deleteAlatButton">Ok</button>
          </div>
          {{ Form::close() }}
          </div>
      </div>
    </div>

    <div class="modal fade" id="deleteKebBahanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          {{ Form::open(array('id'=>'formDeleteKebBahan', 'data-action' => route('keb-bahan.destroy') )) }}
          {{ Form::hidden('id_deleted_keb_bahan', null) }}
          <div class="modal-body">
              Apakan Anda yakin akan menghapus <text id="delete_bahan"></text> ?
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger" id="deleteBahanButton">Ok</button>
          </div>
          {{ Form::close() }}
          </div>
      </div>
    </div>

    <script type="text/javascript">

      $(document).ajaxComplete(function(){
              $.fn.editable.defaults.mode = 'inline';

              $('.eip').editable({
                  type: 'number',
                  showbuttons: false,
                  type: 'number',
                  params: function(params) {
                    params["_token"] = '{{csrf_token()}}';
                    return params;
                }
              });
          });
          
        $(function () {
          $.fn.editable.defaults.mode = 'inline';
          
          $('#id_alat').select2({
            placeholder: '-Pilih Alat-',
            ajax: {
                    url: "{{route('alat.get_combo')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        q: params.term
                      };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

          var tableKebAlat = $('.keb-alat-datatable').DataTable({
              ordering: false,
              processing: true,
              serverSide: true,
              ajax: "{{ route('keb-alat.list',['id' => $data->id_praktikum]) }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'id_alat', name: 'id_alat'},
                  {data: 'formated_ajuan', name: 'formated_ajuan'},
                  {
                      data: 'action', 
                      name: 'action', 
                      width:"12%"
                    
                    
                  },
              ]
          });

          var form = '#formTambahKebAlat';

          $(form).on('submit', function(event){
              event.preventDefault();
              $('#simpanKebAlat').addClass('btn-progress');
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
                      tableKebAlat.ajax.reload();
                      $("#kebAlatMessages").append(`<div class="alert alert-success alert-dismissible show fade">
                          <div class="alert-body">
                              <button class="close" data-dismiss="alert">
                              <span>×</span>
                              </button>
                              `+response.success+`
                          </div>
                      </div>`);
                      $('#simpanKebAlat').removeClass('btn-progress');
                  },
                  error: function(response) {
                  }
              });
          });

          var deleteForm = "#formDeleteKebAlat";
            $(deleteForm).on('submit', function(event){
                event.preventDefault();
                $('#deleteAlatButton').addClass('btn-progress');
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
                        tableKebAlat.ajax.reload();
                        $('#exampleModal').modal('toggle');
                        $("#kebAlatMessages").append(`<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                <span>×</span>
                                </button>
                                `+response.success+`
                            </div>
                        </div>`);
                        $('#deleteAlatButton').removeClass('btn-progress');
                    },
                    error: function(response) {
                    }
                });
            });
          
            //Kebutuhan bahan
            $('#id_bahan').select2({
            placeholder: '-Pilih Bahan-',
            ajax: {
                    url: "{{route('bahan.get_combo')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        q: params.term
                      };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            var formbahan = '#formTambahKebBahan';
            $(formbahan).on('submit', function(event){
                event.preventDefault();
                $('#simpanKebBahan').addClass('btn-progress');
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
                        $(formbahan).trigger("reset");
                        tableKebBahan.ajax.reload();
                        $("#kebBahanMessages").append(`<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                <span>×</span>
                                </button>
                                `+response.success+`
                            </div>
                        </div>`);
                        $('#simpanKebBahan').removeClass('btn-progress');
                    },
                    error: function(response) {
                    }
                });
            });

            var tableKebBahan = $('.keb-bahan-datatable').DataTable({
              ordering: false,
              processing: true,
              serverSide: true,
              ajax: "{{ route('keb-bahan.list',['id' => $data->id_praktikum]) }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'id_bahan', name: 'id_bahan'},
                  {data: 'formated_ajuan', name: 'formated_ajuan'},
                  {data: 'satuan', name: 'satuan'},
                  {
                      data: 'action', 
                      name: 'action', 
                      width:"12%"
                  },
              ]
          });

          var deleteBahanForm = "#formDeleteKebBahan";
            $(deleteBahanForm).on('submit', function(event){
                event.preventDefault();
                $('#deleteBahanButton').addClass('btn-progress');
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
                        $(deleteBahanForm).trigger("reset");
                        tableKebBahan.ajax.reload();
                        $('#deleteKebBahanModal').modal('toggle');
                        $("#kebBahanMessages").append(`<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                <span>×</span>
                                </button>
                                `+response.success+`
                            </div>
                        </div>`);
                        $('#deleteBahanButton').removeClass('btn-progress');
                    },
                    error: function(response) {
                    }
                });
            });

          
        });

        function openDeleteKebAlat(id, name){
            $('#delete_alat').text(name);
            $("input[name=id_deleted_keb_alat]").val(id);
            $('#exampleModal').modal('toggle');
        }

        function openDeleteKebBahan(id, name){
            $('#delete_bahan').text(name);
            $("input[name=id_deleted_keb_bahan]").val(id);
            $('#deleteKebBahanModal').modal('toggle');
        }

      </script>

@endsection

