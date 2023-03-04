<?php 

    include "database/config.php";

    if(isset($_POST['quantity'])){
        session_start();
        $slug = $_POST['slug'];
        $size = $_POST['size'];
        $qty = $_POST['val'];
        $res = [
            "status" => 200,
            "qty" => $qty,
            "total" => 0
        ];
        foreach($_SESSION['cart'] as $key => $val){
            if($val['slug'] == $slug && $val['size'] == $size){
                $_SESSION['cart'][$key]['qty'] = $qty;
                $upd_total = $_SESSION['cart'][$key]['price'] * $qty;
                $_SESSION['cart'][$key]['total'] = $upd_total;
                $_SESSION['cart'][$key]['total_weight'] = $_SESSION['cart'][$key]['weight'] * $qty;
                $res["total"] = $upd_total; 
                break;
            }
        }
        echo json_encode($res);
        return $res;
    }
    if(isset($_POST['remove'])){
            session_start();
            $slug = $_POST['slug'];
            $size = $_POST['size'];
            foreach ($_SESSION['cart'] as $key => $val) {
                if($val['slug'] == $slug && $val['size'] == $size){
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $res = [
                "status" => 200,
                "count" => count($_SESSION['cart'])
            ];
            echo json_encode($res);
            return $res;
        }

    if(isset($_POST['deleteall'])){
            session_start();
            unset($_SESSION['cart']);
            $res = [
                "status" => 200,
            ];
            echo json_encode($res);
            return $res;
        }
    ?>
<title>Cart | Shadow Dropship</title>
<?php include "components/base.php"; ($nav_role == "admin") ? header("location:index.php") : "" ?>

<div id="cart" class="p-3 mt-20 sm:w-11/12 md:w-10/12 mx-auto">
    <div id="cart-header" class="flex justify-between flex-wrap">
        <h1 class="font-black text-3xl sm:text-4xl">CART</h1>
        <?php if(count($_SESSION['cart']) > 0){?>
        <button id="deleteall-btn" class="text-sm text-neutral-400">Delete All</button>
        <div class="w-full text-neutral-400 text-sm"><?= count($_SESSION['cart']) ?> item</div>
        <?php } ?>
    </div>
    <div id="cart-container" class="mt-8 min-h-[80vh] md:w-10/12 mx-auto">
        <?php if(count($_SESSION['cart']) > 0) {
            foreach(array_reverse($_SESSION['cart']) as $carts) { ?>
        <div id="cart-items-<?= $carts['slug'] ?>-<?= $carts['size'] ?>" class="cart-items bg-neutral-700 my-5 md:my-8">
            <div class="cart-items-product grid grid-cols-4 grid-rows-1 items-center h-[15vh] border-b border-neutral-600 bg-neutral-800 overflow-hidden gap-2">
                <img src="src/products/<?= $carts['image'] ?>" class="object-contain w-full h-[15vh] p-2 bg-neutral-600" alt="">
                <h3 class="font-black text-sm md:text-center md:text-lg"><?= $carts['name'] ?></h3>
                <p class="text-sm text-center"><?= $carts['size'] ?></p>
                <p class="text-[11px] md:text-center">Rp. <?= $carts['price'] ?></p>
            </div>
            <div class="cart-items-total bg-neutral-800 p-1 flex justify-between text-[14px] px-3">
                <p class="text-neutral-500">Stok : <?= $carts['stock'] ?></p>
                <p class="text-neutral-400">x<span id="cart-items-qty-<?= $carts['slug'] ?>-<?= $carts['size'] ?>"> <?= $carts['qty'] ?></span></p>
                <p>Rp. <span id="cart-items-total-<?= $carts['slug'] ?>-<?= $carts['size'] ?>" class="items-total"><?= $carts['total'] ?></span></p>
            </div>
            <div class="cart-items-actions p-1 h-[7vh] bg-neutral-900 flex justify-between items-center px-2">
                <div class="cart-items-quantity-edit flex items-center text-sm">
                    <span class="material-symbols-rounded cursor-pointer text-neutral-400 qty-btn minus">
                        remove
                    </span>
                    <input type="number" name="qty" max="10" min="1" value="<?= $carts['qty'] ?>" class="qty-items mx-3 text-center" data-slug="<?= $carts['slug'] ?>" data-size="<?= $carts['size'] ?>">
                    <span class="material-symbols-rounded cursor-pointer text-neutral-400 qty-btn plus">
                        add
                    </span>
                </div>
                <button class="cart-items-remove" data-slug="<?= $carts['slug'] ?>" data-size="<?= $carts['size'] ?>"><span class="material-symbols-rounded text-xl text-neutral-500">
                    delete
                </span></button>
            </div>
        </div>
        <?php }?>
        <div id="cart-total" class="bg-neutral-800 p-2 flex justify-between items-center mt-10">
            <p class="text-sm sm:text-base md:text-xl">Total : Rp. <span id="cart-alltotal"></span></p>
            <div>
                <?php if(!isset($_SESSION['login'])){?>
                    <a href="login.php" class="p-1 bg-neutral-100 font-black text-sm sm:text-base md:text-xl text-neutral-800">LOGIN</a>
                <?php }else{ ?>
                <a href="checkout.php" class="p-1 text-neutral-100 bg-neutral-900 font-black text-sm sm:text-base md:text-xl">CHECKOUT</a>
                <?php } ?>
            </div>
        </div>
        <?php }else{?>
            <div id="cart-empty" class="h-[50vh] flex justify-center pt-20">
                <h3 class="text-sm sm:text-base md:text-lg">Keranjangmu masih kosong</h3>
            </div>
        <?php } ?>
    </div>
</div>

<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/cart.js"></script>
<?php include "components/foot.php"?>