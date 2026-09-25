{{--Marksheet--}}
<li class="nav-item">
    <a href="{{ route('marks.year_select', Qs::hash(Auth::user()->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.show', 'marks.report_card', 'marks.year_selector', 'pins.enter']) ? 'active' : '' }}"><i class="icon-book"></i> Marksheet &amp; report card</a>
</li>
