                <!-- sidebar -->
                <div class="col-12 col-sm-12 col-md-12 col-lg-4 mb-4">
            <div class="card shadow">
            <ul class="list-group">
                <a href="{{ route('manage_shop') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('manage_shop') ? 'active' : '' }}" aria-current="true">
                        <i class="fas fa-cog"></i> {{ trans('general.customize') }}
                    </li>
                </a>
                <a href="{{ route('themes') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('themes') ? 'active' : '' }}">
                        <i class="fas fa-paint-brush"></i> {{ trans('general.themes') }}
                    </li>
                </a>
                <a href="{{ route('custom_css') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('custom_css') ? 'active' : '' }}">
                        <i class="fas fa-code"></i> {{ trans('general.custom_design') }}
                    </li>
                </a>
                <!-- <a href="{{ route('plugins') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('plugins') ? 'active' : '' }}">
                        <i class="fas fa-puzzle-piece"></i> {{ trans('general.plugins') }}
                    </li>
                </a> -->
                <a href="{{ route('shop_payment_method') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('shop_payment_method') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave"></i> {{ trans('general.payment_method') }}
                    </li>
                </a>
                <a href="{{ route('my_domains') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('my_domains') ? 'active' : '' }}">
                        <i class="fas fa-globe"></i> {{ trans('general.custom_domain') }}
                    </li>
                </a>
                <a href="{{ route('shop_package_upgrade') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('shop_package_upgrade') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i> {{ trans('general.upgrade_package') }}
                    </li>
                </a>

                <a href="{{ route('shop_change_type_view') }}" style="text-decoration: none;">
                    <li class="list-group-item {{ request()->routeIs('shop_change_type_view') ? 'active' : '' }}">
                        <i class="fas fa-store"></i> {{ trans('general.change_store_type') }}
                    </li>
                </a>

                @if(auth()->user()->isDropshipping())
                    <a href="{{ route('cj_dropshipping_edit') }}" style="text-decoration: none;">
                        <li class="list-group-item {{ request()->routeIs('cj_dropshipping_edit') ? 'active' : '' }}">
                            <img src="https://d4.alternativeto.net/JckDYVSASfKTxwq1vFr5LHmsKcCBL0ozTwZHQZniqG8/rs:fill:280:280:0/g:ce:0:0/YWJzOi8vZGlzdC9pY29ucy9jamRyb3BzaGlwcGluZ18xODM5NTUucG5n.png" style="height: 20px;"></i> {{ trans('general.cj_dropshipping_menu_edit') }}
                        </li>
                    </a>
                @endif
            </ul>
            </div>
        </div>