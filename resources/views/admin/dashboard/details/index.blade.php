@extends('layouts.default')
@section('content')
<div class="container-fluid mb-5">
    <!-- Content Row -->
    <div class="mb-3">

    <div class="d-flex align-items-center mb-2">
        <a href="{{ url('admin') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
        <!-- <button type="button" onclick="exportChartToPDF()" class="btn btn-sm btn-primary shadow float-end" style="margin-bottom:10px">PDFにエクスポート</button> -->

        <input type="hidden" name="examID" id="examID" value="{{$examID}}"/>
        @include('admin.dashboard.details.examDetail')
        @include('admin.dashboard.details.analyticsByQuestion')
        @include('admin.dashboard.details.questionDetail')
        @include('admin.dashboard.details.resultDetail')

    </div>
</div>
<script>
    
</script>
@endsection