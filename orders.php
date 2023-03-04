<?php 

include "database/config.php";
include "database/Orders.php";
$orders = new Orders;

if(isset($_POST['check_not_approve_order'])){
    $checkNotApproveOrder = $orders->notApproveOrder($conn);
    return $checkNotApproveOrder;
}

if(isset($_POST['approve_order'])){
    $approveOrder = $orders->approveOrder($conn, $_POST['order_code']);
    return $approveOrder;
}

include "components/base.php";
?><title>Pesanan | Shadow Dropship</title><?php

if(!isset($_SESSION['login'])){
    header("location: login.php");
}else if($_SESSION['user']['role'] == "user"){
    echo "<script>history.back()</script>";
}

?>

<div id="orders" class="mt-16 p-3 md:w-11/12 mx-auto md:mt-20">
    <?php if(!isset($_GET['order-detail'])){
        $status = "";
        if(isset($_GET['status'])){
            $status = $_GET['status'];
        }
        if($status == "" || $status == "semua"){
            $header_status = "Semua";
        }else if($status == "belum-approve"){
            $header_status = "Belum di Approve";
        }else if($status == "belum-konfirmasi"){
            $header_status = "Belum di Konfirmasi";
        }else if($status == "selesai"){
            $header_status = "Selesai";
        }else if($status == "batal"){
            $header_status = "Batal";
        }
    
        $filter_show = "";
        $status_filter = "";
        $username_filter = "";
        $sortby_filter = "";
        
        if(isset($_GET['status']) && isset($_GET['username']) && isset($_GET['sort_by'])){
            $filter_show = "show";
            $status_filter = $_GET['status'];
            $username_filter = $_GET['username'];
            $sortby_filter = $_GET['sort_by'];

            $allOrders = $orders->allOrders($conn, $status_filter, $username_filter, $sortby_filter);
        }else{
            $allOrders = $orders->allOrders($conn, $status, "", "");
        }
    
    ?>
    <div id="orders-header" class="flex justify-between">
        <h1 class="text-xl md:text-2xl">Pesanan : <span id="orders-status-header"><?= $header_status ?></span></h1>
        <button class="orders-filter-btn"><span class="material-symbols-rounded text-neutral-400 md:text-3xl">filter_alt</span></button>
    </div>
    <div id="orders-filter" class="<?= $filter_show ?> mt-4 sm:w-10/12 mx-auto lg:w-9/12">
        <form id="form-order-filter" action="orders.php" method="get" class="text-[12px] grid grid-cols-2 gap-x-2 gap-y-2 sm:text-[13px] md:text-sm lg:text-base md:grid-cols-4 md:items-start md:gap-x-5 md:gap-y-0">
                <div class="flex flex-col">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="bg-neutral-700 focus:outline-none">
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
                    <input type="text" name="username" id="username" class="bg-neutral-700 focus:outline-none p-[2px]" value="<?= $username_filter ?>">
                </div>
                <div class="flex flex-col">
                    <label for="">Urutkan dari</label>
                    <select name="sort_by" id="sort-by" class="bg-neutral-700 focus:outline-none">
                        <option value="">-----</option>
                        <option value="newest" <?= ($sortby_filter == "newest") ? "selected" : "" ?>>Paling Baru</option>
                        <option value="oldest" <?= ($sortby_filter == "oldest") ? "selected" : "" ?>>Paling Lama</option>
                    </select>
                </div>
            <div class="col-start-1 md:col-start-auto h-full flex items-center">
                <button type="submit" class="bg-neutral-300 p-1 px-3 text-neutral-800">Cari</button>
                <a href="orders.php" class="bg-neutral-700 p-1 px-3 text-neutral-200 ml-3">Reset </a>
            </div>
        </form>
    </div>
    <div id="orders-status-filter" class="mt-5 text-[11px] no-wrap overflow-x-scroll text-center flex bg-neutral-900 py-2  sm:w-10/12 mx-auto md:w-9/12 sm:justify-around sm:text-[13px] md:text-sm">
        <a class="whitespace-nowrap mx-5 <?= ($status == "semua" || $status == "") ? "font-semibold" : "" ?>" href="orders.php?status=semua">Semua</a>
        <div id="nav-not-approve-container" class="relative">
            <a class="whitespace-nowrap mx-5 <?= ($status == "belum-approve") ? "font-semibold" : "" ?>" href="orders.php?status=belum-approve">Belum di Approve</a>
        </div>
        <a class="whitespace-nowrap mx-5 <?= ($status == "belum-konfirmasi") ? "font-semibold" : "" ?>" href="orders.php?status=belum-konfirmasi">Belum Konfirmasi</a>
        <a class="whitespace-nowrap mx-5 <?= ($status == "selesai") ? "font-semibold" : "" ?>" href="orders.php?status=selesai">Selesai</a>
        <a class="whitespace-nowrap mx-5 <?= ($status == "batal") ? "font-semibold" : "" ?>" href="orders.php?status=batal">Batal</a>
    </div>
    <div id="orders-container" class="mt-1 sm:w-10/12 mx-auto md:w-9/12 h-screen overflow-y-scroll border-y border-neutral-600 py-2 shadow-inner">
        <?php if($allOrders > 0){ foreach($allOrders as $order){
        $approve = $order->approve;
        $status_info = $order->status_info;    
        ?>
            <div id="order-<?= $order->order_code ?>" class="orders shadow-md mb-5">
                <div class="orders-products grid grid-cols-4 h-[10vh] md:h-[13vh] text-[11px] md:text-[14px] justify-center items-center bg-neutral-900">
                    <img src="src/products/<?= $order->image ?>" class="h-[10vh] md:h-[13vh] p-1 bg-neutral-700 w-full object-contain" alt="">
                    <p class="text-center"><?= $order->username ?></p>
                    <p class="text-center text-neutral-300 text-[8px] md:text-[12px] to-calendar"><?= $order->created_at ?></p>
                    <a href="orders.php?order-detail=<?= $order->order_code ?>" class="text-neutral-400 text-center underline">Lihat Detail</a>
                </div>
                <div class="orders-info grid grid-cols-4 items-center text-[9px] md:text-[13px] p-2 gap-x-1">
                    <p class="text-center"><?= $order->order_code ?> </p>
                    <p class="text-center"><?= $order->quantity ?> item</p>
                    <p class="text-neutral-400 text-center">Total : <br><span class="text-neutral-100">Rp. <?= $order->total ?></span></p>
                    <?php if($approve == 1){ ?>
                        <button type="submit" id="approve-<?= $order->order_code ?>-btn" data-order="<?= $order->order_code ?>" class="order-approve-btn main-page text-[12px] bg-neutral-300 text-neutral-800 p-1 text-center font-semibold active:bg-neutral-800 active:text-neutral-300 w-full">Approve</button>
                    <?php }else if($approve == 0 && $status_info == "dalam-proses"){ ?>
                        <p class="text-[10px] bg-neutral-700 text-neutral-300 p-1 text-center font-semibold capitalize">Belum Dikonfirmasi</p>
                    <?php }else if($order->status == 0){?>
                    <p class="text-[10px] bg-neutral-800 text-neutral-300 p-1 text-center font-semibold capitalize"><?= ($status_info == "selesai") ? "Selesai" : "Batal" ?></p>
                    <?php } ?>
                </div>
            </div>
        <?php } }else{?>
            <div class="mt-2 text-center">Pesanan tidak ditemukan</div>
            <?php } ?>
        </div>
        <?php }else if(isset($_GET['order-detail'])){
            $chat_ID = $_GET['order-detail'];
            include "components/orders/order-detail.php";
            $order_detail = $orders->orderDetail($conn, $chat_ID);
            orderDetailComp($order_detail);
        } ?>
</div>

<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/orders.js"></script>
<?php include "components/foot.php"?>