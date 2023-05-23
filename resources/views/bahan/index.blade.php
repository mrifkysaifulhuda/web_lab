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

<div class="main-content" style="min-height: 593px;">
        <section class="section">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4>Bahan</h4>
                  <div class="card-header-action">
                   
                    <a href="bahan/create" class="btn btn-primary">Tambah Bahan</a>
                  </div>
                </div>
                <div class="card-body p-3">
                  <div class="table-responsive table-invoice">
                    <table class="table table-bordered yajra-datatable">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Nama Bahan</th>
                              <th>Deskripsi</th>
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

    
      <script type="text/javascript">
        $(function () {
          
          var table = $('.yajra-datatable').DataTable({
              ordering: false,
              processing: true,
              serverSide: true,
              ajax: "{{ route('bahan.list') }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'nm_bahan', name: 'nm_bahan'},
                  {data: 'deskripsi', name: 'deskripsi'},
                  {
                      data: 'action', 
                      name: 'action', 
                      width:"15%"
                    
                    
                  },
              ]
          });
          
        });
      </script>

@endsection
