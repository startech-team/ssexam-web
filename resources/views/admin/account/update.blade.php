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
                <a href="{{ url('admin/account') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center justify-content-end">
                <a data-toggle="modal" href="#myModal1" class="btn btn-sm btn-primary"><i class="bi bi-save"></i> 更新</a>
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
                    <h6 class="m-0 font-weight-bold text-primary">アカウント更新</h6>
                </div>
                <div class="card-body">
                    <form action="{{ url('/admin/account/update') }}" method="POST">
                        {{csrf_field()}}
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="username1" class="form-label">氏名</label>
                                <input type="text" class="form-control" id="username1" name="name" value="{{ $account->name }}">
                                @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                                <input type="hidden" name="id" value="{{ $account->id }}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="role" class="form-label">グループ</label>
                                <select class="form-control" id="role" name="group_id">
                                    @foreach ($groups as $g)
                                    <option value="{{ $g->group_id }}" {{ ($g->group_id== $account->group_id) ? 'selected' : '' }}>{{ $g->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="role" class="form-label">役職</label>
                                <select class="form-control" name="is_admin">
                                    <option value="2" {{ ($account->is_admin=='2')  ? 'selected' : '' }}>一般社員</option>
                                    <option value="3" {{ ($account->is_admin=='3')  ? 'selected' : '' }}>グループ主任</option>
                                    <option value="1" {{ ($account->is_admin=='1')  ? 'selected' : '' }}>管理者</option>
                                    <option value="4" {{ ($account->is_admin=='4')  ? 'selected' : '' }}>外部利用者</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="exampleInputEmail1" class="form-label">メール</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" name="email" readonly value="{{ $account->email }}">
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
                                        <p>{{ $account->name }}
                                            を更新します。よろしいでしょうか？
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