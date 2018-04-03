<li class="has-children notifications">
    <a href="#0">Commandes <span class="count">{{ pendingOrderCount() }}</span></a>
    <ul>
        <li>
            <a href="{{ route("orders.orders.index") }}">
                Commandes
            </a>
        </li>
        <li>
            <a href="{{ route("orders.invoices") }}">
                Factures
            </a>
        </li>
        <!--<li><a href="#0">Paniers</a></li>-->
        <li>
            <a href="{{ route("orders.carriers.index") }}">
                Transporteurs
            </a>
        </li>
        <li>
            <a href="{{ route("orders.countries.index") }}">
                Pays de livraison
            </a>
        </li>
        <li>
            <a href="{{ route("orders.status.index") }}">
                Status
            </a>
        </li>
    </ul>
</li>