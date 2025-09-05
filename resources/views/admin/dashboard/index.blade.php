@extends('layouts.default')
@section('content')
<div class="container-fluid mb-5">
    <!-- Content Row -->
    <div class="mb-3">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
        @include('admin.dashboard.userPercentage')
        @include('admin.dashboard.groupPercentage')
        @include('admin.dashboard.examOverview')
        @include('admin.dashboard.questionGroup')
    </div>
</div>

@endsection