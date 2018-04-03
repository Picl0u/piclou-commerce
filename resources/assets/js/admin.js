require("../libs/font-awesome/js/fontawesome-all.min")
if (typeof global === "undefined"){global=window;}
global.jQuery = global.$ = require('jquery');
require("../libs/kube/kube/js/kube.min");
var axios = require('axios');

/* Template */
require("../libs/navigation/jquery.menu-aim");
require("./admin/template");

/* DataTable */
if(jQuery(".datatable").length > 0){
    var dataTable = require('datatables.net');
    $.fn.DataTable = dataTable;
}
/* Remodal */
if(jQuery(".remodal").length > 0) {
    require('remodal');
    jQuery(document).on("click",".remodalImg", function(e){
        var $this = jQuery(this);
        jQuery(".remodal .forImg").empty().append('<img src="'+ $this.attr('data-src') +'" />');
        var inst = jQuery('[data-remodal-id=remodal]').remodal();
        inst.open();
        e.preventDefault();
    });
}

/* Se déconnecter */
if(jQuery("li.user-logout")) {
    jQuery("li.user-logout a").on("click",function(e){
        jQuery(this).parent().find('form').submit();
        e.preventDefault();
    });
}

/* Confirmation suppression */
var swal  = require('sweetalert');
jQuery(document).on("click",'.confirm-alert',function(e){
    var $link = jQuery(this).attr('href');
    swal({
        title: "Attention",
        text: "Voulez-vous supprimer cet élément ?",
        icon: "warning",
        buttons: {
            cancel: {
                text: "Annuler",
                value: null,
                visible: true,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "Confirmer",
                value: true,
                visible: true,
                className: "",
                closeModal: true
            }
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            window.location.replace($link);
        }
    });
    e.preventDefault();
});

/* Affichage message flash */
require('jquery-toast-plugin');

/* Reorganisation en drag and drop */
if(jQuery(".nested-section").length > 0){

    require('./admin/sortable');
    var $sort = jQuery('.sortable');
    var $uri = jQuery(".nested-section").attr("data-url");

    $sort.on('click','a',function(e){
       e.preventDefault();
    });

    $sort.nestedSortable({
        items: 'li',
        listType : 'ul',
        forcePlaceholderSize: true,
        helper:	'clone',
        isTree : true,
        update : function(){
            var serialized = $sort.nestedSortable('toArray');
            axios({
                method: 'post',
                url: $uri,
                data: { orders : serialized }
            })
            .then(function (response) {
                $.toast({
                    heading: 'Merci',
                    text:  response.data,
                    showHideTransition: 'slide',
                    icon: 'success',
                    position:'top-right'
                });
            })
            .catch(function (error) {
                var errorMsg = "";
                if (error.response) {
                    errorMsg = error.response.data;
                } else if (error.request) {
                    errorMsg  = error.request;
                } else {
                    errorMsg = error.message;
                }
                console.log(error.config);
                $.toast({
                    heading: 'Erreur !',
                    text:  'Une erreur s\'est produite...' + errorMsg,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position:'top-right'
                });
            });
        }
    });
}
/* Reorganisation en drag and drop - Tableau */
if(jQuery(".table-sortable").length > 0){

    require('./admin/sortable');
    var $sort = jQuery('.sortable');
    var $uri = jQuery(".table-sortable").attr("data-url");


    $sort.nestedSortable({
        items: 'li',
        listType : 'ul',
        forcePlaceholderSize: true,
        helper:	'clone',
        isTree : false,
        update : function() {
            var serialized = $sort.nestedSortable('toArray');
            console.log(serialized);
            axios({
                method: 'post',
                url: $uri,
                data: {orders: serialized}
            })
            .then(function (response) {
                $.toast({
                    heading: 'Merci',
                    text: response.data,
                    showHideTransition: 'slide',
                    icon: 'success',
                    position: 'top-right'
                });
            })
            .catch(function (error) {
                var errorMsg = "";
                if (error.response) {
                    errorMsg = error.response.data;
                } else if (error.request) {
                    errorMsg = error.request;
                } else {
                    errorMsg = error.message;
                }
                $.toast({
                    heading: 'Erreur !',
                    text: 'Une erreur s\'est produite...' + errorMsg,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position: 'top-right'
                });
            });
        }
    });
}

/* Affichage date/datetime picker */
import flatpickr from "flatpickr";
import { French } from "flatpickr/dist/l10n/fr";
if(jQuery(".date-picker").length > 0) {
    flatpickr('.date-picker', {
        locale: French,
        altInput: true,
        altFormat: 'j F Y',
        dateFormat: 'Y-m-d'
    });
}
if(jQuery(".datetime-picker").length > 0) {
    flatpickr('.datetime-picker', {
        locale: French,
        enableTime :true,
        time_24hr: true,
        altInput : true,
        altFormat : 'j F Y, H:i',
        dateFormat : 'Y-m-d H:i:S'
    });
}
/* Calcul prix produits */
if(jQuery("input[name=price_ht]").length > 0 && jQuery("input[name=price_ttc]").length > 0 && jQuery("select[name=vat_id]").length > 0) {
    require("./admin/price");
}
window.CKEDITOR_BASEPATH = '/libs/ckeditor/';
require("ckeditor");

/* Editeur HTML */
if(jQuery(".html-editor")){

    jQuery('.html-editor').each(function(e) {
        var config = {
            extraPlugins: 'colorbutton,font',
            filebrowserBrowseUrl : window.CKEDITOR_BASEPATH + 'kcfinder/browse.php?opener=ckeditor&type=files',
            filebrowserImageBrowseUrl : window.CKEDITOR_BASEPATH + 'kcfinder/browse.php?opener=ckeditor&type=images',
            filebrowserFlashBrowseUrl : window.CKEDITOR_BASEPATH + 'kcfinder/browse.php?opener=ckeditor&type=flash',
            filebrowserUploadUrl : window.CKEDITOR_BASEPATH + 'kcfinder/upload.php?opener=ckeditor&type=files',
            filebrowserImageUploadUrl : window.CKEDITOR_BASEPATH + 'kcfinder/upload.php?opener=ckeditor&type=images',
           filebrowserFlashUploadUrl : window.CKEDITOR_BASEPATH + 'kcfinder/upload.php?opener=ckeditor&type=flash',
        };
        CKEDITOR.replace(this.id,config);
    });
}

/* Gestion des plages de transpoteurs */
if(jQuery(".carriers-plages").length > 0){

    var $stringPrice = 'prix',
        $stringWeight = 'poids';


    var changeTypeShipping = function(val) {
        if(val == 'price'){
            jQuery(".carriers-plages").find(".type-shipping").empty().append($stringPrice);
        } else {
            jQuery(".carriers-plages").find(".type-shipping").empty().append($stringWeight);
        }
    };
    changeTypeShipping(jQuery('input[name=type_shipping]:checked').val())

    jQuery(document).on("click", "input[name=type_shipping]", function(){
        changeTypeShipping(jQuery(this).val());
    });

    /* Ajouter une nouvelle tranche */
    jQuery(".carriers-plages").on("click",".add-new-plage",function(e){
        var $this = jQuery(this).parent().find(".plage-list"),
        $countPlage = $this.find(".col-plage").length,
        $lastCol = $this.find(".col-plage").last(),
        $maxPrice = $lastCol.find('input.price-max').val(),
        $key = $lastCol.attr("data-key"),
        $clone = $lastCol.clone();

        // Modification de l'attribut clé
        $clone.attr("data-key", $countPlage);
        // Champs à zéro
        $clone.find("input").val("");
        // Modification de la clé des inputs
        $clone.find("input").each(function(){
            this.name = this.name.replace($key, $countPlage);
        });
        // Valeur par défaut du prix minimum
        $clone.find("input.price-min").val($maxPrice);
        // Ajouter bouton Supprimer si premier clique
        if($key == 0){
            $clone.append('<a href="#" class="delete-plage">Supprimer</a>');
        }

        $this.append($clone);
        e.preventDefault();
    });

    /* Supprimer une plage */
    jQuery(".carriers-plages").on("click", ".delete-plage", function(e){
        var $this = jQuery(this).parent(),
            $key = $this.attr("data-key"),
            $parent = $this.parent();
        $parent.find(".col-plage").each(function(){
            if(jQuery(this).attr("data-key") > $key){
                var $currentKey = jQuery(this).attr("data-key"),
                    $newKey = parseInt($currentKey) -1;
                jQuery(this).attr("data-key", $newKey);
                jQuery(this).find("input").each(function(){
                    this.name = this.name.replace($currentKey, $newKey);
                });
            }
        });
        $this.empty().remove();
        e.preventDefault();
    });

    /* Cocher tout les pays */
    jQuery(".carriers-plages").on("click",".check-all", function(){
        var $this = jQuery(this).parent().parent().parent();
        if(jQuery(this).is(":checked")){
            $this.find(".country-plage").find('input[type=checkbox]').attr("checked","checked");
        } else {
            //$this.find(".country-plage").find('input[type=checkbox]').removeAttr("checked");
        }
    });

    /* Changer la valeur pour tout les pays */
    jQuery(".carriers-plages").on("keyup",".value-all", function(){
        var $this = jQuery(this).parent().parent().parent();
        $this.find(".country-plage").find('input[type=text]').val(jQuery(this).val());
    });
}

/* Gestion des permissions */
if(jQuery('.permissions-table').length > 0){

    jQuery('.permissions-table').on("click",'input[type=checkbox]',function(e){

        var $this = jQuery(this),
            $role = $this.attr('data-role'),
            $module = $this.attr('data-module'),
            $permission = $this.attr('data-permission'),
            $uri = jQuery('.permissions-table').attr('data-route'),
            $checked = 0;

        if($this.is(':checked')){
            $checked = 1;
        }

        axios({
            method: 'post',
            url: $uri,
            data: {
                role : $role,
                module : $module,
                permission : $permission,
                checked : $checked
            }
        })
        .then(function (response) {
            console.log(response.data)
            $.toast({
                heading: 'Merci',
                text:  response.data,
                showHideTransition: 'slide',
                icon: 'success',
                position:'top-right'
            });
        })
        .catch(function (error) {
            var errorMsg = "";
            if (error.response) {
                errorMsg = error.response.data;
            } else if (error.request) {
                errorMsg  = error.request;
            } else {
                errorMsg = error.message;
            }
            console.log(error.config);
            $.toast({
                heading: 'Erreur !',
                text:  'Une erreur s\'est produite...' + errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'top-right'
            });
        });

    });
}

/* Select2 - Multiple */
if(jQuery("select.multiple-select").length > 0) {
    require("select2");
    jQuery("select.multiple-select").select2();
}

/* Traductions */
if (jQuery(".translate-actions").length > 0) {
    jQuery(".translate-actions a").on("click", function(e){
        var $lang = jQuery(this).attr('data-lang'),
            $route = jQuery(".modal-translate form").attr("data-action"),
            $form = jQuery(".modal-translate form"),
            $modal = jQuery('[data-remodal-id=translate-modal]').remodal();
        $form.attr("action",$route + "&lang=" + $lang);
        axios({
            method: 'get',
            url: $route + "&lang=" + $lang,
        })
        .then(function (response) {

            jQuery.each(response.data, function($key, object) {
                jQuery.each(object, function($field, $value) {
                    $form.find('#'+$field).val($value);
                });
            });
            $form.find('.ajax-editor').each(function(e){
                var instance = CKEDITOR.instances.content;
                instance.destroy();
                CKEDITOR.replace( this.id, {});
            });

            $form.find("span.lang").empty().append($lang);

            $modal.open();

        })
        .catch(function (error) {
            var errorMsg = "";
            if (error.response) {
                errorMsg = error.response.data;
            } else if (error.request) {
                errorMsg  = error.request;
            } else {
                errorMsg = error.message;
            }
            $.toast({
                heading: 'Erreur !',
                text:  'Une erreur s\'est produite...' + errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'top-right'
            });
        });
        e.preventDefault();
    });

    jQuery(".modal-translate .remodal-confirm").on("click", function(e){
        var instance = CKEDITOR.instances.content;
        for ( instance in CKEDITOR.instances ) {
            CKEDITOR.instances[instance].updateElement();
        }

        var $form = jQuery(".modal-translate form"),
            $postData = $form.serialize();

        axios({
            method: 'post',
            url: $form.attr("action"),
            data: $postData
        })
        .then(function (response) {

            $.toast({
                heading: 'Merci !',
                text:  response.data.message,
                showHideTransition: 'slide',
                icon: 'success',
                position:'top-right'
            });


        })
        .catch(function (error) {
            var errorMsg = "";
            if (error.response) {
                errorMsg = error.response.data;
            } else if (error.request) {
                errorMsg  = error.request;
            } else {
                errorMsg = error.message;
            }
            $.toast({
                heading: 'Erreur !',
                text:  'Une erreur s\'est produite...' + errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'top-right'
            });
        });


        e.preventDefault();

    });

}

/* Edition des images */
if(jQuery(".edit-medias").length > 0) {
    var $modal = jQuery('[data-remodal-id=medias-update]'),
        $formMedias = jQuery(".update-medias"),
        $modalInst = $modal.remodal(),
        $altDiv = '';

    jQuery(".edit-medias").on("click", function(e){
        var $this = jQuery(this),
            $action = $this.attr('href');
        $altDiv = $this.parent().parent().find(".alt");
        var $alt = $altDiv.text();

        $formMedias.find(".medias-name").empty().append($alt);
        $formMedias.find("input[name=alt]").val($alt);
        $formMedias.find("form").attr('action', $action);
        $modalInst.open();
        e.preventDefault();

        return $altDiv;
    });

    $modal.on("click",".remodal-confirm", function(e){
        var $form = $formMedias.find('form'),
            $this = jQuery(this),
            $label = $this.text(),
            $postData = $form.serialize();

        console.log($postData);


        $this.attr('disabled','true').empty().append("...");
        axios({
            method: 'post',
            url: $form.attr("action"),
            data: $postData
        })
        .then(function (response) {
            $.toast({
                heading: 'Merci !',
                text:  response.data,
                showHideTransition: 'slide',
                icon: 'success',
                position:'top-right'
            });
            $this.empty().append($label).removeAttr('disabled');
            $modalInst.close();
            $altDiv.empty().append($form.find('input[name=alt]').val());
        })
        .catch(function (error) {
            $modalInst.close();
            $this.empty().append($label).removeAttr('disabled');
            var errorMsg = "";
            if (error.response) {
                errorMsg = error.response.data;
            } else if (error.request) {
                errorMsg  = error.request;
            } else {
                errorMsg = error.message;
            }
            $.toast({
                heading: 'Erreur !',
                text:  'Une erreur s\'est produite...' + errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'top-right'
            });
        });

        e.preventDefault();
        e.stopPropagation();
    });
}

/* Gestion des déclinaisons */
if(jQuery("#attributes").length > 0) {
    var $div = jQuery("#attributes"),
        $modal = jQuery('[data-remodal-id=modal-attributes]'),
        $modalContainer = $modal.find(".modal-container"),
        $modalInst = $modal.remodal(),
        $table = $div.find('.table-declinaisons'),
        $line = "";

    /* Afficher le formulaire d'ajout */
    $div.on('click',"a.add-new-declinaison", function(e){
        $line = "";
        axios({
            method: 'get',
            url: jQuery(this).attr('href')
        })
        .then(function (response) {
            $modalContainer.empty().append(response.data);
            $modalInst.open();
        })
        .catch(function (error) {
            $modalInst.close();
            var errorMsg = "";
            if (error.response) {
                errorMsg = error.response.data;
            } else if (error.request) {
                errorMsg  = error.request;
            } else {
                errorMsg = error.message;
            }
            $.toast({
                heading: 'Erreur !',
                text:  'Une erreur s\'est produite...' + errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'top-right'
            });
        });
        e.preventDefault();
        e.stopPropagation();
        return $line;
    });

    /* Ajouter un attribut */
    $modal.on("click", '.attribute .add-new-attribute', function(e){

        var $this = jQuery(this),
            $parent = jQuery(this).parent().parent(),
            $container = $parent.parent(),
            $key = $parent.attr("data-key"),
            $countAttribute = $modal.find('.attribute').length,
            $clone = $parent.clone();

        // Modification de l'attribut clé
        $clone.attr("data-key", $countAttribute);
        // Champs à zéro
        $clone.find("input").val("");
        // Modification de la clé des inputs
        $clone.find("input").each(function(){
            this.name = this.name.replace($key, $countAttribute);
        });
        // Ajouter bouton Supprimer si premier clique
        if($key == 0){
            $clone.find(".attribute-actions").append(
                '<span class="delete-attribute">\n' +
                    '<i class="fas fa-trash"></i>\n' +
                '</span>'
            );
        }

        $container.append($clone);

        e.preventDefault();
    });

    /* Supprimer un attribut */
    $modal.on("click", ".delete-attribute", function(e){
        var $this = jQuery(this).parent().parent(),
            $key = $this.attr("data-key"),
            $parent = $this.parent();
        $parent.find(".attribute").each(function(){
            if(jQuery(this).attr("data-key") > $key){
                var $currentKey = jQuery(this).attr("data-key"),
                    $newKey = parseInt($currentKey) -1;
                jQuery(this).attr("data-key", $newKey);
                jQuery(this).find("input").each(function(){
                    this.name = this.name.replace($currentKey, $newKey);
                });
            }
        });
        $this.empty().remove();
        e.preventDefault();
    });

    /* Sauvegarde du formulaire */
    $modal.on("click", ".remodal-confirm", function(e){

        var $form = $modal.find("form"),
            $button = jQuery(this),
            $label = $button.text(),
            $uri = $form.attr("action"),
            $postData = $form.serialize();


        $button.empty().append('...');
        axios({
            method: 'post',
            url: $uri,
            data: $postData
        })
            .then(function (response) {
                $button.empty().append($label);
                $modalInst.close();
                if($line != ""){
                    console.log($line);
                    $line.empty().append(response.data);
                } else {
                    $table.find('tbody').append(response.data);
                }
                $.toast({
                    heading: 'Merci !',
                    text:  "La déclinaison a bien été sauvegardée",
                    showHideTransition: 'slide',
                    icon: 'success',
                    position:'top-right'
                });


            })
            .catch(function (error) {
                $button.empty().append($label);
                var errorMsg = "";
                if (error.response) {
                    errorMsg = error.response.data;
                } else if (error.request) {
                    errorMsg  = error.request;
                } else {
                    errorMsg = error.message;
                }
                $.toast({
                    heading: 'Erreur !',
                    text:  'Une erreur s\'est produite...' + errorMsg,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position:'top-right'
                });
            });


        e.preventDefault();
        e.stopPropagation();
    });

    /* Modification d'une déclinaison */
    $div.on('click',"a.edit-declinaison", function(e){

        $line = jQuery(this).parent().parent();

        axios({
            method: 'get',
            url: jQuery(this).attr('href')
        })
            .then(function (response) {
                $modalContainer.empty().append(response.data);
                $modalInst.open();
            })
            .catch(function (error) {
                $modalInst.close();
                var errorMsg = "";
                if (error.response) {
                    errorMsg = error.response.data;
                } else if (error.request) {
                    errorMsg  = error.request;
                } else {
                    errorMsg = error.message;
                }
                $.toast({
                    heading: 'Erreur !',
                    text:  'Une erreur s\'est produite...' + errorMsg,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position:'top-right'
                });
            });
        e.preventDefault();
        e.stopPropagation();

        return $line;
    });

    /* Supprimer une declinaison */
    $div.on("click", "a.delete-declinaison", function(e){

        $line = jQuery(this).parent().parent();
        var $link = jQuery(this).attr('href');
        swal({
            title: "Attention",
            text: "Voulez-vous supprimer cet élément ?",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Annuler",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: "Confirmer",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            axios({
                method: 'get',
                url: $link
            })
            .then(function (response) {
                $line.empty().remove()
            })
            .catch(function (error) {
                var errorMsg = "";
                if (error.response) {
                    errorMsg = error.response.data;
                } else if (error.request) {
                    errorMsg  = error.request;
                } else {
                    errorMsg = error.message;
                }
                $.toast({
                    heading: 'Erreur !',
                    text:  'Une erreur s\'est produite...' + errorMsg,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position:'top-right'
                });
            });
        });

        e.preventDefault();
        e.stopPropagation();

        return $line;
    });

}

