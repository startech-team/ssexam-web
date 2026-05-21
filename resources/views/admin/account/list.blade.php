@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        @php
            $user = \Illuminate\Support\Facades\Auth::user();
        @endphp

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <span>{{ $message }}</span>
                <button type="button" class="close-btn close d-flex justify-content-end" data-dismiss="alert"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <span>{{ $message }}</span>
                <button type="button" class="close-btn close d-flex justify-content-end" data-dismiss="alert"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($user->is_admin == '1')
            <div class="row mb-2">
                <div class="col-8 d-flex justify-content-start">
                    <form action="{{ url('/admin/account') }}" method="GET" role="form" enctype="multipart/form-data">
                        <div class="input-group">
                            <input type="text" name="name_mail" class="form-control form-control-sm mr-2"
                                value="{{ $name_mail }}" placeholder="氏名・メール">

                            <select name="group_id" class="form-control form-control-sm mr-2">
                                <option value="">すべて</option>

                                @foreach ($groups as $g)
                                    <option value="{{ $g->group_id }}" {{ $group_id == $g->group_id ? 'selected' : '' }}>
                                        {{ $g->group_name }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-sm btn-outline-primary" type="submit" style="font-size: 10px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-4 d-flex justify-content-end">
                    <a href="{{ url('/admin/account/insert') }}" type="button" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i>&nbsp;新規作成
                    </a>
                </div>
            </div>
        @endif

        @if (!empty($accs) && count($accs) > 0)
            <div class="table-responsive dataTables_Tablet">

                <table class="table table-bordered bg-white" id="dataTable" cellspacing="0">
                    <thead>
                        <tr class="text-brown-color text-size-14">
                            <th scope="col">#</th>
                            <th scope="col">氏名</th>
                            <th scope="col">グループ</th>
                            <th scope="col">役職</th>
                            <th scope="col">メール</th>
                            <th scope="col">ステータス</th>
                            <th scope="col">操作</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($accs as $a)
                            <tr class="text-brown-color text-size-14">

                                <td>{{ $a->no ?? '' }}</td>

                                <td>
                                    @if ($user->is_admin == '1')
                                        <a href="{{ url('/admin/account/edit/' . $a->id) }}">
                                            {{ $a->name ?? '' }}
                                        </a>
                                    @else
                                        <span>{{ $a->name ?? '' }}</span>
                                    @endif
                                </td>

                                {{-- FIXED --}}
                                <td>{{ $a->group_name ?? '' }}</td>

                                <td>
                                    @if (($a->is_admin ?? '') == '2')
                                        <span>一般社員</span>
                                    @elseif (($a->is_admin ?? '') == '1')
                                        <span>管理者</span>
                                    @elseif (($a->is_admin ?? '') == '3')
                                        <span>グループ主任</span>
                                    @else
                                        <span>外部ユーザー</span>
                                    @endif
                                </td>

                                <td>{{ $a->email ?? '' }}</td>

                                <td>
                                    @if (($a->status_nm ?? '') == '有効')
                                        <a data-toggle="modal" data-id="{{ $a->id }}"
                                            data-target="#status{{ $a->id }}" class="btn btn-secondary btn-sm"
                                            style="font-size: 10px;">
                                            無効
                                        </a>
                                    @else
                                        <a data-toggle="modal" data-id="{{ $a->id }}"
                                            data-target="#status{{ $a->id }}" class="btn btn-success btn-sm"
                                            style="font-size: 10px;">
                                            有効
                                        </a>
                                    @endif
                                </td>

                                <td>

                                    {{-- Password Reset --}}
                                    <a data-toggle="modal" data-id="{{ $a->id }}"
                                        data-target="#pwdReset{{ $a->id }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-key"></i>
                                    </a>

                                    {{-- Delete --}}
                                    @if ($user->is_admin == '1')
                                        <a data-toggle="modal" data-id="{{ $a->id }}"
                                            data-target="#delete{{ $a->id }}" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    @endif

                                    {{-- Reset Password Modal --}}
                                    <form action="{{ url('/admin/account/resetPwd/' . $a->id) }}" method="POST">
                                        {{ csrf_field() }}

                                        <div class="modal" id="pwdReset{{ $a->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header" style="border: none;">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">
                                                            ×
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-center">
                                                        <p>
                                                            {{ $a->name ?? '' }}
                                                            のパスワードをリセットします。よろしいでしょうか？
                                                        </p>
                                                    </div>

                                                    <div class="modal-footer" style="border: none;">
                                                        <button type="submit" class="btn btn-primary">
                                                            OK
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    {{-- Status Modal --}}
                                    <form action="{{ url('/admin/account/changeStatus/' . $a->id) }}" method="POST">

                                        {{ csrf_field() }}

                                        <div class="modal" id="status{{ $a->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header" style="border: none;">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">
                                                            ×
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-center">
                                                        <p>
                                                            {{ $a->name ?? '' }}
                                                            アカウントを
                                                            <b>{{ $a->status_nm ?? '' }}</b>
                                                            にしますか？
                                                        </p>
                                                    </div>

                                                    <div class="modal-footer" style="border: none;">
                                                        <button type="submit" class="btn btn-primary">
                                                            OK
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    {{-- Delete Modal --}}
                                    <form action="{{ url('/admin/account/delete/' . $a->id) }}" method="POST">

                                        {{ csrf_field() }}

                                        <div class="modal" id="delete{{ $a->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header" style="border: none;">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">
                                                            ×
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-center">
                                                        <p>
                                                            {{ $a->name ?? '' }}
                                                            を削除します。よろしいでしょうか？
                                                        </p>
                                                    </div>

                                                    <div class="modal-footer" style="border: none;">
                                                        <button type="submit" class="btn btn-primary">
                                                            OK
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if ($accs->hasPages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">

                            {{-- Previous --}}
                            @if ($accs->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $accs->previousPageUrl() }}" rel="prev">
                                        &laquo;
                                    </a>
                                </li>
                            @endif

                            {{-- Pages --}}
                            @foreach ($accs->links()->elements[0] ?? [] as $page => $url)
                                @if ($page == $accs->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if ($accs->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $accs->nextPageUrl() }}" rel="next">
                                        &raquo;
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            @endif

                        </ul>
                    </nav>
                @endif

            </div>
        @endif

    </div>
@endsection