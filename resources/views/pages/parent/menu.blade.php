<li class="nav-item">
    <a href="{{ route('parent.dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'parent.dashboard' ? 'active' : '' }}"><i class="icon-home4"></i> Parent dashboard</a>
</li>
<li class="nav-item">
    <a href="{{ route('my_children') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_children']) ? 'active' : '' }}"><i class="icon-users4"></i> My Children</a>
</li>
