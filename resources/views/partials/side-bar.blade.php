<aside
    @class(['sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 bg-gradient-dark', 'fixed-end me-3 rotate-caret' => $lang == 'ar', 'fixed-start ms-3' => $lang != 'ar'])
    id="sidenav-main">
    <div class="sidenav-header">
        <i @class(['fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute top-0 d-none d-xl-none', 'start-0' => $lang == 'ar',  'end-0' => $lang != 'ar' ])aria-hidden="true"
           id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard "
           target="_blank">
            <img src="./assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span @class(['font-weight-bold text-white', 'me-1' => $lang == 'ar', 'ms-1' => $lang == 'ar'])>Material Dashboard 2</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">

    <div
        @class(['collapse navbar-collapse w-auto max-height-vh-100', 'w-auto' => $lang == 'ar']) id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active text-white bg-gradient-primary {{$lang == 'ar' ? '' : ''}}"
                   href="./pages/dashboard.html">
                    <div @class(['text-white text-center d-flex align-items-center justify-content-center', 'ms-2' => $lang == 'ar' , 'me-2' => $lang != 'ar'])
                    ">
                    <i @class(['material-icons-round' => $lang == 'ar', 'material-icons' => $lang != 'ar']) opacity-10">dashboard</i>
    </div>
    <span @class(['nav-link-text', 'me-1' => $lang == 'ar' , 'ms-1' => $lang != 'ar'])>{{ __('Dashboard') }}</span>
    </a>
    </li>
    </ul>
    </div>
</aside>
