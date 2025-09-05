@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp
 
@if ($user->is_admin == 1)
<div style="margin-bottom: 25px;">
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">利用者</h6>
        </div>

        <div class="card-body">
            
            @foreach ( $userPercent as $info )
            <div class="row justify-content-center align-items-center">
                <div class="col-md-3">
                    <div class="text-size-14">
                        @if($info->is_admin == 1)
                        <span class="text-brown-color">管理者</span>
                        @elseif($info->is_admin == 2)
                        <span class="text-brown-color">一般ユーザ</span>
                        @elseif($info->is_admin == 3)
                        <span class="text-brown-color">グルプ主任</span>
                        @else
                        <span class="text-brown-color">外部利用者</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{$info->count}}%"
                            aria-valuenow="{{$info->count}}" aria-valuemin="0" aria-valuemax="100">
                            {{$info->count}}人
                        </div>
                    </div>
                </div>
                @if(!$loop->last)
                    <div class="col-md-12">
                        <hr>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif