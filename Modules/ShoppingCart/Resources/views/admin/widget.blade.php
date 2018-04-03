<div class="row gutters">

    <div class="col col-6 ">
        <div class="widget success">
            <div class="widget-title">
                <span>
                    <i class="fas fa-shopping-cart"></i>
                </span>
                Panier
            </div>
            <div class="widget-value">
                {{ $totalCart }}
            </div>
            <a href="#">Afficher</a>
        </div>
    </div>

    <div class="col col-6">
        <div class="widget danger">
            <div class="widget-title">
                <span>
                    <i class="fas fa-heart"></i>
                </span>
                Liste de souhaits
            </div>
            <div class="widget-value">
                {{ $totalWhishList }}
            </div>
            <a href="#">Afficher</a>
        </div>
    </div>
</div>