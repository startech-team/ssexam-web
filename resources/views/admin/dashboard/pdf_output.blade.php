<html lang="ja">

<head>
    <title>試験詳細</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

        table {
            width: 45%;
            border-spacing: 0px;
        }

        td,
        th {
            border: 1px solid #000;
        }

        table tr th {
            background-color: #4e73df;
            color: #fff;
            text-align: left;
            height: 30px;
            width: 70px;
            padding-left: 10px;
        }

        table tr td {
            color: #000;
            text-align: left;
            height: 30px;
            padding-left: 10px;
        }
    </style>
</head>

<body>
    <h2>{{ $exam_nm }}</h2>
    <div style="width: 100%;margin-bottom: 120px;">
        <table style="float: left;">
            <tr>
                <th>氏名</th>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <th>受験日</th>
                <td>{{ $take_exam_dt }}</td>
            </tr>
        </table>
        <table style="float: right;">
            <tr>
                <th>結果</th>
                <td>{{ $result }}</td>
            </tr>
            <tr>
                <th>点数</th>
                <td>{{ $correct_answer_count }} / {{ $question_count }}</td>
            </tr>
        </table>
    </div>
    @foreach($details as $d)
    <p style="overflow-wrap: anywhere;margin-bottom: 20px;margin-top: 20px;" class="mb-4 font-weight-bold">
        {{ $loop->index +1 }} <span>.</span> {{ $d->body }}
    </p>
    <div style="margin-left: 30px;margin-bottom: 10px;">
        <div style="padding-bottom: 10px;">
            @if( '1' == $d->correct_answer and $d->correct_answer == $d->my_answer )
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;width: 30px;">◎</span>
            @elseif( '1' == $d->correct_answer and $d->correct_answer != $d->my_answer)
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;width: 30px;">◎</span>
            @elseif( '1' != $d->correct_answer and '1' == $d->my_answer )
            <span style="color: #e74a3b;font-weight: bold;font-size: 18px;width: 30px;">×&nbsp;</span>
            @else
            <span style="font-weight: bold;font-size: 18px;">&nbsp;&nbsp;&nbsp;</span>
            @endif
            ①&nbsp;&nbsp;{{ $d->option1 }}
        </div>
        <div style="padding-bottom: 10px;">
            @if( '2' == $d->correct_answer and $d->correct_answer == $d->my_answer )
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;width: 30px;">◎</span>
            @elseif( '2' == $d->correct_answer and $d->correct_answer != $d->my_answer)
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;width: 30px;">◎</span>
            @elseif( '2' != $d->correct_answer and '2' == $d->my_answer )
            <span style="color: #e74a3b;font-weight: bold;font-size: 18px;width: 30px;">×&nbsp;</span>
            @else
            <span style="font-weight: bold;font-size: 18px;">&nbsp;&nbsp;&nbsp;</span>
            @endif
            ②&nbsp;&nbsp;{{ $d->option2 }}
        </div>
        <div style="padding-bottom: 10px;">
            @if( '3' == $d->correct_answer and $d->correct_answer == $d->my_answer )
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;width: 30px;">◎</span>
            @elseif( '3' == $d->correct_answer and $d->correct_answer != $d->my_answer)
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;width: 30px;">◎</span>
            @elseif( '3' != $d->correct_answer and '3' == $d->my_answer )
            <span style="color: #e74a3b;font-weight: bold;font-size: 18px;width: 30px;">×&nbsp;</span>
            @else
            <span style="font-weight: bold;font-size: 18px;">&nbsp;&nbsp;&nbsp;</span>
            @endif
            ③&nbsp;&nbsp;{{ $d->option3 }}
        </div>
        <div style="padding-bottom: 10px;">
            @if( '4' == $d->correct_answer and $d->correct_answer == $d->my_answer )
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;">◎</span>
            @elseif( '4' == $d->correct_answer and $d->correct_answer != $d->my_answer)
            <span style="color: #1cc88a;font-weight: bold;font-size: 18px;">◎</span>
            @elseif( '4' != $d->correct_answer and '4' == $d->my_answer )
            <span style="color: #e74a3b;font-weight: bold;font-size: 18px;">×&nbsp;</span>
            @else
            <span style="font-weight: bold;font-size: 18px;">&nbsp;&nbsp;&nbsp;</span>
            @endif
            ④&nbsp;&nbsp;{{ $d->option4 }}
        </div>
    </div>
    <div style="margin-left: 30px;margin-bottom: 30px;">
        @if( $d->correct_answer == $d->my_answer )
        <div style="background-color: #1cc88a;color:#fff;text-align: center;width: 80px;height: 30px;border-radius: 5px; padding-top: 7px;font-weight: bold;">◎ 正解</div>
        @else
        <div style="background-color: #e74a3b;color:#fff;text-align: center;width: 80px;height: 30px;border-radius: 5px; padding-top: 7px;font-weight: bold;">× 不正解</div>
        @endif
    </div>
    <hr style="width: 100%;color:gray;">
    @endforeach
</body>

</html>