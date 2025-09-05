<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>エクスポートされたチャート</title>
    <style>
        @font-face {
            font-family: migmix;
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/migmix-2p-regular.ttf')}}") format('truetype');
        }

        @font-face {
            font-family: migmix;
            font-style: bold;
            font-weight: bold;
            src: url("{{ storage_path('fonts/migmix-2p-bold.ttf')}}") format('truetype');
        }

        body {
            font-family: migmix;
            line-height: 80%;
            font-size: 14px;
        }

        .chart-container {
            text-align: center;
            margin-top: 20px;
        }

        img {
            max-width: 100%;
            height: auto;
        }

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
            background-color: #B2D3c2;
            width: 100%;
            max-width: 400px;
            color: black;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            margin-bottom: 5px;
            margin-top: 5px;
            padding: 12px 0 0px 20px;
        }

        /* Normal Option Styling */
        .normal-div {
            background-color: #dcdcdc;
            width: 100%;
            max-width: 400px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            margin-bottom: 5px;
            margin-top: 5px;
            padding: 12px 0 0px 20px;
        }

        .names-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 10px;
        }

        .name-div {
            flex: 1 1 auto;
            min-width: 100px;
            margin: 5px;
            text-align: center;
        }

        .page-break {
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            /* Full width of the container */
            border-collapse: collapse;
            /* Merge borders */
            table-layout: fixed;
            /* Make sure cells are of equal width */
        }

        th,
        td {
            border: 1px solid #ccc;
            /* Border for cells */
            padding: 10px;
            /* Padding for aesthetics */
            text-align: center;
            /* Center text */
        }

        th {
            background-color: #f2f2f2;
            /* Optional: Header background color */
        }

        @media print {

            /* Media query to adjust layout on print */
            th,
            td {
                font-size: 14px;
                /* Adjust font size for better visibility */
            }
        }
    </style>
</head>

<body>
    <p style="font-size:20px;">質問に対して間違えたユーザ数</p>
    <div class="chart-container">
        @foreach ($chartImage as $index => $image)
        @if($index == 1)
        <br>
        <p style="text-align: left;font-size:19px;margin-top:10px">合格率</p>
        <img src="{{$image}}" style=" width: 500px;
            height: 500px; 
            object-fit: contain; " />

        @else
        <img src="{{ $image }}" alt="Chart Image">
        @endif
        @endforeach
    </div>
    @foreach ($examListByID as $data )

    <table style="width: 100%; text-align: center;">
        <tr>
            <td colspan="2" class="text-center align-middle">
                <div class="flex justify-center items-center" style="height: 100px;">
                    <img class="rounded-full" style="width: 100px; height: 100px;" src="data:image/jpeg;base64,{{$data->category_icon}}" alt="Image">
                </div>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">試験名</td>
            <td style="text-align: center;">{{$data->exam_nm}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">有効期間</td>
            <td style="text-align: center;">{{$data->start_dt}}~{{$data->end_dt}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">期間</td>
            <td style="text-align: center;">{{$data->duration}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">問題数</td>
            <td style="text-align: center;">{{$data->ques_count}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">合格率</td>
            <td style="text-align: center;">{{$data->win_rate}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">対象者</td>
            <td style="text-align: center;">{{$data->acc_count}}</td>
        </tr>
    </table>

    @endforeach

    <p class="mt-5">試験詳細</p>
    @foreach ( $questionDetail as $data )
    <div style="margin-bottom:10px">
        <div class="content border">
            <p class="mb-4 mt-3">問題{{$loop->iteration}}.　{{$data->body}}</p>
            <div class="form-group">
                <div class="form-check" style="display: flex; justify-content: center; align-items: center">
                    @if ($data->correct_answer=='1')
                    <div class="right-div page-break">
                        <span style="  border-radius: 5px;">
                            <p>1.{{$data->option1}} </p>
                        </span>
                    </div>
                    @else
                    <div class="notmal-div page-break">
                        <span style=" border-radius: 5px;">
                            <p>1.{{$data->option1}} </p>
                        </span>
                    </div>
                    @endif
                </div>
                <div class="form-check">
                    @if ($data->correct_answer == '2')
                    <div class="right-div page-break">
                        <span style="border-radius: 5px;">
                            <p>2. {{$data->option2}}</p>
                        </span>
                    </div>
                    @else
                    <div class="normal-div page-break">
                        <span style="border-radius: 5px;">
                            <p>2. {{$data->option2}}</p>
                        </span>
                    </div>
                    @endif
                </div>

                <div class="form-check">
                    @if ($data->correct_answer=='3')
                    <div class="right-div page-break">
                        <span style="  border-radius: 5px;">
                            <p>3.{{$data->option3}}</p>
                        </span>
                    </div>
                    @else
                    <div class="normal-div page-break">
                        <span style="  border-radius: 5px;">
                            <p>3.{{$data->option3}}</p>
                        </span>
                    </div>
                    @endif
                </div>
                <div class="form-check">
                    @if ($data->correct_answer=='4')
                    <div class="right-div page-break">
                        <span style="  border-radius: 5px;">
                            <p> 4.{{$data->option4}}</p>
                        </span>
                    </div>
                    @else
                    <div class="normal-div page-break">
                        <span style="border-radius: 5px;">
                            <p>4.{{$data->option4}}</p>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @if(!empty($data->usernames))
        <table>
            <tbody>
                <tr>
                    <!-- @foreach ($data->usernames as $id)

                    <td>{{$id}}</td>

                    @endforeach -->
                    @foreach ($data->usernames as $index => $id)
                    @if ($index % 3 === 0)
                <tr>
                    @endif
                    <td>{{$id}}</td>
                    @if ($index % 3 === 2 || $index === count($data->usernames) - 1)
                </tr>
                @endif
                @endforeach
                </tr>
            </tbody>
        </table>
        @endif
    </div>
    @endforeach
    @include('admin.dashboard.details.resultDetail')
</body>

</html>