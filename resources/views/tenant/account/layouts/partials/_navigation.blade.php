<!-- General Links -->
<ul class="nav flex-column nav-pills">
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.account.index'), ' active') }}"
           href="{{ route('tenant.account.index') }}">
            Account overview
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.account.profile.index'), ' active') }}"
           href="{{ route('tenant.account.profile.index') }}">
            Profile
        </a>
    </li>
</ul>