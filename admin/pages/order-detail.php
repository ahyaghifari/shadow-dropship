<?php 
function orderDetailComp($order_detail){?>
   <div id="order-detail" class="mx-auto sm:w-11/12">
    <button  onClick="javascript:history.back();" class="float-right"><span class="material-symbols-rounded text-xl text-neutral-400 md:text-2xl">
        arrow_back_ios</span></button>
        <div id="order-detail-header" class="clear-both border-b border-neutral-500 border-dashed pb-2">
            <h1 class="text-md:text-2xl">Detail Pesanan : <span class="font-semibold"><?= $_GET['order_code'] ?></span> </h1>
        </div>
        <div id="order-detail-container" class="mt-5 mx-auto sm:w-11/12 md:grid md:grid-cols-2 md:mt-10 md:gap-x-5">
            <?php foreach($order_detail as $order){
                $approve = $order['payment']['payment_approve'];
                ?>
                <div id="order-detail-detail" class="shadow-md">
                    <h3 class="font-semibold lg:text-xl md:border-b md:border-neutral-700">Pesanan</h3>
                    <table class="text-[13px] lg:text-sm border-separate border-spacing-2">
                        <tr>
                            <td>Kode Pemesanan </td>
                            <td>: <?= $order['order_code'] ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td id="detail-order-status" class="capitalize">: <span class="font-semibold"><?= $order['order_info'] ?></span></td>
                        </tr>
                        <tr>
                            <td>Kuantitas Produk</td>
                            <td>: <?= $order['order_quantity'] ?> produk</td>
                        </tr>
                        <tr>
                            <td>Total Keseluruhan</td>
                            <td>: <span class="font-semibold">Rp. <?= $order['order_total'] ?></span></td>
                        </tr>
                        <tr>
                            <td>Dibuat Pada</td>
                            <td>: <?= $order['order_created_at'] ?></td>
                        </tr>
                        <?php if($approve == 1){?>
                        <tr><td><button type="submit" id="approve-<?= $order['order_code'] ?>-btn" data-order="<?= $order['order_code'] ?>" class="order-approve-btn detail-page text-[12px] bg-neutral-300 text-neutral-800 p-1 text-center font-semibold active:bg-neutral-800 active:text-neutral-300 w-full">Approve</button></td></tr>
                        <?php } ?>
                    </table>
                </div>
                <div id="order-detail-receiver" class="mt-8 shadow-md md:mt-0">
                    <h3 class="font-semibold lg:text-xl  md:border-b md:border-neutral-700">Penerima</h3>
                    <table class="text-[13px] border-separate border-spacing-2 lg:text-sm">
                        <tr>
                            <td>Nama</td>
                            <td>: <span class="font-semibold"><?= $order['receiver']['receiver_name'] ?></span></td>
                        </tr>
                        <tr>
                            <td>Nomor Telepon</td>
                            <td>: <?= $order['receiver']['receiver_phone_number'] ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: <?= $order['receiver']['receiver_address'] ?></td>
                        </tr>
                        <tr>
                            <td>Kode Pos </td>
                            <td>: <?= $order['receiver']['receiver_post_code'] ?></td>
                        </tr>
                        <tr>
                            <td>Kota </td>
                            <td>: <span class="font-semibold"><?= $order['receiver']['receiver_city'] ?></span> </td>
                        </tr>
                        <tr>
                            <td>Provinsi </td>
                            <td>: <span class="font-semibold"><?= $order['receiver']['receiver_province'] ?></span></td>
                        </tr>
                    </table>
                </div>
                <div id="order-detail-products" class="mt-8 md:col-span-2">
                    <h3 class="font-semibold lg:text-xl">Produk</h3>
                    <?php foreach($order['order_products'] as $order_products){
                        foreach($order['products'] as $products){
                        if($products['product_id'] == $order_products['order_product_product']){?>
                    <div class="order-products my-2 sm:w-11/12 md:w-10/12 mx-auto lg:w-9/12">
                        <div class="order-product-detail grid grid-cols-4 grid-rows-1 items-center h-[10vh] border-b border-neutral-600 bg-neutral-300 overflow-hidden gap-2">
                            <img src="../src/products/<?= $products['products_image'] ?>" class="object-contain w-full h-[10vh] p-2 bg-neutral-400" alt="">
                            <h3 class="font-black text-[13px] md:text-center md:text-lg"><?= $products['products_name'] ?></h3>
                            <p class="text-[11px] md:text-center"><?= $order_products['order_product_size'] ?></p>
                            <p class="text-[11px] md:text-center">Rp. <?= $products['products_price'] ?></p>
                        </div>
                        <div class="order-total bg-neutral-300 p-1 grid grid-cols-3 text-[12px] px-3">
                            <p class="text-neutral-500 col-start-2 text-center">x<?= $order_products['order_product_quantity'] ?></span></p>
                            <p class="text-center">Rp. <span class="product-totals"><?= $order_products['order_product_total'] ?></span></p>
                            <p class="weight-totals text-center hidden"><?= $order_products['order_product_weight'] ?></p>
                        </div>
                    </div>    
                    <?php }}} ?>
                </div>
                <div id="order-detail-shipment" class="mt-8 shadow-md md:col-span-2">
                    <h3 class="font-semibold lg:text-xl">Pengiriman</h3>
                    <table class="text-[13px] border-separate border-spacing-2 lg:text-sm">
                        <tr>
                            <td>Kode Pengiriman</td>
                            <td>: <span class="font-semibold"><?= $order['shipment']['shipment_code'] ?></span> </td>
                        </tr>
                        <tr>
                            <td>Pengirim</td>
                            <td>: <span class="font-semibold"><?= $order['shipment']['shipment_shipper'] ?></span></td>
                        </tr>
                        <tr>
                            <td>Kurir </td>
                            <td class="uppercase">: <?= $order['shipment']['shipment_courier'] ?></td>
                        </tr>
                        <tr>
                            <td>Layanan </td>
                            <td>: <?= $order['shipment']['shipment_service'] ?></td>
                        </tr>
                        <tr>
                            <td>Berat Total </td>
                            <td>: <?= $order['shipment']['shipment_weight'] ?> Gram</td>
                        </tr>
                        <tr>
                            <td>Biaya Pengiriman </td>
                            <td>: <span class="font-semibold">Rp. <?= $order['shipment']['shipment_cost'] ?></span></td>
                        </tr>
                    </table>
                </div>
                <div id="order-detail-payment" class="mt-8 shadow-md md:col-span-2">
                    <h3 class="font-semibold lg:text-xl">Pembayaran</h3>
                    <table class="text-[13px] border-separate border-spacing-2 lg:text-sm">
                        <tr>
                            <td>Kode Pembayaran </td>
                            <td>: <span class="font-semibold"><?= $order['payment']['payment_code'] ?></span></td>
                        </tr>
                        <tr>
                            <td>Metode </td>
                            <td>: <?= $order['payment']['payment_method'] ?></td>
                        </tr>
                        <tr>
                            <td>Approve </td>
                            <td>: <span class="font-semibold"> <?= ($order['payment']['payment_approve'] == 0) ? "Approve" : "Not Approved" ?></span></td>
                        </tr>
                    </table>
                </div>

                <?php if($order['order_status'] == 1){?>
                <div id="order-actions" class="mt-12 flex md:col-start-2 md:place-self-end">
                    <?php if($order['payment']['payment_approve'] == 1){?>
                        <button class="bg-neutral-300 text-neutral-800 p-1 text-center font-semibold disabled:opacity-50 lg:text-xl" disabled>Konfirmasi</button>
                    <?php }else{?>
                        <form action="user.php" method="post">
                            <input type="hidden" name="confirm_order">
                            <input type="hidden" name="order_code" value="<?= $order['order_code'] ?>">
                            <button type="submit" class="bg-neutral-300 text-neutral-800 p-1 text-center font-semibold active:bg-neutral-800 active:text-neutral-300 lg:text-xl">Konfirmasi</button>
                        </form>
                        
                        <?php } ?>
                        <form id="form-cancelorder" action="user.php" class="ml-5" method="post">
                            <input type="hidden" name="cancel_order">
                            <input type="hidden" name="order_code" value="<?= $order['order_code'] ?>">
                            <button id="cancel-order-btn" type="submit" class="text-sm lg:text-base bg-red-600 p-1">Batalkan</button>
                        </form>
                </div>
                <?php } ?>
                
            <?php } ?>
        </div>
   </div>
<?php } 