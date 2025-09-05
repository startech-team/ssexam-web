@extends('layouts.default')

@section('content')
<div class="container-fluid">

    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif

    <div class="row mb-2">
        <div class="col-6">
            <div class="d-flex align-items-center">
                <a href="{{ url('admin/group') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center justify-content-end">
                <a data-toggle="modal" href="#myModal1" class="btn btn-sm btn-primary"><i class="bi bi-save"></i> 登録</a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- First Column -->
        <div class="col-12">

            @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
            @endif
            <!-- Custom Text Color Utilities -->
            <div class="card shadow mb-4">
                <div class="card-header padding-x">
                    <h6 class="m-0 font-weight-bold text-primary">グループ登録</h6>
                </div>
                <div class="card-body">

                    <form action="{{ url('/admin/group/store') }}" method="POST">

                        {{csrf_field()}}
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="username1" class="form-label">グループ名</label>
                                <input type="text" class="form-control" name="group_name" id="group_name" value="{{ old('group_name') }}">
                                @if ($errors->has('group_name'))
                                <span class="text-danger">{{ $errors->first('group_name') }}</span>
                                @endif
                                @if ($errors->has('ERGP0001'))
                                <span class="text-danger">{{ $errors->first('ERGP0001') }}</span>
                                @endif
                                @if ($errors->has('ERGP0002'))
                                <span class="text-danger">{{ $errors->first('ERGP0002') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="modal" id="myModal1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="border: none;">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body" style="text-align:center;">
                                        <p>
                                            登録します。よろしいでしょうか？
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
    </div>
</div>
@endsection