        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            @php
                $shop = null;
                if(Auth::check() && null !== auth()->user()->managing_shop) {
                    $shop = auth()->user()->shops()->find(auth()->user()->managing_shop);
                }
            @endphp

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon">
                    @if($shop && $shop->shop_image_url)
                        <img class="rounded-circle" src="{{ $shop->shop_image_url }}" style="width: 50px; height: 50px; object-fit:contain;">
                    @endif
                </div>
                @if($shop && $shop->shop_name)
                    <div class="sidebar-brand-text mx-3">{{ $shop->shop_name }}</div>
                @else
                    <div class="sidebar-brand-text mx-3">{{ trans('general.welcome') }}</div>
                @endif
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::is('home') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-store"></i>
                    <span>{{ trans('general.my_shops') }}</span></a>
            </li>

            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <!-- Divider -->
            <hr class="sidebar-divider">
            @endif


            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('stats') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('shop_stats') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ trans('general.sales_dashboard') }}</span>
                </a>
            </li>
            @endif

            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('analytics') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('shop_analitycs') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ trans('general.visitors') }}</span>
                </a>
            </li>
            @endif

            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('orders') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('shop_orders')}}">
                    <i class="fas fa-box"></i>
                    <span>
                        {{ trans('general.orders') }} 
                        @if(auth()->user()->unshippedOrdersCount())
                            <span class="badge badge-danger">{{ auth()->user()->unshippedOrdersCount() }}</span>
                        @endif
                    </span>
                </a>
            </li>
            @endif

            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('products') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('products')}}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>{{ trans('general.products') }}</span>
                </a>
            </li>
            @endif

 
            @if(Auth::check() && null !== auth()->user()->managing_shop && auth()->user()->isDropshipping())
            <li class="nav-item {{ Request::is('cj-dropshipping') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('cj_dropshipping_index')}}">
                <img src="https://d4.alternativeto.net/JckDYVSASfKTxwq1vFr5LHmsKcCBL0ozTwZHQZniqG8/rs:fill:280:280:0/g:ce:0:0/YWJzOi8vZGlzdC9pY29ucy9jamRyb3BzaGlwcGluZ18xODM5NTUucG5n.png" style="height: 20px;">
                    <span>{{ trans('general.cj_dropshipping_menu_edit') }}</span>
                </a>
            </li>
            @endif
            
            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('categories') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('categories') }}">
                <i class="far fa-list-alt"></i>
                    <span>{{ trans('general.categories') }}</span>
                </a>
            </li>
            @endif

            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('collections') || Request::is('collection/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('collections') }}">
                <i class="fas fa-layer-group"></i>
                    <span>{{ trans('general.collections') }}</span>
                </a>
            </li>
            @endif


            <!-- @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item disabled">
                <a class="nav-link" href="#">
                    <i class="fas fa-box"></i>
                    <span>Collections (coming soon)</span>
                </a>
            </li>

            @endif -->


            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('variants') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('variant_view') }}">
                <i class="fas fa-dna"></i>
                    <span>{{ trans('general.variants') }}</span>
                </a>
            </li>
            @endif


            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('store/shipping') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('shipping') }}">
                <i class="fas fa-shipping-fast"></i>
                    <span>{{ trans('general.shipping') }}</span>
                </a>
            </li>
            @endif

            @if(Auth::check() && null !== auth()->user()->managing_shop && auth()->user()->isDropshipping())
            <li class="nav-item {{ Request::is('withdrawals') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('withdrawals_view') }}">
                <i class="fas fa-euro-sign"></i>
                    <span>{{ trans('general.withdrawals') }}</span>
                </a>
            </li>
            @endif

            @if(Auth::check() && null !== auth()->user()->managing_shop)
            <li class="nav-item {{ Request::is('store/*') && !Request::is('store/shipping') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('manage_shop', auth()->user()->managing_shop) }}">
                <i class="fas fa-store-alt"></i>
                    <span>{{ trans('general.shop_settings') }}</span>
                </a>
            </li>
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider">

            @if(Auth::check())
            <!-- Heading -->
            <div class="sidebar-heading">
                {{ trans('general.account') }}
            </div>


            <li class="nav-item {{ Request::is('account/referral') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('referral_view') }}">
                    <i class="fas fa-share-alt"></i>
                    <span>{{ trans('general.referral') }}</span></a>
            </li>

            <li class="nav-item {{ (preg_match('/^account\/support/', Request::path()) || preg_match('/^ticket/', Request::path())) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('support_view') }}">
                    <i class="fas fa-question-circle"></i>
                    <span>{{ trans('general.help') }}</span>
                    @php
                        $user_unread_tickets = \App\Models\SupportTickets::where('user_seen', '0')->where('user_id', auth()->user()->id)->count();
                    @endphp
                    @if($user_unread_tickets)
                        <span class="badge badge-success">{{ $user_unread_tickets }}</span>
                    @endif
                </a>
            </li>


            <li class="nav-item {{ Request::is('account/settings') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('edit') }}">
                    <i class="fas fa-cog"></i>
                    <span>{{ trans('general.account') }} {{ trans('general.settings') }}</span></a>
            </li>

            @endif

            

            @if(auth()->check() && auth()->user()->admin == 'yes')
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Admin
                </div>

                <li class="nav-item {{ Request::is('admin/users') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin_users') }}">
                        <i class="fas fa-user"></i>
                        <span>Users</span></a>
                </li>

                <li class="nav-item {{ Request::is('admin/stores*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin_stores') }}">
                        <i class="fas fa-store-alt"></i>
                        <span>Stores</span></a>
                </li>

                <li class="nav-item {{ Request::is('admin/support*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin_support') }}">
                        <i class="fas fa-question-circle"></i>
                        <span>Tickets</span>
                        @php
                            $unread_tickets = \App\Models\SupportTickets::where('admin_seen', '0')->count();
                        @endphp
                        @if($unread_tickets)
                            <span class="badge badge-success">{{ $unread_tickets }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item {{ Request::is('admin/orders*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin_orders') }}">
                        <i class="fas fa-box"></i>
                        <span>
                            Orders
                            @php
                                $admin_unpaid_orders = \App\Models\Orders::where('cj_paid', 'no')->where('finished', 'yes')->count();
                            @endphp
                            @if($admin_unpaid_orders)
                                <span class="badge badge-danger">{{ $admin_unpaid_orders }}</span>
                            @endif
                        </span>
                    </a>
                </li>

                <li class="nav-item {{ Request::is('admin/help-pages') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin_help_pages') }}">
                        <i class="fas fa-file"></i>
                        <span>Help Pages</span></a>
                </li>
            @endif


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->