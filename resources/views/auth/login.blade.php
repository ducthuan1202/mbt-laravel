@extends('layouts.app')

@section('content')

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h1>Tài Khoản</h1>

                    @if(count($errors))
                        <div class="text-danger alert">Đăng nhập thất bại.</div>
                    @endif
                    @if($message = Session::get('success'))
                        <div class="text-success alert">{{$message}}</div>
                    @endif

                    <div>
                        <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email hoặc SĐT" autofocus/>
                    </div>
                    <div>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Mật Khẩu" />
                    </div>

                    <div>
                        <button type="submit" class="btn btn-default submit">{{ __('Đăng Nhập') }}</button>
                        {{--<div class="form-check">--}}
                            {{--<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}
                            {{--<label class="form-check-label" for="remember">--}}
                                {{--{{ __('Nhớ đăng nhập') }}--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    </div>

                    <div class="clearfix"></div>
                </form>
            </section>
        </div>
    </div>
@endsection

