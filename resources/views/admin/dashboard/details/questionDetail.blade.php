<h6 class="m-0 font-weight-bold text-primary">試験詳細</h6>
<div class="mb-3"></div>
@foreach ( $questionDetail as $data )
<div class="mb-3 text-size-14 text-brown-color">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><strong>問題{{$loop->iteration}}</strong>. {{$data->body}}</span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="form-check">
                    @if ($data->correct_answer=='1')
                    <div class="right-div">
                        <span style="padding: 5px; border-radius: 5px;">
                            1. {{$data->option1}}
                        </span>
                    </div>
                    @else
                    <div class="normal-div">
                        <span style=" padding: 5px; border-radius: 5px;">
                            1. {{$data->option1}}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="form-check">
                    @if ($data->correct_answer=='2')
                    <div class="right-div">
                        <span style="padding: 5px; border-radius: 5px;">
                            2. {{$data->option2}}
                        </span>
                    </div>
                    @else
                    <div class="normal-div">
                        <span style=" padding: 5px; border-radius: 5px;">
                            2. {{$data->option2}}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="form-check">
                    @if ($data->correct_answer=='3')
                    <div class="right-div">
                        <span style=" padding: 5px; border-radius: 5px;">
                            3. {{$data->option3}}
                        </span>
                    </div>
                    @else
                    <div class="normal-div">
                        <span style=" padding: 5px; border-radius: 5px;">
                            3. {{$data->option3}}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="form-check">
                    @if ($data->correct_answer=='4')
                    <div class="right-div">
                        <span style=" padding: 5px; border-radius: 5px;">
                            4. {{$data->option4}}
                        </span>
                    </div>
                    @else
                    <div class="normal-div">
                        <span style="padding: 5px; border-radius: 5px;">
                            4. {{$data->option4}}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="ml-4">
            @if ($data->userIdlist != null)
                    @php
                        $nameList = explode(',', $data->userIdlist);
                    @endphp
                    @foreach ($nameList as $name)
                    <span class="text-danger">
                        {{ $name }}
                        @if ($loop->last)
                        @else
                            、
                        @endif
                    </span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    $(document).ready(function() {
        $("#dataTable_length").hide();
        $("#dataTable_info").hide();
    });
</script>

<style>
    
    /* General Container */
    .container {
        margin-top: 50px;
    }

    /* Divider Line */
    .divider {
        height: 1px;
        background-color: #ddd;
        margin: 20px 0;
    }

    /* Content Box */
    .content {
        padding: 10px;
        background-color: #f1f5f9;
        /* Light background for better readability */
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        /* Soft shadow for depth */
    }

    /* Correct Answer Styling */
    .right-div {
        background:rgba(19, 204, 62, 0.83);
        width: 100%;
        max-width: 600px;
        min-height: 45px; /* 高さを固定でなく、テキストに応じて可変にする場合は height → min-height に */
        color: white;
        display: flex;
        align-items: center;
        justify-content: flex-start; /* 左寄せ */
        border-radius: 5px;
        margin-bottom: 8px;
        padding: 0 12px; /* 左右に余白 */
        text-align: left;
        word-break: break-word; /* 単語の途中でも折り返す */
        white-space: normal; /* テキストを折り返し可能に */
    }

    /* Normal Option Styling */
    .normal-div {
        background:rgb(241, 241, 241);
        width: 100%;
        max-width: 600px;
        min-height: 45px; /* 高さを固定でなく、テキストに応じて可変にする場合は height → min-height に */
        display: flex;
        align-items: center;
        justify-content: flex-start; /* 左寄せ */
        border-radius: 5px;
        margin-bottom: 8px;
        padding: 0 12px; /* 左右に余白 */
        text-align: left;
        word-break: break-word; /* 単語の途中でも折り返す */
        white-space: normal; /* テキストを折り返し可能に */
    }

    /* Name Container Styling */
    .names-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        /* Center content */
        align-items: center;
    }

    /* Individual Name Div */
    .name-div {
        background: linear-gradient(135deg, #ff7e79 0%, #ffb199 100%);
        width: 100px;
        margin: 5px;
        text-align: center;
        border-radius: 5px;
        flex-shrink: 0;
        height: 40px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    /* Responsive Adjustments */
    @media (max-width: 600px) {
        .name-div {
            width: 70px;
        }
    }

    @media (max-width: 400px) {
        .name-div {
            width: 60px;
        }
    }
</style>