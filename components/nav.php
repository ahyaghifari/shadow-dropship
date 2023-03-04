<?php 

$nav_role = "";
if(isset($_SESSION['user'])){
    $nav_role = $_SESSION['user']['role'];
}
?>

<nav class="p-2 fixed top-0 left-0 flex justify-between w-full md:px-5 z-[20] items-center">
        <a href="" class="font-black text-xl z-10">SHADOW</a>
        <?php $_FILES ?>
        <div id="nav-toggle" class="cursor-pointer fixed bottom-3 right-3 group hover:bg-neutral-100 p-2 border border-neutral-100 aspect-square rounded-full flex items-center transition durattion-300 md:hidden">
        <span class="material-symbols-rounded text-3xl group-hover:text-neutral-900 transition duration-300">
            menu
        </span>
    </div>
    <div id="nav-list" class="font-black uppercase text-3xl md:text-base lg:text-lg p-3 md:p-0 md:text-neutral-500">
        <a href="index.php">Beranda</a>
        <?php if($nav_role != "admin" && $nav_role != "superadmin"){?>
        <a href="products.php">Produk</a>
        <a href="about.php">Tentang</a>
        <?php } ?>
        <?php if($nav_role == "admin" || $nav_role == "superadmin"){ ?>
            <a href="orders.php" class="orders-nav">Pesanan</a>
            <a href="admin/index.php" class="orders-nav">Dashboard</a>
        <?php } if( $nav_role == "admin"){?>
            <a href="talk.php">Chat</a>
        <?php } ?>
        </div>
    <div id="nav-right" class=" flex items-center">
        <div class="mr-3 font-semibold text-sm md:text-base">
            <?php if(isset($_SESSION['login'])) {?>
                <a href="user.php"><?= $_SESSION['user']['username'] ?></a>
            <?php }else{ ?>
                <a href="login.php">Masuk</a>
            <?php } ?>
        </div>
        <?php 
         if($nav_role != "admin"){
         ?>
        <a href="cart.php" id="nav-cart" class="px-2 relative cursor-pointer">
            <span class="material-symbols-rounded text-2xl md:text-3xl group-hover:text-neutral-900 transition duration-300">
                shopping_cart
            </span>
            <span id="cart-count" class="absolute bg-white top-0 right-0 aspect-[1/1] w-[18px] h-[18px] text-neutral-700 flex justify-center items-center rounded-full text-[12px]">
                <?= count($_SESSION['cart']) ?>
            </span>
        </a>
        <?php } ?>
    </div>
</nav>