<div class="col-md-3 left_col">
    <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
        <a href="{{ Url::to('/admin') }}" class="site_title"><span>{{ Config::get('app.app_title') }}</span></a>
        </div>
        <div class="clearfix"></div>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
                <ul class="nav side-menu">
                    @if ( Auth::user()->hasRole('administrator') )
                    <li><a href="javascript:void(0);">
                            <i class="fa fa-user"></i> User <span class="fa fa-chevron-down"></span>
                        </a>
                            <ul class="nav child_menu" style="display: none">
                                <li><a href="{{ route('admin::user::new') }}">Add New</a></li>
                                <li><a href="{{ route('admin::user::index') }}">View All</a></li>
                                <li><a href="{{ route('admin::user::ucodes') }}">User Ucodes</a></li>
                                <li><a href="{{ route('admin::user::pendings') }}">Pendings Verification</a></li>
                            </ul>
                        </li>
                         <li><a href="javascript:void(0);">
                                 <i class="fa fa-newspaper-o"></i> Post <span class="fa fa-chevron-down"></span>
                             </a>
                            <ul class="nav child_menu" style="display: none">                                
                                <li><a href="{{ Url::to('/admin/post/add-new') }}">Add New</a></li>
                                <li><a href="{{ Url::to('/admin/post') }}">View All</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0);">
                                <i class="fa fa-folder"></i> Category <span class="fa fa-chevron-down"></span>
                            </a>
                            <ul class="nav child_menu" style="display: none">
                                <li><a href="{{ Url::to('/admin/categories/new') }}">Add New</a></li>
                                <li><a href="{{ Url::to('/admin/categories') }}">View All</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0);">
                                <i class="fa fa-film"></i> Media <span class="fa fa-chevron-down"></span>
                            </a>
                            <ul class="nav child_menu" style="display: none">
                                <li><a href="{{ Url::to('/admin/media/reports') }}">Reports</a></li>
                                <li><a href="{{ route('admin::media::index') }}">View All</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0);">
                                <i class="fa fa-info"></i> Learn <span class="fa fa-chevron-down"></span>
                            </a>
                            <ul class="nav child_menu" style="display: none">
                                <li><a href="{{ Url::to('/admin/learns/new') }}">Add New</a></li>
                                <li><a href="{{ Url::to('/admin/learns') }}">View All</a></li>
                            </ul>
                        </li>
                        <li>
                          <a href="{{ Url::to('/admin/basiccollection') }}"><i class="fa fa-clone"></i> Basic Collections</span></a>
                        </li>
                    @endif

                    <li><a href="{{ Url::to('/user/logout') }}"><i class="fa fa-sign-out"></i> Log Out</span></a>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

    </div>
</div> <!-- col-md-3 left_col -->
