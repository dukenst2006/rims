<ul class="nav flex-column nav-pills">
    <li class="nav-item">
        <a href="{{ route('account.dashboard') }}"
           class="nav-link{{ return_if(on_page('account.dashboard'), ' active') }}">
            Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.education.index') }}"
           class="nav-link{{ return_if(on_page('account.education.index'), ' active') }}">
            Education
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.skills.index') }}"
           class="nav-link{{ return_if(on_page('account.skills.index'), ' active') }}">
            My Skills
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.languages.index') }}"
           class="nav-link{{ return_if(on_page('account.languages.index'), ' active') }}">
            My Languages
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.portfolios.index') }}"
           class="nav-link{{ return_if(on_page('account.portfolios.index'), ' active') }}">
            My Portfolios
        </a>
    </li>
</ul>