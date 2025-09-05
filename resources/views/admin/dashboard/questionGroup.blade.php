@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp

@if ($user->is_admin == 1)
<div style="margin-bottom: 25px;">
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">問題</h6>
        </div>

        <div class="card-body">
            <div class="d-flex flex-wrap">
                @foreach ( $questionGroup as $data )
                <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                    <div class="card text-size-14">
                        <div class="d-flex justify-content-center align-items-center">
                            <img class="rounded-pill"
                                    style="width: 100px; height: 100px;"
                                    src="data:image/jpeg;base64,{{$data->category_icon}}" alt="Image" style="max-width: 300px; max-height: 300px;">
                        </div>
                        <div class="card-body">
                            <span class="text-brown-color d-flex justify-content-center align-items-center">{{$data->category_nm}}</span>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-brown-color">{{$data->question_count}}問</span>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <a href="{{ url('admin/question?question_type=' . $data->category_id . '&title_detail=') }}"><i class="bi bi-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endif