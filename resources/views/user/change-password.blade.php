@extends('layouts.user')
<style>
    .exam-block {
        background: #ffffff;
        border: 2px solid #d0d1d5;
        border-radius: 10px;
        font-size: 0.95rem;
    }

    .bd-bottom {
        border-bottom: 1px solid #d0d1d5;
    }

    .bd-bottom:last-child {
        border: none;
    }

    .exam-block a {
        color: #ffffff;
    }
</style>
@section('content')
<div class="container user-sp">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <span>{{ $message }}</span>
        <button type="button" class="close-btn close position-absolute" data-dismiss="alert" aria-label="Close" style="right:10px;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">パスワード変更</h6>
        </div>
        <div class="card-body">

            <form action="{{ url('/user/changePasswordOk') }}" method="POST">

                {{csrf_field()}}

                <div class="col-12 col-lg-12 row mb-4">
                    <div class="col-12 col-lg-5 col-md-12 col-sm-12">
                        <label for="username1" class="form-label">新規パスワード</label>
                        <input type="password" class="form-control" name="new_password" value="{{ old('new_password') }}">
                        @if ($errors->has('new_password'))
                        <span class="text-danger">{{ $errors->first('new_password') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-lg-12 row mb-4">
                    <div class="col-12 col-lg-5 col-md-12 col-sm-12">
                        <label for="username1" class="form-label">再確認パスワード</label>
                        <input type="password" class="form-control" name="confirm_password" value="{{ old('confirm_password') }}">
                        @if ($errors->has('confirm_password'))
                        <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-12 mb-3 row">
                    <div class="col-12 col-lg-5 col-md-12 col-sm-12">
                        <a data-toggle="modal" href="#myModal1" class="btn btn-primary col-4 btn btn-primary">変更</a>
                    </div>
                </div>
                <div class="modal" id="myModal1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="border: none;">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body" style="text-align:center;">
                                <p>
                                    パスワードを変更します。よろしいでしょうか？
                                </p>
                            </div>
                            <div class="modal-footer" style="border: none;">
                                <button type="submit" class="btn btn-primary">OK</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection