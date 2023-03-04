<?php 
include "components/base.php";
include "database/config.php";
include "database/Products.php";
$products = new Products;
$allCategory = $products->allCategory($conn);
?>
<title>Beranda | Shadow Dropship</title>
<div id="home" class="px-3">
    <section id="hero" class="shadow-lg p-2 h-screen flex flex-col justify-center md:flex-row md:items-center md:w-10/12 md:mx-auto">
        <div id="hero-img" class="flex items-center bg-neutral-700 rounded-md owl-carousel h-[50vh] -z-10 w-10/12 mx-auto md:w-1/2">
            <?php foreach($allCategory as $category) {?>
            <img src="src/category/<?= $category->image ?>" class="h-[50vh] w-full object-contain" alt="">
            <?php } ?>
        </div>
        <div id="hero-content" class="flex flex-col items-center justify-center mt-5 md:w-1/2">
            <h1 class="font-black text-4xl md:text-5xl">SHADOW</h1>
            <h2 class="font-black text-neutral-500 text-2xl lg:text-3xl">DROPSHIP</h2>
            <h3 class="text-sm mt-5 text-center sm:w-11/12 md:w-10/12 md:text-base lg:text-lg">Website resmi dari <span class="font-black"> SHADOW </span> untuk para dropshipper</h3>
        </div>
    </section>
    <section class="mt-20 p-2 shadow-lg py-10 flex flex-col md:flex-row-reverse md:w-10/12 md:mx-auto md:justify-around">
        <div class="md:w-1/2">    
            <h5 class="mt-20 text-xl md:text-2xl mb-10">Cek ketersediaan barang dan lakukan pemesanan secara resmi disini</h5>
            <a href="products.php" class="md:text-xl bg-neutral-200 text-neutral-800 font-black p-2 shadow-md mt-20">Dropship Sekarang</a>
        </div>
        <img src="src/illustration/home-dropship_now.png" class="opacity-75 brightness-[60%] my-10 w-10/12 sm:w-1/2 md:w-5/12 lg:w-4/12 mx-auto -z-10" alt="">
    </section>
    <section class="mt-32 p-2 py-10 shadow-lg flex flex-col md:flex-row md:w-10/12 md:mx-auto md:justify-around md:items-center">
        <img src="src/illustration/chat-admin.png" class="mx-auto w-9/12 opacity-75 brightness-[70%] -z-10 w-10/12 sm:w-1/2 md:w-5/12 lg:w-4/12 mx-auto" alt="">
        <div class="md:w-1/2">
            <h5 class="text-xl md:text-2xl mb-10">Kamu juga bisa chat dengan admin untuk menanyakan hal-hal tertentu</h5>
            <a href="talk.php" class="border p-1 border-neutral-100 md:text-xl font-black">Chat Admin</a>
        </div>
    </section>
</div>
<?php require_once "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/home.js"></script>
<?php include "components/foot.php"?>
