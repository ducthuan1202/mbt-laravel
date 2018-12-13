@extends('layouts.app')

@section('content')

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h1>Đăng Nhập</h1>

                    @if($message = Session::get('success'))
                        <div class="alert alert-success">{{$message}}</div>
                    @endif

                    <div>
                        <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" value="{{ old('email') }}" placeholder="Email hoặc SĐT" required autofocus/>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div>
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               name="password" placeholder="Mật Khẩu" required />

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Nhớ đăng nhập') }}
                            </label>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">
                            {{ __('Login') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="reset_pass" href="{{ route('password.request') }}">{{ __('Quên mật khẩu?') }}</a>
                        @endif
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-paw"></i> T2 CRM</h1>
                            <p>©2018 All Rights Reserved. Guno.vn</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection

