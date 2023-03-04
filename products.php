<?php 
    include "database/config.php";
    include "database/Products.php";


    $products = new Products();

    if(isset($_GET['getdetail'])){  
        $slug = $_GET['slug'];
        $product = [];
        foreach($products->detailProduct($conn, $slug) as $pro){
            $product= $pro;
        }
        echo json_encode($product);
        return $product;
    }
    if(isset($_GET['addtocart'])){  
        $slug = $_GET['slug'];
        $product = [];
        foreach($products->addToCart($conn, $slug) as $pro){
            $product= $pro;
        }
        echo json_encode($product);
        return $product;
    }
    if(isset($_POST['cart'])){
        session_start();
        $slug = $_POST['slug'];
        $size = $_POST['size'];
        $qty = $_POST['qty'];
        $data = [];
        $res = [
            "context" => "",
            "status" => 400
        ];
        $product_exists = false;
        if(count($_SESSION['cart']) > 0){
                foreach($_SESSION['cart'] as $key => $val){
                    if($val['slug'] == $slug && $val['size'] == $size){
                        $upd_qty = $_SESSION['cart'][$key]['qty'] += $qty;
                        $_SESSION['cart'][$key]['total'] = $_SESSION['cart'][$key]['price'] * $upd_qty;
                        $_SESSION['cart'][$key]['total_weight'] = $_SESSION['cart'][$key]['weight'] * $upd_qty;
                        $product_exists = true;
                        $res["status"] = 200;
                        $res["context"] = "update";
                        break;
                    }
                }
            }
        if(!$product_exists){
            foreach($products->addToCart($conn, $slug) as $pro){
                $data = $pro;
            }
                    
            $data += [
                'size' => $size,
                'qty' => $qty,
                'total_weight' => $data['weight'] * $qty,
                'total' => $data['price'] * $qty 
            ];
            array_push($_SESSION['cart'], $data);
            $res["status"] = 200;
            $res["context"] = "new";
        }
        echo json_encode($res);
        return $res;
    }
?>    
<title>Produk | Shadow Dropship</title>
<?php  include "components/base.php"; ?>


<div id="products" class="px-3 mt-20">
    <div id="products-header" class="text-center">
        <h1 class="text-2xl font-black sm:text-3xl md:text-4xl">PRODUK</h1>
    </div>
    <div id="products-container" class="mt-10 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1 md:gap-2 lg:gap-5">
        <?php foreach($products->allProducts($conn) as $pro){ ?>
        <div class="products bg-neutral-800 shadow-md h-[50vh] relative z-[1] <?= ($pro->stock == 0) ? "opacity-50" : "" ?>">
            <div class="products-image h-1/2 bg-neutral-700">
                <img src="src/products/<?= $pro->image ?>" class="h-full object-contain w-full" alt="">
            </div>
            <div id="products-detail" class="md:mt-3 p-3 grid grid-rows-4 grid-cols-2">
                <h4 class="font-black uppercase col-span-2 text-sm md:text-xl"><?= $pro->name ?></h4>
                <h4 class="font-semibold text-[13px] col-span-2 md:text-sm">Rp. <?= $pro->price ?></h4>
                <p class="text-[13px] text-neutral-500">Stok : <?= $pro->stock ?></p>
                <button class="product-info place-self-end text-neutral-400" data-product="<?= $pro->slug ?>"><span class="material-symbols-rounded text-base md:text-lg">
                    info
                </span></button>
                <?php if(!isset($_SESSION['login']) || $_SESSION['user']['role'] == "user"){
                    if($pro->stock != 0){
                ?>
                <button class="order text-[11px] col-span-2 bg-neutral-200 text-neutral-900 p-[1px] text-sm self-end active:bg-neutral-800 active:text-neutral-100 md:text-base" data-product="<?= $pro->slug ?>">Pesan</button>
                <?php } }else{?>
                    <p class="opacity-50 cursor-default text-center text-[11px] col-span-2 bg-neutral-200 text-neutral-900 p-[1px] text-sm self-end md:text-base">Pesan</p>
                <?php }  ?>
            </div>
            <?php if($pro->stock == 0){?>
                <div class="empty-product absolute top-0 let-0 w-full h-full flex justify-center items-center">
                    <h5 class="md:text-lg font-semibold lg:text-xl">Stok kosong</h5>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>


<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/products.js"></script>
<?php include "components/foot.php"?>
