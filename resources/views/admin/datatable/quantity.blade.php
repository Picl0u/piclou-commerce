@if($stock_available < 1)
    <label class="label error">0</label>
@else
    <label class="label focus">{{ $stock_available }}</label>
@endif