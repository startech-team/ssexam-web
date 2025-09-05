@extends('layouts.default')

@section('content')
<div class="container-fluid">

    <div class="row mb-2">
        <div class="col-6">
            <div class="d-flex align-items-center">
                <a href="{{ url('admin/term') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
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
            <!-- Custom Text Color Utilities -->
            <div class="card shadow mb-4">
                <div class="card-header padding-x">
                    <h6 class="m-0 font-weight-bold text-primary">用語情報</h6>
                </div>
                <div class="card-body">

                    <form action="{{ url('/admin/term/update/'.$term->term_id )}}" method="POST">
                        {{csrf_field()}}
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="question_type" class="form-label">用語カテゴリー</label>
                                <select class="form-select form-control" name="category_id" id="category_id">
                                    @foreach ($categorylist as $c)
                                    <option value="{{$c->category_id}}" {{ ($c->category_id== $term->category_id) ? 'selected' : '' }} >{{$c->category_nm}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="word" class="form-label">用語の言葉</label>
                                <input type="text" class="form-control" name="word" id="word" value="{{ old('word', $term->word) }}">
                                <input type="hidden" name="term_id" value="{{ $term->term_id}}">
                                @if ($errors->has('word'))
                                <span class="text-danger">{{ $errors->first('word') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="body" class="form-label">用語の説明</label>
                                <textarea class="form-control" aria-label="With textarea" name="explanation" id="explanation" value="{{ old('explanation', $term->explanation) }}" style="height:300px">{{ $term->explanation}}</textarea>
                                @if ($errors->has('explanation'))
                                <span class="text-danger">{{ $errors->first('explanation') }}</span>
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
                                            更新します。よろしいでしょうか？
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