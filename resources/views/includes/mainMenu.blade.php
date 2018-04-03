<ul>
    <li class="{{ (Route::current()->getName() == 'homepage')?'active':'' }}">
        <a href="/">
            {{ __('generals.home') }}
        </a>
    </li>
    {!!  navigationShopCategories() !!}
    <li class="{{ (Route::current()->getName() == 'product.flash')?'active':'' }}">
        <a href="{{ route('product.flash') }}">
            {{ __("generals.flashSales") }}
        </a>
    </li>
    {!!  navigationContents() !!}
    <li class="{{ (Route::current()->getName() == 'contact.index')?'active':'' }}">
        <a href="{{ route('contact.index') }}">
            {{ __('generals.contact') }}
        </a>
    </li>
</ul>