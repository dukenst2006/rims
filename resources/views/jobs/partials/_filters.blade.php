<ul class="nav flex-column nav-pills">
    <!-- Industries -->
    <li class="nav-item">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
           aria-haspopup="true" aria-expanded="false">
            Industries
        </a>
        <ul class="dropdown-menu">
            @foreach($categories as $category)
                <li>
                    <a class="dropdown-item" href="#">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </li>

    <!-- Skills -->
    <li class="nav-item">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
           aria-haspopup="true" aria-expanded="false">
            Skills
        </a>
        <ul class="dropdown-menu">
            @foreach($skills as $skill)
                <li>
                    <a class="dropdown-item" href="#">
                        {{ $skill->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
</ul>
<hr>

<!-- Job Type -->
<div class="list-group mb-3">
    <div class="list-group-item">
        Type
    </div>
    <a href="#" class="list-group-item list-group-item-action">
        Full time
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        Part time
    </a>
</div>

<!-- Location -->
<div class="list-group mb-3">
    <div class="list-group-item">
        Location
    </div>
    <a href="#" class="list-group-item list-group-item-action">
        On site
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        Remote (off site)
    </a>
</div>

<!-- Education -->
<div class="list-group mb-3">
    <div class="list-group-item">
        Education
    </div>
    @foreach($education_levels as $level)
        <a href="#" class="list-group-item list-group-item-action">
            {{ $level->name }}
        </a>
    @endforeach
</div>

<!-- Skill Level -->
<div class="list-group mb-3">
    <div class="list-group-item">
        Skill level
    </div>
    @foreach($levels as $level)
        <a href="#" class="list-group-item list-group-item-action">
            {{ $level->name }}
        </a>
    @endforeach
</div>

<!-- Skill Level -->
<div class="list-group mb-3">
    <div class="list-group-item">
        Languages
    </div>
    @foreach($languages as $language)
        <a href="#" class="list-group-item list-group-item-action">
            {{ $language->name }}
        </a>
    @endforeach
</div>
