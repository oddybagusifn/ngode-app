<div class="navigation d-flex justify-content-center align-items-center" style="width: 200px">
    <div class=" d-flex justify-content-center">
        <ul class="navbar-nav gap-5 ">
            <li class="nav-item">
                <a href="/admin">
                    <img class="img-fluid" style="width: 70px"
                        src="{{ request()->is('admin') ? '/img/adminIcon1-active.svg' : '/img/adminIcon1.svg' }}"
                        alt="">
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/create">
                    <img class="img-fluid" style="width: 70px"
                        src="{{ request()->is('admin/create') ? '/img/adminIcon2-active.svg' : '/img/adminIcon2.svg' }}"
                        alt="">
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/profile">
                    <img class="img-fluid" style="width: 70px"
                        src="{{ request()->is('admin/profile') ? '/img/adminIcon3.svg' : '/img/adminIcon3.svg' }}"
                        alt="">
                </a>
            </li>
        </ul>

    </div>
</div>
