@hasrole(config('ikCommerce.superAdminRole'))
    <li class="has-children">
        <a href="#0">Administrateurs</a>

        <ul>
            <li>
                <a href="{{ route('admin.admin.index') }}">
                    Administrateurs
                </a>
            </li>
            <li>
                <a href="{{ route('admin.permissions.index') }}">
                    Permissions
                </a>
            </li>
            <li>
                <a href="{{ route('admin.roles.index') }}">
                    Rôles
                </a>
            </li>
        </ul>
    </li>
@else
    <li>
        <a href="{{ route('admin.admin.index') }}">
            Administrateurs
        </a>
    </li>
@endhasrole