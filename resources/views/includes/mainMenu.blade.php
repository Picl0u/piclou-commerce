<ul>
    <li class="active">
        <a href="/">
            {{ __('generals.home') }}
        </a>
    </li>
    {!!  navigationShopCategories() !!}
    <li>
        <a href="{{ route('product.flash') }}">
            {{ __("generals.flashSales") }}
        </a>
    </li>
    {!!  navigationContents() !!}
    <li>
        <a href="{{ route('contact.index') }}">
            {{ __('generals.contact') }}
        </a>
    </li>
</ul>