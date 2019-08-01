<div class="col s12 m4">
    <div class="card-panel grey lighten-5 z-depth-1">
        <div class="row valign-wrapper">
            <div class="col s2">
                <img src="images/photo.png" alt="" class="circle responsive-img">
                <!-- notice the "circle" class -->
            </div>
            <div class="col s10">
                <span class="black-text">
                    Hello,
                </span>
                <h6 class="fw-500 m-0"><?= $_SESSION['u_name'] ?></h6>
            </div>
        </div>
    </div>
    <div class="collection z-depth-1 card">
        <a href="profile" class="collection-item <?= (($active == 1) ? 'active' : '') ?>"><i class="material-icons left">person</i> Profile
            Information</a>
        <a href="profile?address" class="collection-item <?= (($active == 2) ? 'active' : '') ?>"><i class="material-icons left">place</i> Manage
            Addresses</a>
        <a href="orders" class="collection-item <?= (($active == 3) ? 'active' : '') ?>"><i class="material-icons left">shopping_cart</i> My
            Orders</a>
        <a href="services/logout.php?log=true" class="collection-item"><i class="material-icons left">exit_to_app</i> Logout</a>
    </div>

</div>