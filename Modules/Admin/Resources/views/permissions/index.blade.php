@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Administrateurs</span></li>
            <li><span>Permissions</span></li>
        </ul>
    </nav>

    <h2>
        Permissions
        <span> - Gérez les permissions pour vos administrateurs</span>
    </h2>
@endsection

@section('content')
    <nav class="tabs" data-component="tabs">
        <ul>
            @foreach($roles as $role)
                <li><a href="#{{ str_slug($role->guard_name) }}">{{ $role->name }}</a></li>
            @endforeach
        </ul>
    </nav>
    @foreach($roles as $role)
        <div id="{{ str_slug($role->guard_name) }}">
            <table class="bordered striped permissions-table" data-route="{{ route('admin.perssions.save') }}">
                <thead>
                    <th>Nom du module</th>
                    <th class="w5">Accès</th>
                    <th class="w5">Créer</th>
                    <th class="w5">Editer</th>
                    <th class="w5">Supprimer</th>
                </thead>
                <tbody>
                @foreach($modules as $module)
                    <tr>
                        <td class="module-name">
                            <span>{{ $module->getName() }}</span>
                            {{ $module->getDescription() }}
                        </td>
                        <td>
                            <?php
                                $checked = '';
                                foreach($role->permissions as $permission){
                                    if($permission->name == 'access - '.$module->getAlias()) {
                                        $checked = 'checked="checked"';
                                    }
                                }
                            ?>
                            <input type="checkbox"
                                   name="module[{{$role->guard_name}}][{{$module->getAlias()}}]['access']"
                                   value="1"
                                   data-role="{{ $role->guard_name }}"
                                   data-module="{{ $module->getAlias() }}"
                                   data-permission="access"
                                   {{ $checked }}
                            >
                        </td>
                        <td>
                            <?php
                                $checked = '';
                                foreach($role->permissions as $permission){
                                    if($permission->name == 'create - '.$module->getAlias()) {
                                        $checked = 'checked="checked"';
                                    }
                                }
                            ?>
                            <input type="checkbox"
                                   name="module[{{$role->guard_name}}][{{$module->getAlias()}}]['show']"
                                   value="1"
                                   data-role="{{ $role->guard_name }}"
                                   data-module="{{ $module->getAlias() }}"
                                   data-permission="create"
                                   {{ $checked }}
                            >
                        </td>
                        <td>
                            <?php
                                $checked = '';
                                foreach($role->permissions as $permission){
                                    if($permission->name == 'edit - '.$module->getAlias()) {
                                        $checked = 'checked="checked"';
                                    }
                                }
                            ?>
                            <input type="checkbox"
                                   name="module[{{$role->guard_name}}][{{$module->getAlias()}}]['edit']"
                                   value="1"
                                   data-role="{{ $role->guard_name }}"
                                   data-module="{{ $module->getAlias() }}"
                                   data-permission="edit"
                                   {{ $checked }}
                            >
                        </td>
                        <td>
                            <?php
                                $checked = '';
                                foreach($role->permissions as $permission){
                                    if($permission->name == 'delete - '.$module->getAlias()) {
                                        $checked = 'checked="checked"';
                                    }
                                }
                            ?>
                            <input type="checkbox"
                                   name="module[{{$role->guard_name}}][{{$module->getAlias()}}]['delete']"
                                   value="1"
                                   data-role="{{ $role->guard_name }}"
                                   data-module="{{ $module->getAlias() }}"
                                   data-permission="delete"
                                   {{ $checked }}
                            >
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    @endforeach
@endsection
