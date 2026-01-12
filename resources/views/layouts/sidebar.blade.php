<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
      <div class="logo-wrapper"><a href="{{ route('dashboard')}}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="{{ route('dashboard')}}"></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn">
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                </li>

                @foreach(App\Helpers\MenuHelper::getSidebarMenu() as $menu)
                    @can($menu->permission_name)
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="{{ $menu->url != '#' ? url($menu->url) : '#' }}">
                                <span><i class="{{ $menu->icon }}"></i> {{ $menu->title }}</span>
                            </a>
                            
                            @if($menu->children->count() > 0)
                                <ul class="sidebar-submenu">
                                    @foreach($menu->children as $sub)
                                        @can($sub->permission_name)
                                            <li>
                                                @if($sub->children->count() > 0)
                                                    <a class="submenu-title" href="#">
                                                        <span>{{ $sub->title }}</span>
                                                    </a>
                                                    <ul class="nav-sub-childmenu submenu-content">
                                                        @foreach($sub->children as $child)
                                                            @can($child->permission_name)
                                                                <li><a href="{{ url($child->url) }}">{{ $child->title }}</a></li>
                                                            @endcan
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <a href="{{ url($sub->url) }}">{{ $sub->title }}</a>
                                                @endif
                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endcan
                @endforeach
            </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
