</main>
<footer class="mt-20 p-3 text-center border-t border-neutral-500 grid grid-cols-3 md:grid-cols-3 grid-rows-auto gap-y-10 md:py-8 text-sm md:text-base">
    <div class="grid grid-cols-1 gap-y-1">
        <a href="index.php">Beranda</a>
        <a href="products.php">Produk</a>
        <a href="about.php">Tentang</a>
        <a href="contact.php">Kontak</a>
    </div>
    <?php if(isset($_SESSION['user'])){
            if($_SESSION['user']['role'] == "admin"){ ?>
            <div class="flex flex-col gap-y-3">
                <a href="orders.php">Pesanan</a>
                <a href="talk.php">Chat</a>
            </div>
        <?php }else{ ?>
            <a href="talk.php" class="flex items-center justify-center"><span class="material-symbols-rounded mr-1 text-xl">
                chat
            </span>Chat Admin</a>
       <?php } }else{ ?>
        <a href="talk.php" class="flex items-center justify-center"><span class="material-symbols-rounded mr-1 text-xl">
            chat
        </span>Chat Admin</a>
    <?php } ?>
    <?php if(isset($_SESSION['login'])){?>
        <form action="database/Users.php" method="post" class="flex items-center justify-center">
            <input type="hidden" name="logout">
            <button type="submit">Logout</button>           
        </form>
        <?php }else{?>
        <a href="login.php" class="flex items-center justify-center">Masuk</a>
        <?php }?>
    <p class="col-span-3 text-center flex items-center justify-center"><span class="material-symbols-rounded mr-1">
            location_on
        </span>Banjarbaru, Kalimantan Selatan.</p>
    <p class="col-span-3 md:col-span-3 text-center">&copy;SHADOW2023</p>
</footer>