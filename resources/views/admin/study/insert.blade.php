@extends('layouts.default')

@section('content')
<div class="container-fluid">

    <div class="row mb-2">
        <div class="col-6">
            <div class="d-flex align-items-center">
                <a href="{{ url('admin/study') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
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
            <!-- Custom Text Color Utilities -->
            <div class="card shadow mb-4">
                <div class="card-header padding-x">
                    <h6 class="m-0 font-weight-bold text-primary">勉強登録</h6>
                </div>
                <div class="card-body">
                    <form action="{{ url('/admin/study/store') }}" method="POST">
                    {{csrf_field()}}
                    <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="category_type" class="form-label">勉強カテゴリー</label>
                                <select class="form-select form-control" name="category_id" id="category_id">
                                    @foreach ($categoryType as $q)
                                    <option value="{{$q->category_id}}" {{ old('category')== $q->category_id ? 'selected='.'"'.'selected'.'"' : '' }}>{{$q->category_nm}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="title" class="form-label">勉強タイトル</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
                                @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                    </div>
                    <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="body" class="form-label">精細</label>
                                <textarea class="form-control" name="body" id="body" style="height: 300px;">{{ old('body') }}</textarea>
                                @if ($errors->has('body'))
                                <span class="text-danger">{{ $errors->first('body') }}</span>
                                @endif
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
                                            登録します。よろしいでしょうか？
                                        </p>
                                    </div>
                                    <div class="modal-footer" style="border: none;">
                                        <button type="submit" id="formSubmit" class="btn btn-primary">OK</button>
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