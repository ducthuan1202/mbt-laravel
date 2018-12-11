@extends('layouts.app')

@section('content')

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <h1>Đăng Ký</h1>

                    @if(count($errors))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               name="name" value="{{ old('name') }}" placeholder="Tên" required autofocus/>
                    </div>

                    <div>
                        <input type="text" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                               name="mobile" value="{{ old('mobile') }}" placeholder="Số điện thoại" required />
                    </div>

                    <div>
                        <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" value="{{ old('email') }}" placeholder="Email hoặc SĐT" required />
                    </div>

                    <div>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               name="password" placeholder="Mật Khẩu" required />
                    </div>

                    <div>
                        <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                               name="password_confirmation" placeholder="Xác Nhận Mật Khẩu" required />
                    </div>

                    <div>
                        <button type="submit" class="btn btn-default submit">
                            {{ __('Đăng Ký') }}
                        </button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
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
