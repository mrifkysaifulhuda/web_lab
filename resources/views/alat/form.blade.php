@extends('layouts.app')

@section('main')

@php
    if(isset($data)):
        $model = $data;
        $action = "update";
        $edit = true;
        $route = ['alat.'.$action, $model->id_alat];
    else:
        $model = array();
        $action = "store";
        $edit = false;
        $route = ['alat.'.$action];
    endif;

@endphp


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
            <h2 class="section-title">Editor</h2>
            <p class="section-lead">WYSIWYG editor and code editor.</p>

            {{ Form::model($model,array( 'route' => $route,'class'=>'form-horizontal','files'=>false)) }}
            @if ($edit)
                @method('PUT')
            @endif
            
            @csrf
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Full Summernote</h4>
                  </div>
                  <div class="card-body">
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Alat</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::text("nm_alat", null, ["class" => "form-control"]) }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Merek</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::text("merek", null, ["class" => "form-control"]) }}
                      </div>
                    </div>

                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Profil</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::textarea("profil", null, ["class" => "form-control", "style" => "height: 150px;"]) }}

                      
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Instruksi</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::textarea("instruksi", null, ["class" => "form-control", "style" => "height: 150px;"]) }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                      <div class="col-sm-12 col-md-7">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

@endsection