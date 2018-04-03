<div class="row gutters">

    <div class="col col-12">
        <div class="widget primary">
            <div class="widget-title">
                <span>
                    <i class="fas fa-users"></i>
                </span>
                10 derniers utilisateurs
            </div>
            <div class="widget-value small">
                <ul>
                    @foreach($users as $user)
                        <li>
                            <strong>{{ $user->firstname }} {{ $user->lastname }}</strong> -
                            <a href="mailto:{{ $user->email }}">
                                {{ $user->email }}
                            </a> -
                            Nombre de commandes : <span class="label success">{{ count($user->Orders) }}</span> -
                            Inscrit le : {{ $user->created_at->format('d/m/Y à H:i') }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <a href="{{ route('admin.users.index') }}">Afficher</a>
        </div>
    </div>
</div>