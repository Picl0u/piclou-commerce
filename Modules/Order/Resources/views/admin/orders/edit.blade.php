@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Commandes</span></li>
            <li><span>Commande : {{ $order->reference }}</span></li>
        </ul>
    </nav>

    <h2>
        Commande : {{ $order->reference }}
        <span> - Modifiez votre commande</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("orders.orders.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    <div class="order-title">
        Commande : <strong>n°{{ $order->id }}</strong>
        - Référence <strong>{{ $order->reference }}</strong>
        - Passée le <strong>{{ $order->created_at->format('d/m/Y à H:i') }}</strong>
    </div>

    <div class="row gutters">
        <div class="col col-7 order-infos">

            <a href="{{ route('orders.orders.invoice',['uuuid' => $order->uuid]) }}" class="invoice-link">
                <i class="fas fa-file-pdf"></i> Facture
            </a>

            <div class="order-status">
                <div class="title">Etat de la commande</div>
                <div class="status">
                    <div class="label"
                         <?= (!empty($order->Status->color))?' style="background-color:'.$order->Status->color.';color:#FFF"':''; ?>
                    >
                        {{ $order->Status->name }}
                    </div>
                </div>

                <hr>
                <form method="post" action="{{ route('orders.orders.status',['uuid' => $order->uuid]) }}">
                    {{ csrf_field() }}
                    <div class="form-item">
                        <label>Mettre à jours la commande</label>
                        <div class="append">
                            <select name="status_id">
                                @foreach($status as $st)
                                    <option value="{{ $st->id }}">{{ $st->name }}</option>
                                @endforeach
                            </select>
                            <button class="button">OK</button>
                        </div>
                    </div>
                </form>
                <hr>
            </div>

            <div class="title">Historique de la commande</div>

            <div class="order-history">
                @foreach($order->OrdersStatus as $history)
                    <div class="history">
                        <div class="label"
                            <?= (!empty($history->Status->color))?' style="background-color:'.$history->Status->color.';color:#FFF"':''; ?>
                        >
                            {{ $history->Status->name }}
                        </div>
                        <div class="date">
                            Le {{ $history->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="title">Transporteur</div>
            <table class="bordered">
                <thead>
                    <tr>
                        <th>
                            Nom
                        </th>
                        <th>
                            Delais
                        </th>
                        <th>
                            Frais d'expéditions
                        </th>
                        <th>
                            ID suivis
                        </th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $order->shipping_name }}</td>
                    <td>{{ $order->shipping_delay }}</td>
                    <td>{{ priceFormat($order->shipping_price) }}</td>
                    <td>
                        <a href="{{ $order->shipping_url.$order->shipping_order_id }}" target="_blank">
                            {{ $order->shipping_order_id }}
                        </a>
                    </td>
                    <td>
                        <a href="#" data-remodal-target="carrier-order">
                            <i class="fas fa-pencil-alt"></i> Editer
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="remodal" data-remodal-id="carrier-order">
                <span data-remodal-action="close" class="remodal-close"></span>


                <form method="post" action="{{ route('orders.orders.carrier',['uuid' => $order->uuid]) }}">
                    {{ csrf_field() }}

                    <div class="form-item">
                        {{ Form::label('shipping_url', 'Url de suivis') }}
                        {{ Form::text('shipping_url', $order->shipping_url) }}
                    </div>

                    <div class="form-item">
                        {{ Form::label('shipping_order_id', 'ID du suivis de colis') }}
                        {{ Form::text('shipping_order_id', $order->shipping_order_id) }}
                    </div>

                    <div class="form-item">
                        {{ Form::label('shipping_delay', 'Délais de livraison') }}
                        {{ Form::text('shipping_delay', $order->shipping_delay) }}
                    </div>

                    <div class="form-buttons">
                        <button type="submit">{{ trans('form.register') }}</button>
                    </div>

                </form>

            </div>
            <div class="title">Retour produits</div>

        </div>
        <div class="col col-5 order-user">

            <div class="title">Informations client</div>
            <div class="user-infos">
                <div class="user">
                    Utilisateur inscrit :
                    @if(empty($order->user_id))
                       <div class="label error">Non</div>
                    @else
                        <div class="label success">Oui</div>
                    @endif
                </div>
                <div class="user">
                    Email :
                    <a href="mailto:{{ $order->user_email }}" class="label">
                        {{ $order->user_email }}
                    </a>
                </div>
                <div class="user">
                    Nom :
                    <div class="label">
                        {{ $order->user_firstname }} {{ $order->user_lastname }}
                    </div>
                </div>
                <div class="user">
                    Commandes validées :
                    <div class="label success">
                      {{ $nbOrder }}
                    </div>
                </div>
                <div class="user">
                    Total commande: <div class="label success">
                       {{ priceFormat($totalOrder) }}
                    </div>
                </div>
                <hr>
            </div>
            <div class="title">Adresse livraison</div>
            <div class="address">
                <strong>
                    {{ $order->delivery_gender }} {{ $order->delivery_firstname }}  {{ $order->delivery_lastname }}
                </strong><br>
                {{ $order->delivery_address }}  {{ $order->delivery_additional_address }}<br>
                {{ $order->delivery_zip_code }} {{ $order->delivery_city }}<br>
                {{ $order->delivery_country_name }}<br>
                Tél : {{ $order->delivery_phone}}
            </div>
            <div class="title">Adresse de facturation</div>
            <div class="address">
                <strong>
                    {{ $order->billing_gender }} {{ $order->billing_firstname }}  {{ $order->billing_lastname }}
                </strong><br>
                {{ $order->billing_address }}  {{ $order->billing_additional_address }}<br>
                {{ $order->billing_zip_code }} {{ $order->billing_city }}<br>
                {{ $order->billing_country_name }}<br>
                Tél : {{ $order->billing_phone}}
            </div>

        </div>
    </div>

    <div class="order-detail">
        <div class="title">
            Détail de la commande
        </div>
        <div class="cart">
            <div class="row gutters align-middle">
                <div class="col col-6 thead">Produit</div>
                <div class="col col-2 thead">Prix HT</div>
                <div class="col col-2 thead">Quantité</div>
                <div class="col col-2 thead">Prix TTC</div>
            </div>
            @foreach($products as $product)
                <div class="row gutters align-middle border-top border-top">
                    <div class="col col-6 tbody">
                        <div class="row gutters align-middle">
                            <div class="col col-2">
                                <img src="{{ resizeImage($product->getMedias('image','src'),50,50) }}"
                                     alt="{{ $product->name }}"
                                >
                            </div>
                            <div class="col col-10">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-ref">Référence : {{ $product->ref }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-2 tbody">
                        <div class="product-price">{{ priceFormat($product->price_ht) }}</div>
                    </div>
                    <div class="col col-2 tbody">
                        {{ $product->quantity }}

                    </div>
                    <div class="col col-2 tbody">
                        <div class="product-total">{{ priceFormat($product->price_ttc) }}</div>
                    </div>
                </div>
            @endforeach

            <div class="row gutters align-middle tfoot">
                <div class="col col-10 text-right">{{ trans('shop.subTotal') }}</div>
                <div class="col col-2">{{ priceFormat($order->price_ht) }}</div>
            </div>

            <div class="row gutters align-middle tfoot">
                <div class="col col-10 text-right">{{ trans('shop.vat') }}({{ $order->vat_percent }}%)</div>
                <div class="col col-2">{{ priceFormat($order->vat_price) }}</div>
            </div>

            @if(!is_null($order->coupon_price) && !empty($order->coupon_price))
                <div class="row gutters align-middle tfoot">
                    <div class="col col-10 text-right">Réduction ({{ $order->coupon_name }})</div>
                    <div class="col col-2">-{{ priceFormat($order->coupon_price) }}</div>
                </div>
            @endif

            <div class="row gutters align-middle tfoot">
                <div class="col col-10 text-right">{{ trans('shop.shipping') }}</div>
                <div class="col col-2">{{ priceFormat($order->shipping_price) }}</div>
            </div>

            <div class="row gutters align-middle tfoot">
                <div class="col col-10 text-right">{{ trans('shop.total') }}</div>
                <div class="col col-2">{{ priceFormat($order->price_ttc) }}</div>
            </div>

        </div>
    </div>

@endsection
