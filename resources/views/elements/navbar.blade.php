<nav>
    <div class="nav-wrapper">
        <ul class="left">
            <li><a href="/" class="waves-effect"><i class="material-icons">home</i></a></li>
            <li>
                <a href="{{ action('TasksController@index') }}" class="waves-effect"><i class="material-icons right">content_paste</i> Tasks</a>
            </li>

            @yield('navbar-items')

            @if (Auth::user()->group->has_role('manage-roles'))
                <li>
                    <a href="{{ action('RolesController@index') }}" class="waves-effect"><i class="material-icons right">settings_input_composite</i> Roles</a>
                </li>
            @endif
            @if (Auth::user()->group->has_role('manage-groups'))
                <li>
                    <a href="{{ action('GroupsController@index') }}" class="waves-effect"><i class="material-icons right">group</i> Groups</a>
                </li>
            @endif
            @if (Auth::user()->group->has_role('manage-users'))
                <li>
                    <a href="{{ action('UsersController@index') }}" class="waves-effect"><i class="material-icons right">person</i> Users</a>
                </li>
            @endif
        </ul>

        <ul class="right">
            <li>
                <a class="dropdown-button" href="#!" data-activates="profile-dropdown">
                    {{ Auth::user()['name'] }}<i class="material-icons right">arrow_drop_down</i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<ul id="profile-dropdown" class="dropdown-content" style="margin-top: 65px;">
    <li><a href="{{ action('Auth\AuthController@logout') }}">Logout</a></li>
</ul>
