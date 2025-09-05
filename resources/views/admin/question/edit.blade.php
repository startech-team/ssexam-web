@extends('layouts.default')

@section('content')
<div class="container-fluid">

    <div class="row mb-2">
        <div class="col-6">
            <div class="d-flex align-items-center">
                <a href="{{ url('admin/question') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center justify-content-end">
                <a data-toggle="modal" href="#myModal1" class="btn btn-sm btn-primary"><i class="bi bi-save"></i> 更新</a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header padding-x">
                    <h6 class="m-0 font-weight-bold text-primary">問題更新</h6>
                </div>
                <div class="card-body">

                    <form action="{{ url('/admin/question/update/') }}" method="POST">
                        {{csrf_field()}}
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="question_type" class="form-label">問題種類</label>
                                <select class="form-select form-control" name="category_id" id="category_id">
                                    @foreach ($categoryList as $c)
                                    <option value="{{$c->category_id}}" {{ ($c->category_id== $questions->category_id) ? 'selected' : '' }} >{{$c->category_nm}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="title" class="form-label">問題タイトル</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $questions->title) }}">
                                <input type="hidden" name="question_id" value="{{ $questions->question_id}}">
                                @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="body" class="form-label">問題内容</label>
                                <textarea class="form-control" aria-label="With textarea" name="body" id="body" value="{{ old('body', $questions->body) }}" style="height:300px">{{ $questions->body}}</textarea>
                                @if ($errors->has('body'))
                                <span class="text-danger">{{ $errors->first('body') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="option1" class="form-label">選択肢1</label>
                                <textarea class="form-control" name="option1" id="option1" style="height:100px;">{{ old('option1', $questions->option1) }}</textarea>
                                @if ($errors->has('option1'))
                                <span class="text-danger">{{ $errors->first('option1') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="option2" class="form-label">選択肢2</label>
                                <textarea class="form-control" name="option2" id="option2" style="height:100px;">{{ old('option2', $questions->option2) }}</textarea>
                                @if ($errors->has('option2'))
                                <span class="text-danger">{{ $errors->first('option2') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="option3" class="form-label">選択肢3</label>
                                <textarea class="form-control" name="option3" id="option3" style="height:100px;">{{ old('option3', $questions->option3) }}</textarea>
                                @if ($errors->has('ERQA0005'))
                                <span class="text-danger">{{ $errors->first('ERQA0005') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="option4" class="form-label">選択肢4</label>
                                <textarea class="form-control" name="option4" id="option4" style="height:100px;">{{ old('option4', $questions->option4) }}</textarea>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <label for="correct_answer" class="form-label">正当の回答</label>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group">
                                            <div class="form-check">
                                                <input name="correct_answer" class="form-check-input mt-1" type="radio" value="1" aria-label="Radio button for following text input" {{ old(('correct_answer')=="1" ? 'checked='.'"'.'checked'.'"' : '', ($questions->correct_answer == '1') ? 'checked' : '') }}>
                                                <label class="form-check-label" for="correct_answer1">
                                                    選択肢1
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <div class="form-check">
                                                <input name="correct_answer" che class="form-check-input mt-1" type="radio" value="2" aria-label="Radio button for following text input" {{ old(('correct_answer')=="2" ? 'checked='.'"'.'checked'.'"' : '', ($questions->correct_answer == '2') ? 'checked' : '') }}>
                                                <label class="form-check-label" for="correct_answer2">
                                                    選択肢2
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <div class="form-check">
                                                <input name="correct_answer" class="form-check-input mt-1" type="radio" value="3" aria-label="Radio button for following text input" {{ old(('correct_answer')=="3" ? 'checked='.'"'.'checked'.'"' : '', ($questions->correct_answer == '3') ? 'checked' : '') }}>
                                                <label class="form-check-label" for="correct_answer3">
                                                    選択肢3
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <div class="form-check">
                                                <input name="correct_answer" class="form-check-input mt-1" type="radio" value="4" aria-label="Radio button for following text input" {{ old(('correct_answer')=="4" ? 'checked='.'"'.'checked'.'"' : '', ($questions->correct_answer == '4') ? 'checked' : '') }}>
                                                <label class="form-check-label" for="correct_answer4">
                                                    選択肢4
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('correct_answer'))
                                <span class="text-danger">{{ $errors->first('correct_answer') }}</span>
                                @elseif ($errors->has('ERQA0006'))
                                <span class="text-danger">{{ $errors->first('ERQA0006') }}</span>
                                @elseif ($errors->has('ERQA0007'))
                                <span class="text-danger">{{ $errors->first('ERQA0007') }}</span>
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