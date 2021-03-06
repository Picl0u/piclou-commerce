require("../libs/font-awesome/js/fontawesome-all.min")
if (typeof global === "undefined"){global=window;}

global.jQuery = global.$ = require('jquery');
require("../libs/kube/kube/js/kube.min");
var axios = require('axios');
require("slick-carousel");

/* Affichage message flash */
require('jquery-toast-plugin');

/* Remodal */
require('remodal');

/* Image en background */
if (jQuery('img.img-to-background').length > 0) {
    jQuery('img.img-to-background').each(function(){
       var $this = jQuery(this);
       $this.parent().css({
           'background-image':'url("' + $this.attr('src') + '")'
       })
    });
}

/* Navigation mobile */
jQuery(".responsive-nav").append('<span class="flex-nav">'+jQuery(".ecommerce-navigation").html()+'</span>');
jQuery(".responsive-nav").append(jQuery(".main-navigation").html());
jQuery(".responsive-nav").on("click",".flex-nav a",function(e){
    document.location.href = jQuery(this).attr('href');
    e.stopPropagation();
    e.preventDefault();
});

/* Se déconnecter */
if(jQuery("li.user-logout")) {
    jQuery("li.user-logout a").on("click",function(e){
       jQuery(this).parent().find('form').submit();
       e.preventDefault();
    });
}

/* Homepage - Slider */
if(jQuery(".slider").length > 0) {
    var $slider = jQuery(".slider"),
        $slideDuration = $slider.attr("data-slideDuration"),
        $slideTransition = $slider.attr("data-transition"),
        $arrows = $slider.attr('data-arrows');

    if(jQuery('.slider').has('.fullscreen')){
        var fullScreenSize = function(){
            var $wHeight = jQuery(window).height(),
                $hHeight = jQuery("header").outerHeight(),
                $bHeight = jQuery('.search-bar').outerHeight(),
                $slider = jQuery('.slider.fullscreen');

            $slider.css({
                "height" : $wHeight - ($hHeight + $bHeight)
            });
            $slider.find(".slide").css({
                "height" : $wHeight - ($hHeight + $bHeight)
            })
        };
        window.onload = fullScreenSize();
        jQuery(window).resize(function(){
            fullScreenSize();
        });
    }

    var enableArrow = false;
    if($arrows == 'true') {
        enableArrow = true;
    }
    $slider.slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade:($slideTransition == 'fade' ? true : false),
        autoplay:($slideDuration > 0 ? true : false),
        autoplaySpeed: $slideDuration,
        arrows : enableArrow,
        speed: $slider.attr("data-transitionDuration")
    });

    /* Pagination */
    if (jQuery(".slider-pagination").length > 0) {

        var $pagination = jQuery(".slider-pagination");
        $pagination.find("span[data-slide=0]").addClass("active");
        $pagination.on("click",'span',function(e){
            var index = jQuery(this).attr("data-slide");
            if(!jQuery(this).hasClass("active")) {
                $pagination.find("span").removeClass('active');
                jQuery(this).addClass("active");
                $slider.slick('slickGoTo', index);
            }

            e.preventDefault();
        });

        $slider.on('afterChange', function(event, slick, currentSlide){
            $pagination.find("span").removeClass('active');
            $pagination.find("span[data-slide=" + currentSlide + "]").addClass("active");
        });
    }
}
/* Meilleures ventes - Slider */
if (jQuery(".best-sale-slider").length > 0) {
    var $bestSlider = jQuery(".best-sale-slider");
    $bestSlider.slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay:true,
        autoplaySpeed: 4000,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots:true
                }
            }
        ]
    });

    jQuery(".best-prev").on('click',function(e){
        $bestSlider.slick('slickPrev');
        e.preventDefault();
    });
    jQuery(".best-next").on('click',function(e){
        $bestSlider.slick('slickNext');
        e.preventDefault();
    });
}
/* Vente Flash - Slider */
if (jQuery(".flash-sales-slider").length > 0) {
    var $flashSlider = jQuery(".flash-sales-slider");
    $flashSlider.slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay:true,
        autoplaySpeed: 3000,
    });

    jQuery(".flash-prev").on('click',function(e){
        $flashSlider.slick('slickPrev');
        e.preventDefault();
    });
    jQuery(".flash-next").on('click',function(e){
        $flashSlider.slick('slickNext');
        e.preventDefault();
    });
}
/* Countdown */
if(jQuery(".countdown").length > 0){
    require("jquery-countdown");
    jQuery(".countdown").each(function(){
        var $this = jQuery(this);
        $this.countdown($this.attr('data-date'), function(event) {
            $this.find(".days span").html(event.strftime('%D'));
            $this.find(".hours span").html(event.strftime('%H'));
            $this.find(".minutes span").html(event.strftime('%M'));
            $this.find(".seconds span").html(event.strftime('%S'));
        });
    })
}
/* Ventes de la semaine - Slider */
if (jQuery(".slider-week-sections").length > 0) {
    var $weekSlider = jQuery(".slider-week-sections");
    $weekSlider.slick({
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots:true
                }
            }
        ]
    });

    jQuery(".week-prev").on('click',function(e){
        $weekSlider.slick('slickPrev');
        e.preventDefault();
    });
    jQuery(".week-next").on('click',function(e){
        $weekSlider.slick('slickNext');
        e.preventDefault();
    });
}
/* Trier les produits */
if(jQuery(".filters-products form").length > 0) {
    var $url = jQuery(".filters-products form").attr("action")
    jQuery(".filters-products form select").on('change',function(e){
        jQuery(".filters-products form").submit();
    });
}

/* Produit - Zoom sur l'image */
if(jQuery(".product .zoom").length > 0){
    require('jquery-zoom');
    jQuery(".product .zoom").each(function(){
       jQuery(this).zoom({
           url: jQuery(this).attr("data-url"),
       })
    });
    jQuery(".product .vignettes img").first().addClass("active");
    jQuery(".product .vignettes img").on("click", function(e){
        var $this = jQuery(this);
        if(!$this.hasClass('active')) {
            jQuery(".product .vignettes img").removeClass('active');
            $this.addClass('active');
            jQuery(".product .zoom").hide();
            jQuery(".product .zoom[data-img="+$this.attr('data-img')+"]").show();
        }
        e.preventDefault();
    });
}

/* Produits - Produits associés */
if (jQuery(".slider-related-products").length > 0) {
    var $weekSlider = jQuery(".slider-related-products");
    $weekSlider.slick({
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots:true
                }
            }
        ]
    });

    jQuery(".week-prev").on('click',function(e){
        $weekSlider.slick('slickPrev');
        e.preventDefault();
    });
    jQuery(".week-next").on('click',function(e){
        $weekSlider.slick('slickNext');
        e.preventDefault();
    });
}

/* Produit - Ajouter au panier */
if (jQuery(".form-product-to-basktet").length > 0) {
    var $modal = jQuery('[data-remodal-id=modal-cart]');
    var $modalCart = $modal.remodal();

    jQuery(".form-product-to-basktet").on("submit",function(e){
        var $form = jQuery(this),
            $formdata = $form.serialize();

        axios({
            method: 'post',
            url: $form.attr('action'),
            data: $formdata
        })
        .then(function (response) {
            jQuery(".shopping-count").empty().append(response.data.count);
            jQuery(".shopping-total").empty().append(response.data.total);
            $modal.find(".cart-product-image").empty()
                .append('<img src="/'+response.data.product.options.image+'" alt="'+response.data.product.name+'">');
            $modal.find(".product-name").empty().append(response.data.product.name);
            $modal.find(".product-price").empty().append(response.data.product.price);
            $modal.find(".product-quantity span").empty().append(response.data.product.qty);
            $modal.find(".total-count").empty().append(response.data.countCart);
            $modal.find(".transport").empty().append(response.data.shipping);
            $modal.find(".total").empty().append(response.data.total);
            $modalCart.open();
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
                text:  errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'bottom-right'
            });
        });

       e.preventDefault();
    });
}

/* Produit - Ajouter à la liste de souhait */
if (jQuery(".add-to-whishlist").length > 0) {

    jQuery(".add-to-whishlist").on("click",function(e){
        var $this = jQuery(this),
            $uuid = $this.attr('data-product');
        axios({
            method: 'post',
            url: $this.attr('href'),
            data: { 'uuid' : $uuid }
        })
            .then(function (response) {
                jQuery(".whishlist-count").empty().append(response.data.count);
                $.toast({
                    heading: 'Merci',
                    text:  response.data.message,
                    showHideTransition: 'slide',
                    icon: 'success',
                    position:'bottom-right'
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
                    text:  errorMsg,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position:'bottom-right'
                });
            });

        e.preventDefault();
    });
}

/* Modification du Panier */
if(jQuery(".table-cart").length > 0) {

    var $uri = jQuery(".cart").attr("data-url"),
        $cart = jQuery(".table-cart");

    var ajaxCart = function($productId, $qty)
    {
        axios({
            method: 'post',
            url: $uri,
            data: { 'product_id' : $productId, 'quantity' : $qty }
        })
        .then(function (response) {
            $cart.empty().append(response.data);
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
                text:  errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'bottom-right'
            });
        });
    };
    /* Réduire une quantité */
    $cart.on("click",".less", function(e){
        var $parent = jQuery(this).parent().parent(),
            $productId = $parent.attr('data-id'),
            $qty = parseInt($parent.find(".qty").text()) - 1 ;
        ajaxCart($productId, $qty);
        e.preventDefault();
    });
    /* Augmenter une quantité */
    $cart.on("click",".more", function(e){
        var $parent = jQuery(this).parent().parent(),
            $productId = $parent.attr('data-id'),
            $qty = parseInt($parent.find(".qty").text()) + 1 ;
        ajaxCart($productId, $qty);
        e.preventDefault();
    });
    /* Suppression d'un produit */
    $cart.on("click", ".delete-product", function(e){
        var $parent = jQuery(this).parent(),
            $productId = $parent.attr('data-id');
        ajaxCart($productId, 0);
       e.preventDefault();
    });
}

/* Adresses - Liste des pays */
if(jQuery("select[name=country_id]").length > 0) {
    require("select2");
    jQuery("select[name=country_id]").select2();
}

/* Adresses - Ajouter une nouvelle adresse */
if(jQuery(".new-address-control").length > 0){
    jQuery(".new-address-control").on("click",'a',function(e){
        jQuery(".add-new-address").slideToggle(300);
        e.preventDefault();
    });
}

/* Confirmation suppression */
if(jQuery('.delete-confirm').length > 0){
    var swal  = require('sweetalert');
    jQuery(document).on("click",'.delete-confirm',function(e){
        var $link = jQuery(this).attr('href'),
            $infos = jQuery(this).find('.delete-infos');

        swal({
            title: $infos.attr('data-title'),
            text: $infos.attr('data-message'),
            icon: "warning",
            buttons: {
                cancel: {
                    text: $infos.attr('data-cancel'),
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: $infos.attr('data-confirm'),
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
}

/* Inscription à la newsletter */
if(jQuery('.newsletter form').length > 0) {
    jQuery('.newsletter').on("submit",'form',function(e){
        var $buttonText = jQuery('.newsletter form button').text();
        jQuery('.newsletter form button').attr('disabled',true).empty().append('...');
        axios({
            method: 'post',
            url: jQuery('.newsletter form').attr('action'),
            data: { 'email' : jQuery('.newsletter form input[name=email]').val() }
        })
        .then(function (response) {
            $.toast({
                heading: 'Merci !',
                text:  response.data,
                showHideTransition: 'slide',
                icon: 'success',
                position:'top-right'
            });

            jQuery('.newsletter form button').removeAttr('disabled').empty().append($buttonText);
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
            if(errorMsg.errors.email[0]){
                errorMsg = errorMsg.errors.email[0];
            }
            $.toast({
                heading: 'Erreur !',
                text:  errorMsg,
                showHideTransition: 'slide',
                icon: 'error',
                position:'top-right'
            });
            jQuery('.newsletter form button').removeAttr('disabled').empty().append($buttonText);
        });
        e.preventDefault();
    });

}
