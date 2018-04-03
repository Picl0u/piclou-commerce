<li class="has-children">
    <a href="#0">{{ trans('admin.shop_menu') }}</a>
    <ul>
        <li>
            <a href="{{ route('shop.products.index') }}">
                Produits
            </a>
        </li>
        <li>
            <a href="{{ route("admin.products.comments.index") }}">
                Commentaires
            </a>
        </li>
        <li>
            <a href="{{ route("shop.categories.index") }}">
                Catégories
            </a>
        </li>
        <li>
            <a href="{{ route("shop.vats.index") }}">
                Taxes
            </a>
        </li>
    </ul>
</li>