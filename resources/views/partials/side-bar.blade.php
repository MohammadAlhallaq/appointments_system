<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 {{@app()->getLocale() === 'ar' ? 'fixed-end me-3 rotate-caret' : 'fixed-start ms-3'}}  bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute {{@app()->getLocale() == 'ar' ? 'start-0' : 'end-0'}} top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard "
           target="_blank">
            <img src="./assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="{{@app()->getLocale() == 'ar' ? 'me-1' : 'ms-1'}} font-weight-bold text-white">Material Dashboard 2</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse {{@app()->getLocale() == 'ar' ? 'w-auto' : ''}} w-auto max-height-vh-100"
         id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{@app()->getLocale() == 'ar' ? '' : 'text-white active bg-gradient-primary'}}"
                   href="./pages/dashboard.html">
                    <div
                        class="text-white text-center {{@app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}} d-flex align-items-center justify-content-center">
                        <i class="{{@app()->getLocale() == 'ar' ? 'material-icons-round' : 'material-icons'}} opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text {{@app()->getLocale() == 'ar' ? 'me-1' : 'ms-1'}}">{{ __('Dashboard') }}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
