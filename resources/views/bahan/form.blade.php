@extends('layouts.app')

@section('main')

@php
    if(isset($data)):
        $model = $data;
        $action = "update";
        $edit = true;
        $route = ['bahan.'.$action, $model->id_bahan];
    else:
        $model = array();
        $action = "store";
        $edit = false;
        $route = ['bahan.'.$action];
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
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Bahan</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::text("nm_bahan", null, ["class" => "form-control"]) }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Satuan</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::select("satuan", array('mg'=>'mg', 'gram' => 'gram', 'liter' => 'liter', 'mL'=> 'mL', 'lembar' => 'lembar')  , null, ["class" => "form-control"]) }}
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deskripsi</label>
                      <div class="col-sm-12 col-md-7">
                      {{ Form::textarea("deskripsi", null, ["class" => "form-control", "style" => "height: 150px;"]) }}
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