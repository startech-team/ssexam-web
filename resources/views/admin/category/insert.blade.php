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
                <a href="{{ url('admin/category') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
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
                    <h6 class="m-0 font-weight-bold text-primary">カテゴリー登録</h6>
                </div>
                <div class="card-body">

                    <form action="{{ url('/admin/category/store') }}" method="POST" enctype="multipart/form-data">

                        {{csrf_field()}}


                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="username1" class="form-label">カテゴリ名</label>
                                <input type="text" class="form-control" name="category_nm" id="category_nm" value="{{ old('category_nm') }}">
                                @if ($errors->has('category_nm'))
                                <span class="text-danger">{{ $errors->first('category_nm') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="username1" class="form-label">カテゴリ種類</label>
                                <select class="form-select form-control" name="category_type" id="category_type">
                                    <option value="1">問題</option>
                                    <option value="2">勉強</option>
                                    <option value="3">用語</option>
                                    <option value="4">試験</option>
                                </select>
                                @if ($errors->has('category_type'))
                                <span class="text-danger">{{ $errors->first('category_type') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="username1" class="form-label">アイコン</label>
                                <input type="file" class="form-control" name="category_icon" id="category_icon" onchange="previewImage(event)" accept=".jpg, .jpeg, .png">
                                @if ($errors->has('category_icon'))
                                <span class="text-danger">{{ $errors->first('category_icon') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <img id="imagePreview" src="" alt="Image Preview" style="display:none; max-width: 200px; margin-top: 10px;">
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
                    <script>
                        function previewImage(event) {
                            const file = event.target.files[0];
                            const maxSize = 1 * 1024 * 1024;// 1MB
                            if(file){
                                if(file.size > maxSize){
                                    alert("アイコンを最大1MBまでにしてください。");
                                    $('#category_icon').val("");
                                    return;
                                }

                                var reader = new FileReader();
                                reader.onload = function() {
                                    var output = document.getElementById('imagePreview');
                                    output.src = reader.result;
                                    output.style.display = 'block';
                                }
                                reader.readAsDataURL(event.target.files[0]);
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection