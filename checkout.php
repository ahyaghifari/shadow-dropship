<?php 
    include "database/api_keys.php";
    include "database/config.php";
    include "database/Orders.php";

    $orders = new Orders;

    if(isset($_GET['province'])){
        $url = "https://api.rajaongkir.com/starter/province";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("key: $apiKey"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($ch);
        curl_close($ch);

        echo $res;
        return $res;
    }

    if(isset($_GET['city'])){
        $id = $_GET['id'];
        $url = "https://api.rajaongkir.com/starter/city?province=$id";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("key: $apiKey"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($ch);
        curl_close($ch);

        echo $res;
        return $res;
    }

    if(isset($_POST['checkout_process'])){
        $orders->create($conn, $_POST['all_total'], $_POST['receiver_name'], $_POST['receiver_phone'], $_POST['receiver_address'], $_POST['receiver_postcode'], $_POST['receiver_province'], $_POST['receiver_city'], $_POST['shipping_type'], $_POST['shipping_cost'], $_POST['shipping_weight'], $_POST['checkout_payment']);
        return;
    }

    if(isset($_POST['shipping_cost'])){
        $id = $_POST['id'];
        $weight = $_POST['weight'];
        $url = "https://api.rajaongkir.com/starter/cost";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "origin=35&destination=$id&weight=$weight&courier=jne");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "content-type: application/x-www-form-urlencoded", 
            "key: $apiKey"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $res = curl_exec($ch);
        curl_close($ch);
        
        echo $res;
        return $res;
    }

    include "components/base_checkout.php";
    if(!isset($_SESSION['login'])){
        header("location: login.php");
    }
    if(count($_SESSION['cart']) == 0){
        header("location: cart.php");
    }
?>
<title>Checkout | Shadow Dropship</title>
<div id="checkout">
    <div id="checkout-header" class="p-3 flex justify-between items-center sm:w-11/12 mx-auto md:w-10/12">
        <h1 class="font-black sm:text-lg md:text-2xl">CHECKOUT</h1>
        <a onClick="javascript:history.go(-1);" class="cursor-pointer"><span class="material-symbols-rounded text-xl text-neutral-400 sm:text-2xl md:text-3xl">
            arrow_back_ios
        </span></a>
    </div>
    <div id="checkout-container" class="p-3 md:mt-12">
        <form id="checkout-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="sm:w-10/12 mx-auto md:w-9/12">
        <input type="hidden" name="checkout_process">
        <div id="checkout-receiver" class="grid grid-cols-2 gap-x-2 gap-y-5">        
            <h2 class="col-span-2 font-semibold md:text-xl">Data Penerima : </h2>
            <div class="checkout-form-components">
                <label for="receiver-name">Nama Penerima :<span class="text-red-500">*</span></label>
                <input type="text" name="receiver_name" id="receiver-name" required>
            </div>
            <div class="checkout-form-components">
                <label for="receiver-phone">Telepon Penerima : <span class="text-red-500">*</span></label>
                <input type="text" name="receiver_phone" id="receiver-phone" required>
            </div>
            <div class="checkout-form-components">
                <label for="receiver-address">Alamat Penerima :<span class="text-red-500">*</span> </label>
                <input type="text" name="receiver_address" id="receiver-address" required>
            </div>
            <div class="checkout-form-components">
                <label for="receiver-postcode">Kode Pos : </label>
                <input type="text" name="receiver_postcode" id="receiver-postcode">
            </div>
            <div class="checkout-form-components col-span-2 md:col-span-1">
                <label for="receiver-province">Provinsi : <span class="text-red-500">*</span></label>
                <select name="receiver_province" id="receiver-province" class="w-full" required>
                    <option value="">------</option>
                </select>
            </div>
            <div class="checkout-form-components col-span-2 md:col-span-1">
                <label for="receiver-city">Kota : <span class="text-red-500">*</span></label>
                <select name="receiver_city" id="receiver-city" disabled="disabled" required>
                    <option value="">------</option>
                </select>
            </div>
        </div>
        <div id="checkout-products" class="mt-10 md:mt-16">
            <h2 class="font-semibold mb-4 md:text-xl">Produk : </h2>
            <?php foreach($_SESSION['cart'] as $carts){ ?>
            <div class="checkout-products my-3">
                <div class="checkout-product-detail grid grid-cols-4 grid-rows-1 items-center h-[15vh] border-b border-neutral-600 bg-neutral-800 overflow-hidden gap-2">
                    <img src="src/products/<?= $carts['image'] ?>" class="object-contain w-full h-[15vh] p-2 bg-neutral-600" alt="">
                    <h3 class="font-black text-sm md:text-center md:text-lg"><?= $carts['name'] ?></h3>
                    <p class="text-sm text-center"><?= $carts['size'] ?></p>
                    <p class="text-[11px] md:text-center">Rp. <?= $carts['price'] ?></p>
                </div>
                <div class="checkout-total bg-neutral-800 p-1 grid grid-cols-3 text-[12px] px-3">
                    <p class="text-neutral-400 col-start-2 text-center">x<?= $carts['qty'] ?></span></p>
                    <p class="text-center">Rp. <span class="product-totals"><?= $carts['total'] ?></span></p>
                    <p class="weight-totals text-center hidden"><?= $carts['total_weight'] ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
        <div id="checkout-shipping" class="mt-10 md:mt-16">
            <h2 class="font-semibold mb-2 md:text-xl">Metode Pengiriman :</h2>
            <ul>
                <li class="text-[11px]">Barang akan di kirimkan oleh JNE langsung ke alamat penerima</li>
                <li class="text-[11px]">Barang akan di kirimkan atas nama toko kamu</li>
                <li class="text-[10px] text-neutral-400">Jika kamu belum mengatur nama toko kamu, nama pengirim otomatis adalah username kamu</li>
            </ul>
            <div class="mt-5 flex flex-col">
                <p class="text-sm">Jenis Layanan : <span class="text-red-500">*</span></p>
                <select name="shipping_type" id="shipping-type" class="w-1/2 text-[12px]" required>
                    <option value="">------</option>
                </select>
                <input type="hidden" name="shipping_cost" id="shipping-cost" value="">
                <input type="hidden" name="shipping_weight" id="shipping-weight" value="<?= $carts['total_weight'] ?>">
            </div>
        </div>
        <div id="checkout-payment" class="mt-10 md:mt-16">
            <h2 class="font-semibold mb-2 md:text-xl">Metode Pembayaran :<span class="text-red-500">*</span></h2>
            <select name="checkout_payment" id="checkout-payment" required>
                <option value="">------</option>
                <option value="COD">COD</option>
                <option value="Bayar Di Depan">Bayar Di Depan</option>
            </select>
            <ul class="text-[11px] text-neutral-500 mt-2">
                <li>Jika pembayaran <span class="font-semibold">COD</span> maka penjual bisa langsung konfirmasi</li>
                <li>Jika pembayaran <span class="font-semibold">Bayar Di Depan</span> penjual harus menunggu konfirmasi dari admin</li>
            </ul>
        </div>
        <div id="checkout-totals" class="text-[12px] sm:text-[14px] md:text-base mt-12 bg-neutral-900 p-2">
            <table class="w-full">
                <tr>
                    <td class="text-neutral-400">Subtotal Produk : </td>
                    <td>Rp. <span class="subtotal-product checkout-totals"></span></td>
                </tr>
                <tr>
                    <td class="text-neutral-400">Berat : </td>
                    <td><span class="checkout-total_weight"></span> Kg</td>
                </tr>
                <tr>
                    <td class="text-neutral-400">Biaya Pengiriman : </td>
                    <td>Rp. <span class="shipping-cost checkout-totals">0</span></td>
                </tr>
                <tr class="text-sm md:text-base font-semibold">
                    <td class="text-neutral-100">Total Keseluruhan : </td>
                    <td>Rp. <span class="all-total">0</span></td>
                </tr>
            </table>
            <input type="hidden" name="all_total" id="all_total">
        </div>
    <div id="checkout-button" class="mt-12 text-right">
            <button type="submit" class="bg-neutral-200 text-neutral-800 p-2 font-black hover:bg-neutral-800 hover:text-neutral-100">PROCESS</button>
        </div>
        </form>
    </div>
</div>
<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/checkout.js"></script>
<?php include "components/foot.php"?>