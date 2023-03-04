<?php 
$username_filter = "";
$status_filter = "";
$sortby_filter = "";
if(isset($_GET['status']) && isset($_GET['username']) && isset($_GET['sort_by'])){
    $allOrders = $orders->allOrders($conn, $_GET['status'], $_GET['username'], $_GET['sort_by']);
    $status_filter = $_GET['status'];
    $username_filter = $_GET['username'];
    $sortby_filter = $_GET['sort_by'];
}else{
    $allOrders = $orders->allOrders($conn, "", "", "");
}

$getInfo = $orders->ordersInfo($conn);
?>
<div id="order">
    <div id="order-header" class="border-b border-neutral-800 pb-2 border-dashed flex justify-between items-stretch">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Order</h1>
        <div id="order-header-right" class="text-[13px] flex">
            <button id="order-info-btn"><span class="material-symbols-rounded text-xl text-neutral-600">
            info</span></button>
        </div>
    </div>
    <div id="order-info" class="border-b border-neutral-400 text-sm px-2 py-3">
        <table class="text-[12px] md:text-[14px]">
            <?php foreach($getInfo['order_total'] as $order_total){ ?>
            <tr>
                <td>Total Pemasukan</td>
                <td>: Rp. <?= $order_total->balance ?></td>
            </tr>
            <tr>
                <td>Total Pesanan berhasil</td>
                <td>: <?= $order_total->order_count ?></td>
            </tr>
            <?php } ?>
            <?php foreach($getInfo['favorite_product'] as $fav_product){?>
            <tr>
                <td>Produk paling banyak terjual</td>
                <td>: <?= $fav_product->product_name ?> - <?= $fav_product->product_count ?> terjual</td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <div id="orders-filter" class="mt-4 sm:w-10/12 mx-auto lg:w-9/12">
        <form id="form-order-filter" action="index.php" method="get" class="text-[12px] grid grid-cols-2 gap-x-2 gap-y-2 sm:text-[13px] md:text-sm lg:text-base md:grid-cols-4 md:items-start md:gap-x-5 md:gap-y-0 p-1">
                <input type="hidden" name="pages" value="order">
                <div class="flex flex-col">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="bg-neutral-300 focus:outline-none">
                        <option value="semua" <?= ($status_filter == "" || $status_filter == "semua") ? "selected" : "" ?>>-----</option>
                        <option value="semua" <?= ($status_filter == "semua") ? "selected" : "" ?>>Semua</option>
                        <option value="belum-approve" <?= ($status_filter == "belum-approve") ? "selected" : "" ?>>Belum Di Approve</option>
                        <option value="belum-konfirmasi" <?= ($status_filter == "belum-konfirmasi") ? "selected" : "" ?>>Belum Di Konfirmasi</option>
                        <option value="selesai" <?= ($status_filter == "selesai") ? "selected" : "" ?>>Selesai</option>
                        <option value="batal" <?= ($status_filter == "batal") ? "selected" : "" ?>>Batal</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="bg-neutral-300 focus:outline-none p-[2px]" value="<?= $username_filter ?>">
                </div>
                <div class="flex flex-col">
                    <label for="">Urutkan dari</label>
                    <select name="sort_by" id="sort-by" class="bg-neutral-300 focus:outline-none">
                        <option value="">-----</option>
                        <option value="newest" <?= ($sortby_filter == "newest") ? "selected" : "" ?>>Paling Baru</option>
                        <option value="oldest" <?= ($sortby_filter == "oldest") ? "selected" : "" ?>>Paling Lama</option>
                    </select>
                </div>
            <div class="col-start-1 md:col-start-auto h-full flex items-center">
                <button type="submit" class="bg-neutral-700 p-1 px-3 text-neutral-200">Cari</button>
                <a href="index.php?pages=order" class="bg-neutral-300 p-1 px-3 ml-3 text-neutral-800">Reset </a>
            </div>
        </form>
    </div>
    <div id="order-container" class="mt-5 h-screen overflow-y-scroll border-y border-neutral-400">
        <?php if($allOrders > 0){ foreach ($allOrders as $order) {
            $approve = $order->approve;
            $status_info = $order->status_info;    
        ?>
        <div id="order-<?= $order->order_code ?>" class="orders border border-neutral-400 rounded-md gap-x-2 text-center my-2 sm:w-10/12 mx-auto">
            <div class="orders-user grid grid-cols-4 p-2">
                <h3 class="text-[10px] md:text-[11px] lg:text-[12px]"><?= $order->order_code ?></h3>
                <h3 class="text-[11px] md:text-[12px] lg:text-[13px]"><?= $order->username ?></h3>
                <h3 class="text-[9px] md:text-[10px] lg:text-[11px] text-neutral-500 to-calendar"><?= $order->created_at ?></h3>
                <h3 class="text-[9px] md:text-[10px] lg:text-[11px] text-neutral-500">Total : <br> <span class="text-neutral-700">Rp. <?= $order->total ?></span></h3>        </div>
            <div class="orders-info-actions grid grid-cols-3 bg-neutral-300 text-[10px] p-1 text-neutral-500 md:text-[11px] lg:text-[12px]">
                <p><?= $order->quantity ?> item</p>
                <a href="index.php?pages=order_detail&order_code=<?= $order->order_code ?>" class="text-neutral-600 underline">Lihat Detail</a>
                <?php if($approve == 1){ ?>
                    <button type="submit" id="approve-<?= $order->order_code ?>-btn" data-order="<?= $order->order_code ?>" class="order-approve-btn main-page text-[12px] bg-neutral-600 text-neutral-200 p-1 text-center font-semibold active:bg-neutral-800 active:text-neutral-300 w-full">Approve</button>
                <?php }else if($approve == 0 && $status_info == "dalam-proses"){ ?>
                    <p class="text-[10px] bg-neutral-400 text-neutral-200 p-[1px] text-center font-semibold capitalize">Belum Dikonfirmasi</p>
                <?php }else if($order->status == 0){?>
                    <p class="text-[10px] bg-neutral-100 text-neutral-600 p-[1px] text-center font-semibold capitalize"><?= ($status_info == "selesai") ? "Selesai" : "Batal" ?></p>
                <?php } ?>
            </div>
        </div>
        <?php }}else{ ?>
            <p class="text-sm py-3 text-center">Pesanan tidak ditemukan</p>
        <?php } ?>  
    </div>
</div>