<!-- Sidebar-->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/admin')}}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class='fas fa-star-half-alt'></i>
        </div>
        <div class="sidebar-brand-text mx-3">SS EXAM&nbsp;&nbsp;</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Dashboard -->
    <li class="{{ ($activePage == 'dashboard') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin')}}" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>ダッシュボード</span>
        </a>
    </li>
    <!-- アカウント -->
    @if(auth()->user()->is_admin == 1)
    <li class="{{ ($activePage == 'group') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/group')}}" aria-expanded="true" aria-controls="collapseTwo">
            <i class='fas fa-users'></i>
            <span>グループ</span>
        </a>
    </li>
    @endif
    <li class="{{ ($activePage == 'account') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/account')}}" aria-expanded="true" aria-controls="collapseTwo">
            <i class='fas fa-user-alt'></i>
            <span>アカウント</span>
        </a>
    </li>
    @if(auth()->user()->is_admin == 3)
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/user')}}" aria-expanded="true" aria-controls="collapseTwo">
            <i class='bi bi-book-fill'></i>
            <span>My Exam</span>
        </a>
    </li>
    @endif
    @if(auth()->user()->is_admin == 1)
    <!-- Nav Item - Pages Collapse Menu -->
    <!-- <li class="{{ ($activePage == 'questionType') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/questionType')}}" aria-expanded="true" aria-controls="collapsePages">
            <i class='fas fa-rss'></i>
            <span>問題種類</span>
        </a>
    </li> -->
    <li class="{{ ($activePage == 'question') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/question')}}" aria-expanded="true" aria-controls="collapsePages">
            <i class='fas fa-question-circle'></i>
            <span>問題</span>
        </a>
    </li>
    <li class="{{ ($activePage == 'exam') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/exam')}}" aria-expanded="true" aria-controls="collapsePages">
            <i class='fas fa-marker'></i>
            <span>試験</span>
        </a>
    </li>
    <li class="{{ ($activePage == 'study') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/study')}}" aria-expanded="true" aria-controls="collapsePages">
            <i class='fas fa-book-open'></i>
            <span>勉強</span>
        </a>
    </li>
    <li class="{{ ($activePage == 'term') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/term')}}" aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-font"></i>
            <span>用語</span>
        </a>
    </li>
    <li class="{{ ($activePage == 'category') ? 'nav-item active' : 'nav-item' }}">
        <a class="nav-link collapsed" href="{{ url('/admin/category')}}" aria-expanded="true" aria-controls="collapsePages">
            <i class='far fa-list-alt'></i>
            <span>カテゴリ</span>
        </a>
    </li>
    @endif
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto" style="color: #fff;">
                <span>Copyright &copy; STAR SE</span>
            </div>
        </div>
    </footer>
</ul>