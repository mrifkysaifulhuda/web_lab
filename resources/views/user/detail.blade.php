@extends('layouts.app')

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
                        <a class="nav-link active show" id="home-tab3" href="/user/show/{{$data->id}}" role="tab" aria-controls="home" aria-selected="true">Detail</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab3"  href="/user/laboratorium/{{$data->id}}" role="tab" aria-controls="profile" aria-selected="false">Laboratorium</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama</label>
                      <div class="col-sm-12 col-md-7 col-form-label">
                      {{ $data->name}}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                      <div class="col-sm-12 col-md-7 col-form-label">
                      {{ $data->email }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                      <div class="col-sm-12 col-md-7 col-form-label">
                      {{ $data->role }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
           
          </div>
        </section>
</div>

@endsection