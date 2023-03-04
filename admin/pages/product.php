<?php 
$allProducts = $products->allProducts($conn);
$allCategory = $products->allCategory($conn);
?>
<div id="product">
    <div id="product-header" class="border-b border-neutral-800 pb-2 border-dashed flex justify-between items-stretch">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Produk</h1>
        <?php if($admin_role == "superadmin"){?>
        <div id="product-header-right" class="text-[13px] flex">
            <a href="index.php?pages=create_product" class="bg-neutral-800 text-neutral-200 p-1 md:p-2 rounded-sm h-full">Buat Produk</a>
            <a href="index.php?pages=create_category" class="border border-neutral-800 text-neutral-800 p-1 md:p-2 rounded-sm ml-2">Buat Kategori</a>
        </div>
        <?php } ?>
    </div>
    <?php if(isset($_SESSION['success'])){?>
    <div id="product-success" class="message bg-green-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
        <p class="w-11/12"><?= $_SESSION['success']['message'] ?></p>
        <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
    </div>
    <?php  unset($_SESSION['success']); } ?>
    <div id="product-container" class="mt-5">
        <?php foreach($allProducts as $product){?>
        <div class="products border border-neutral-700 rounded-md mb-3 shadow-sm sm:w-11/12 mx-auto md:w-9/12 md:mb-5">
            <div class="products-image-name grid grid-cols-2 h-[15vh] justify-center items-center border-b border-neutral-300">
                <img src="../src/products/<?= $product->image ?>" class="h-[15vh] bg-neutral-300 w-full object-contain p-1" alt="">
                <h4 class="font-black text-center text-lg md:text-xl"><?= $product->name ?></h4>
            </div>
            <div class="products-details p-2 text-[12px] grid grid-cols-2 text-neutral-500 gap-x-2 relative">
                <button class="see-more-detail text-[9px] absolute text-neutral-500 top-2 right-2 md:text-3xl"><span class="material-symbols-rounded text-2xl md:text-3xl text-neutral-500">expand_more</span></button>
                <p>Harga : <span class="text-neutral-700"> Rp. <?= $product->price ?></span></p>
                <p>Stok :  <span class="text-neutral-700"> <?= $product->stock ?> </span></p>
                <p>Berat : <span class="text-neutral-700"><?= $product->weight ?> G </span></p>
                <p>Aktif : <span class="text-neutral-700"> <?= ($product->active == true) ? "Aktif" : "Tidak Aktif" ?></span></p>
                <div class="products-detail-more col-span-2 mt-2">
                    <p>Bahan : <span class="text-neutral-700"> <?= $product->material ?></span></p>
                    <p>Deskripsi : <span class="text-neutral-700"><?= $product->description ?></span></p>
                    <p>Dibuat Pada : <span class="text-neutral-700 to-calendar"><?= $product->created_at ?></span></p>
                    <p>Terakhir Diubah : <span class="text-neutral-700 to-calendar"><?= $product->updated_at ?></span></p>
                </div>
            </div>
            <?php if($admin_role == "superadmin"){?>
            <div class="products-actions border-t border-neutral-700 p-1 px-2 text-right">
                <a href="index.php?pages=edit_product&slug=<?= $product->slug ?>"><span class="material-symbols-rounded text-base md:text-xl">
                    edit
                </span></a>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    <div id="category-container" class="mt-8 md:mt-12">
            <h1 class="text-xl sm:text-2xl md:text-3xl pb-2 border-b border-neutral-800 border-dashed">Kategori</h1>
            <?php $no = 1; ?>
            <table class="mt-5 border-collapse border-spacing-2 table-auto border border-neutral-800 w-full text-center">
                <tr class="bg-neutral-800 text-neutral-200">
                    <th class="font-semibold border border-neutral-200">No</th>
                    <th class="font-semibold border border-neutral-200">Nama</th>
                    <th class="font-semibold border border-neutral-200">Slug</th>
                    <th class="font-semibold border border-neutral-200">Image</th>
                    <?php if($admin_role == "superadmin") {?>
                        <th class="font-semibold border border-neutral-200">Aksi</th>
                        <?php }?>
                    </tr>
                    <?php foreach($allCategory as $category){?>
                <tr>
                    <td class="border border-neutral-800"><?= $no++ ?></td>
                    <td class="border border-neutral-800"><?= $category->name ?></td>
                    <td class="border border-neutral-800"><?= $category->slug ?></td>
                    <td class="border border-neutral-800"><img src="../src/category/<?= $category->image ?>" class="w-[80px] h-[80px] object-contain mx-auto" alt=""></td>
                    <?php if($admin_role == "superadmin") {?>
                    <td class="border border-neutral-800"><a href="index.php?pages=edit_category&slug=<?= $category->slug ?>"><span class="material-symbols-rounded text-base md:text-xl ">edit</span></a></td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </table>
    </div>
</div>