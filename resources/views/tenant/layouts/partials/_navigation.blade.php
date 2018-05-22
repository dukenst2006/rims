<!-- General Links -->
<ul class="nav flex-column nav-pills">
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.dashboard', request()->tenant()->id), ' active') }}"
           href="{{ route('tenant.dashboard', request()->tenant()->id) }}">
            Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            Applications
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            Internships
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.projects.index'), ' active') }}"
           href="{{ route('tenant.projects.index') }}">
            Projects
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.account.index'), ' active') }}"
           href="{{ route('tenant.account.index') }}">
            Account overview
        </a>
    </li>
</ul>