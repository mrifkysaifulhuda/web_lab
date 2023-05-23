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
                    <h4>Rekapitulasi Kebutuhan Laboratorium</h4>
                  </div>
                 
                  <div class="card-body">
                    <div id="messages">
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tahun</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::open(array('id'=>'formKebutuhanLab', 'url' => route('lab'), 'method'=> 'get')) }}
                        {{ Form::select("tahun", array("2023" => "2023", "2022" => "2022") ,$data['tahun'], ["class" => "form-control","id"=> "pilih_tahun"]) }}
                      {{ Form::close() }} 
                     </div>
                    </div>
                    @if(auth()->user()->role == "kepala laboratorium")
                        @if($data['status'] != "Disetujui")
                        <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                        {{ Form::open(array('id'=>'formSetujuiKebutuhan', 'url' => route('keb-lab.setujui') )) }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            {{ Form::hidden('selected_year', $data['tahun']) }} 
                            <button class="btn btn-primary" type="submit">Setujui</button>
                        {{ Form::close() }} 
                        </div>
                        </div>
                        @else
                            <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                            {{ Form::open(array('id'=>'formExportKebutuhan', 'url' => route('keb-lab.export'), 'method'=> 'get' )) }}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                {{ Form::hidden('selected_year', $data['tahun']) }} 
                                <button class="btn btn-primary" type="submit">Export to CSV</button>
                            {{ Form::close() }} 
                            </div>
                            </div>
                        @endif
                    @endif
                  </div>
                 
                </div>
              </div>
        </div>
        <div class="row">
                <div class="col-12">
                    <div class="card mb-0">
                        <div class="card-header pb-0 pt-0" style="min-height:0">
                            <h4>Kebutuhan Alat</h4>
                        </div>
                    </div>
                    <div class="card-body p-3">
                      <div class="table-responsive table-invoice">
                        <table class="table table-bordered keb-alat-lab-datatable">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Nama Alat</th>
                                  <th>Merek</th>
                                  <th>Stok</th>
                                  <th>Kebutuhan</th>
                                  <th>Praktikum</th>
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
                <div class="col-12 mt-3">
                    <div class="card mb-0">
                        <div class="card-header pb-0 pt-0" style="min-height:0">
                            <h4>Kebutuhan Bahan</h4>
                        </div>
                    </div>
                    <div class="card-body p-3">
                      <div class="table-responsive table-invoice">
                        <table class="table table-bordered keb-bahan-lab-datatable">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Nama Bahan</th>
                                  <th>Satuan</th>
                                  <th>Desrkipsi</th>
                                  <th>Stok</th>
                                  <th>Kebutuhan</th>
                                  <th>Praktikum</th>
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

    $(function(){

        $('#pilih_tahun').on( "change", function() {
            $('#formKebutuhanLab').submit();
        } );

        var tableKebAlat = $('.keb-alat-lab-datatable').DataTable({
              ordering: false,
              processing: true,
              serverSide: true,
              ajax: "{{ route('keb-alat-lab.list',['tahun' => $data['tahun']]) }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'nm_alat', name: 'nm_alat'},
                  {data: 'merek', name: 'merek'},
                  {data: 'stok', name: 'stok'},
                  {data: 'formated_ajuan', name: 'formated_ajuan'},
                  {data: 'praktikum', name: 'praktikum'},
              ]
          });

          var tableKebBahan = $('.keb-bahan-lab-datatable').DataTable({
              ordering: false,
              processing: true,
              serverSide: true,
              ajax: "{{ route('keb-bahan-lab.list',['tahun' => $data['tahun']]) }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'nm_bahan', name: 'nm_bahan'},
                  {data: 'satuan', name: 'satuan'},
                  {data: 'deskripsi', name: 'deskripsi'},
                  {data: 'stok', name: 'stok'},
                  {data: 'formated_ajuan', name: 'formated_ajuan'},
                  {data: 'praktikum', name: 'praktikum'},
              ]
          });
    })

</script>
@endsection