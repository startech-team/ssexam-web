@extends('layouts.app')
<style>
    .navbar {
        display: none !important;
    }

    body {
        /* background-image:url('http://gifgifs.com//animations/nature/stars/Stars_flash.gif');  */
        /* background-image:url('https://bestanimations.com/media/stars/475964289colorful-falling-stars-lemat-gif.gif'); */
        /* background-image:url('https://bestanimations.com/media/stars/9682300exploding-colorful-star-gif.gif#.YvmasFKz92I.link'); */
        background-image: url('https://bestanimations.com/media/stars/1399486584lemat-falling-golden-stars-gif.gif#.YvmasIM7XQs.link');
        /* background-image:url('https://bestanimations.com/media/stars/1594754744tumblr_ovz82fgWx91vsjcxvo1_500.gif#.YvnGx-WdclI.link'); */
        background-repeat: repeat;
    }
</style>
@section('content')
<div class="container">
    <div class="vh-100">
        <div class="py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body text-center login-div">
                            @error('login-error')
                            <span class="text-danger" role="">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="sidebar-brand d-flex align-items-center justify-content-center user-nav mb-5 mt-3">
                                    <div style="width:35px;height:35px;">
                                        <div class="sidebar-brand-icon rotate-n-15">
                                            <i class='fas fa-star-half-alt' style="color:#4e73df;font-size:2rem;"></i>
                                        </div>
                                    </div>
                                    <div class="fs-7 fw-bold sidebar-brand-text mx-3" style="color:#4e73df;font-size:30px;font-weight: bold;">SS EXAM</div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="email" class="text-left col-md-12 pl-0">メール</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-outline mb-5">
                                    <label for="password" class="text-left col-md-12 pl-0">パスワード</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('passsword') }}" autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <button class="btn btn-primary btn-lg btn-block" style="font-weight: bold;" type="submit">ログイン</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection