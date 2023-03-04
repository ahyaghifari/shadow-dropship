<div id="home">
    <div id="home-header" class="border-b border-neutral-800 pb-2 border-dashed">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Beranda</h1>
    </div>
    <div id="home-container" class="grid grid-cols-1 gap-y-1 py-2 md:grid-cols-2 md:gap-x-1 md:gap-2">
        <a href="index.php?pages=product" class="border border-neutral-700 px-5 pb-6 pt-10 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out">Produk</a>
        <a href="index.php?pages=order" class="border border-neutral-700 px-5 pb-6 pt-10 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out">Pesanan</a>
        <a href="index.php?pages=user" class="border border-neutral-700 px-5 pb-6 pt-10 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out">User</a>
        <?php if($_SESSION['user']['role'] == "superadmin"){ ?>
        <a href="index.php?pages=contact" class="border border-neutral-700 px-5 pb-6 pt-10 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out">Contact</a>
        <?php }?> 
    </div>
</div>