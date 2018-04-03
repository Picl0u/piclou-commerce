<nav class="sidebar-navigation">
    <ul>
        <li>
            <a href="{{ route('user.account') }}">
                {{ __('user::user.my_account') }}
            </a>
        </li>
        <li>
            <a href="{{ route('user.infos') }}">
                {{ __('user::user.my_informations') }}
            </a>
        </li>
        <li>
            <a href="{{ route('user.addresses') }}">
                {{ __('user::user.my_addresses') }}
            </a>
        </li>
        <li>
            <a href="{{ route('order.index') }}">
                {{ __('user::user.my_orders') }}
            </a>
        </li>
        <li>
            <a href="{{ route('whishlist.index') }}">
                {{ __('user::user.my_whishlist') }}
            </a>
        </li>
        <li class="user-logout">
            <a href="{{ route('logout') }}">
                {{ __('user::user.logout') }}
            </a>
            <form class="logout-form" action="{{ route('logout') }}" method="POST">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</nav>