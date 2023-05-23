@extends('layouts.app')

@push('style')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
@endpush

@section('main')


<div class="main-content" style="min-height: 368px;">
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
            <h2 class="section-title"> {{ $data->name}}</h2>
            
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                  <ul class="nav nav-pills" id="myTab3" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" href="/user/show/{{$data->id}}" role="tab" aria-controls="home" aria-selected="true">Detail</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active show" id="profile-tab3"  href="/user/laboratorium/{{$data->id}}" role="tab" aria-controls="profile" aria-selected="false">Laboratorium</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                  <div class="card-header">
                  <h4>Daftar Laboratorium</h4>
                  <div class="card-header-action">
                    <button onclick="openTambahLab()" class="btn btn-primary">Tambah Lab</button>
                  </div>
                 </div>
                 <div id="messages">
                    </div>
                 <div class="table-responsive table-invoice">
                    <table class="table table-bordered yajra-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lab</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Laboratorium</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ Form::open(array('id'=>'formTambahLab', 'data-action' => route('user-lab.store_lab') )) }}
            {{ Form::hidden('id_user', $data->id) }}
            <div class="modal-body">
            {{ Form::select("id_laboratorium", $data->listLab ,null, ["class" => "form-control"]) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="simpanButton">Ok</button>
            </div>
            {{ Form::close() }}
            </div>
        </div>
 </div>

  <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ Form::open(array('id'=>'formDeleteLab', 'data-action' => route('user-lab.destroy_lab') )) }}
            {{ Form::hidden('id_deleted_lab', null) }}
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
              serverSide: true,
              ajax: "{{ route('user-lab.list',['id' => $data->id]) }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'id_laboratorium', name: 'id_laboratorium'},
                  {
                      data: 'action', 
                      name: 'action', 
                      width:"15%"
                    
                    
                  },
              ]
          });

          var form = '#formTambahLab';

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
                    $('#exampleModal').modal('toggle');
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

        var deleteForm = "#formDeleteLab";
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
                        $('#deleteModal').modal('toggle');
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

        function openTambahLab(){
           
            $('#exampleModal').modal('toggle');
        }

        function openDeleteLab(id){
            $("input[name=id_deleted_lab]").val(id);
            $('#deleteModal').modal('toggle');
        }

</script>

@endsection