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
          <h2 class="section-title">Editor</h2>
            <p class="section-lead">WYSIWYG editor and code editor.</p>

                <form method="post" action="{{ route('user.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Full Summernote</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"  required="required" autofocus>
                                            @if ($errors->has('name'))
                                                <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email address</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"  required="required">
                                            @if ($errors->has('email'))
                                                <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                                        <div class="col-sm-12 col-md-7">
                                            {{ Form::select("role", array("laboran" => "laboran", "kepala laboratorium" => "kepala laboratorium") ,null, ["class" => "form-control"]) }}
                                            @if ($errors->has('role'))
                                                <span class="text-danger text-left">{{ $errors->first('role') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                                        <div class="col-sm-12 col-md-7">
                                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
                                            @if ($errors->has('password'))
                                                <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Confirm Password</label>
                                        <div class="col-sm-12 col-md-7">
                                        <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Password" required="required">
                                            @if ($errors->has('password_confirmation'))
                                                <span class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
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
    </section>
</div>

@endsection