<h3>{{ $title }}</h3>
<form class="form-attributes" action="{{ $route }}" method="post">
    {{ csrf_field() }}
    <div class="attributes-container">
        @if(empty($data->declinaisons))
            <div class="row gutters align-middle attribute" data-key="0">

                <div class="col col-5 form-item">
                    <label>Attribut</label>
                    <input type="text" name="attr[0]" value="">
                </div>
                <div class="col col-5 form-item">
                    <label>Valeur</label>
                    <input type="text" name="values[0]" value="">
                </div>
                <div class="col col-2 attribute-actions">
                    <span class="add-new-attribute">
                        <i class="fas fa-plus"></i>
                    </span>
                </div>
            </div>
        @else
            @php $declinaisons = $data->getValues('declinaisons'); @endphp
            @foreach($declinaisons as $key => $value)
                <div class="row gutters align-middle attribute" data-key="{{ $loop->index }}">

                    <div class="col col-5 form-item">
                        <label>Attribut</label>
                        <input type="text" name="attr[{{ $loop->index }}]" value="{{ $key }}">
                    </div>
                    <div class="col col-5 form-item">
                        <label>Valeur</label>
                        <input type="text" name="values[{{ $loop->index }}]" value="{{ $value }}">
                    </div>
                    <div class="col col-2 attribute-actions">
                        <span class="add-new-attribute">
                            <i class="fas fa-plus"></i>
                        </span>
                        @if($loop->index > 0)
                            <span class="delete-attribute">
                                <i class="fas fa-trash"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="form-item">
        <label>Quantité</label>
        <input type="text" name="stock_brut" value="{{ $data->stock_brut }}">
    </div>

    <div class="row gutters">
        <div class="col col-3">
            <div class="form-item">
                <label>Référence</label>
                <input type="text" name="reference" value="{{ $data->reference }}">
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                <label>Code-barre EAN-13 ou JAN</label>
                <input type="text" name="ean_code" value="{{ $data->ean_code }}">
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                <label>Code-barre UPC</label>
                <input type="text" name="upc_code" value="{{ $data->upc_code }}">
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                <label>Code-barre ISBN</label>
                <input type="text" name="isbn_code" value="{{ $data->isbn_code }}">
            </div>
        </div>

    </div>
    <button data-remodal-action="confirm" class="remodal-confirm">
        {{ __('admin::actions.save') }}
    </button>
</form>