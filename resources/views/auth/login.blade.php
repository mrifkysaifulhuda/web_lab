@extends('layouts.auth')

@section('title', 'Login')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Login</h4>
        </div>

        @include('components.messages')

        <div class="card-body">
            <form method="POST"
                action="{{ route('login.perform') }}"
                class="needs-validation"
                novalidate="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required="required" autofocus>
                    <div class="invalid-feedback">
                        Please fill in your email
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password"
                            class="control-label">Password</label>
                        </div>
                    <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
                    <div class="invalid-feedback">
                        please fill in your password
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                            name="remember"
                            class="custom-control-input"
                            tabindex="3"
                            id="remember-me">
                        <label class="custom-control-label"
                            for="remember-me">Remember Me</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block"
                        tabindex="4">
                        Login
                    </button>
                </div>
            </form>
            

        </div>
    </div>
  
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
