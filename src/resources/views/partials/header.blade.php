<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar"
            data-class="c-sidebar-show">
        <svg class="c-icon c-icon-lg">
            <use xlink:href=""></use>
        </svg>
    </button>
    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar"
            data-class="c-sidebar-lg-show" responsive="true">
        <svg class="c-icon c-icon-lg">
            <use xlink:href=""></use>
        </svg>
    </button>
    <ul class="c-header-nav ml-auto mr-4">
        <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#"
                                                  role="button" aria-haspopup="true" aria-expanded="false">
                <div class="c-avatar"><img class="c-avatar-img" src="{{asset(auth()->user()->avatar ?? '')}}"
                                           alt="user@email.com"
                                           title="{{auth()->user()->name ?? ''}}"></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
                <div class="dropdown-header bg-light py-2"><strong>پنل کاربری</strong></div>
                <a class="dropdown-item" href="/admin/profile">
                    <svg class="c-icon mr-2">
                        <use xlink:href=""></use>
                    </svg>
                    پروفایل</a>

                <a class="dropdown-item" href="/admin/change-password">
                    <svg class="c-icon mr-2">
                        <use xlink:href=""></use>
                    </svg>
                    تغییر پسورد</a>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="/admin/logout"
                   onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    <svg class="c-icon mr-2">
                        <use xlink:href=""></use>
                    </svg>
                    خروج</a>

                <form id="logout-form"
                      action="/admin/logout"
                      method="POST"
                      style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
    </ul>
</header>
