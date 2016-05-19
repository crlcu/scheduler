<div class="navbar-fixed">
    <nav>
        <div class="nav-wrapper">
            <ul class="left hide-on-med-and-down">
                <li><a href="/" class="waves-effect"><i class="material-icons">home</i></a></li>
                <li>
                    <a href="{{ action('TasksController@index') }}" class="waves-effect"><i class="material-icons right">content_paste</i> Tasks</a>
                </li>

                @yield('navbar-items')

                @if (Auth::user()->hasRole('manage-roles'))
                    <li>
                        <a href="{{ action('RolesController@index') }}" class="waves-effect"><i class="material-icons right">settings_input_composite</i> Roles</a>
                    </li>
                @endif
                @if (Auth::user()->hasRole('manage-groups'))
                    <li>
                        <a href="{{ action('GroupsController@index') }}" class="waves-effect"><i class="material-icons right">group</i> Groups</a>
                    </li>
                @endif
                @if (Auth::user()->hasRole('manage-users'))
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

            <ul id="slide-out" class="side-nav">
                <li><a href="/" class="waves-effect"><i class="material-icons left">home</i> Home</a></li>
                <li>
                    <a href="{{ action('TasksController@index') }}" class="waves-effect"><i class="material-icons left">content_paste</i> Tasks</a>
                </li>

                @yield('navbar-items')

                @if (Auth::user()->hasRole('manage-roles'))
                    <li>
                        <a href="{{ action('RolesController@index') }}" class="waves-effect"><i class="material-icons left">settings_input_composite</i> Roles</a>
                    </li>
                @endif
                @if (Auth::user()->hasRole('manage-groups'))
                    <li>
                        <a href="{{ action('GroupsController@index') }}" class="waves-effect"><i class="material-icons left">group</i> Groups</a>
                    </li>
                @endif
                @if (Auth::user()->hasRole('manage-users'))
                    <li>
                        <a href="{{ action('UsersController@index') }}" class="waves-effect"><i class="material-icons left">person</i> Users</a>
                    </li>
                @endif
            </ul>
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
        </div>
    </nav>
    
    <ul id="profile-dropdown" class="dropdown-content" style="margin-top: 65px;">
        <li><a href="{{ action('Auth\AuthController@logout') }}">Logout</a></li>
    </ul>
</div>

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function($) {
    $(".button-collapse").sideNav();
});
</script>
@endsection
