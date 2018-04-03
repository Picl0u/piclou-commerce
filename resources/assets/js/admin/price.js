var ht_val = parseFloat(jQuery("input[name=price_ht]").val());
var ttc_val = parseFloat(jQuery("input[name=price_ttc]").val());
var percent_val = jQuery("select[name=vat_id]").val();

/* Prix HT */
jQuery("input[name=price_ht]").on("change keyup paste", function(){
    ht_val = parseFloat(jQuery("input[name=price_ht]").val());
    ttc_val = parseFloat(jQuery("input[name=price_ttc]").val());
    percent_val = jQuery("select[name=vat_id]").val();

    var taux = 0;
    jQuery('select[name=vat_id] option').each(function(){
        if(jQuery(this).attr("value") == percent_val){
            taux = jQuery(this).attr("data-taux");
        }
    });

    var taxe = ht_val*(1+(taux/100));
    jQuery("input[name=price_ttc]").val(taxe.toFixed(2));
});

/* Prix TTC */
jQuery("input[name=price_ttc]").on("change keyup paste", function(){
    ht_val = parseFloat(jQuery("input[name=price_ht]").val());
    ttc_val = parseFloat(jQuery("input[name=price_ttc]").val());
    percent_val = jQuery("select[name=vat_id]").val();

    var taux = 0;
    jQuery('select[name=vat_id] option').each(function(){
        if(jQuery(this).attr("value") == percent_val){
            taux = jQuery(this).attr("data-taux");
        }
    });
    var taxe = ttc_val/(1+(taux/100));
    jQuery("input[name=price_ht]").val(taxe.toFixed(2));

});