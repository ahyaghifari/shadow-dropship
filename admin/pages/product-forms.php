<?php 

function productCreateForm($category){
    if(isset($_SESSION['create_failed'])){
        if($_SESSION['create_failed']['context'] == "product"){
            $name = $_SESSION['create_failed']['name'];
            $slug = $_SESSION['create_failed']['slug'];
            $image = $_SESSION['create_failed']['image'];
            $price = $_SESSION['create_failed']['price'];
            $weight = $_SESSION['create_failed']['weight'];
            $stock = $_SESSION['create_failed']['stock'];
            $material = $_SESSION['create_failed']['material'];
            $active = $_SESSION['create_failed']['active'];
            $description = $_SESSION['create_failed']['description'];
            $category_id = $_SESSION['create_failed']['category_id'];
        }else{
            unset($_SESSION['create_failed']);
            header("Refesh:0");
        }
    }else{
        $name = "";
        $slug = "";
        $image = "";
        $price  = 0;
        $weight = 0;
        $stock = 0;
        $material = "";
        $active = true;
        $description = "";
        $category_id = 0;
    }
    ?>
    
<div id="create-product">
    <div id="create-product-header" class="flex justify-between border-b border-neutral-800 pb-2 border-dashed">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Buat Produk</h1>
        <button class="text-[14px] md:text-sm lg:text-base" onClick="javascript:history.back()">Kembali</button>
    </div>
    <?php if(isset($_SESSION['edit_failed'])){ ?>
        <div id="create-product-failed" class="message bg-red-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
            <p class="w-11/12"><?= $_SESSION['edit_failed']['message'] ?></p>
            <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
        </div>
    <?php } ?>
    <div id="create-product-container" class="mt-5">
    <form id="form-create-product" action="<?= htmlspecialchars('index.php')?>" method="post" class="text-[13px] sm:text-[14px] md:text-base" enctype="multipart/form-data">
        <table class="border-separate border-spacing-x-3 border-spacing-y-2 md:border-spacing-y-3 overflow-hidden">
            <tr>
                <td><label for="nama">Nama</label></td>
                <td>: <input type="text" name="name" id="input-product-name" value="<?= $name ?>" required></td>
            </tr>
            <tr>
                <td><label for="slug">Slug</label></td>
                <td>: <input type="text" name="slug" id="input-product-slug" class="bg-neutral-300 opacity-50 slug-input" value="<?= $slug ?>" readonly required> </td>
            </tr>
            <tr>
                <td><label for="image">Image</label></td>
                <td class="flex w-fit max-w-fit">: <span class="ml-2"><input id="input-product-image" type="file" name="image" class="image-input text-[12px] md:text-[14px]" accept="image/png" required></span><input type="hidden" value="<?= $image ?>" class="image-input-value" value="<?= $image ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><img src="" class="image-preview w-[80px] h-[80px] object-contain md:w-[100px] md:h-[100px]" alt=""></td>
            </tr>
            <tr>
                <td><label for="price">Harga</label></td>
                <td>: <input type="number" name="price" value="<?= $price ?>" required></td>
            </tr>
            <tr>
                <td><label for="weight">Berat</label></td>
                <td>: <input type="number" name="weight" value="<?= $weight ?>" required></td>
            </tr>
            <tr>
                <td><label for="stock">Stok</label></td>
                <td>: <input type="number" name="stock" value="<?= $stock ?>" required></td>
            </tr>
            <tr>
                <td><label for="material">Bahan</label></td>
                <td>: <input type="text" name="material" value="<?= $material  ?>" required></td>
            </tr>
            <tr>
                <td><label for="active">Active</label></td>
                <td>: <input type="checkbox" name="active" <?= ($active == true) ? "checked" : "" ?>></td>
            </tr>
        </table>
        
        <div class="px-3 flex flex-col">
            <label for="description">Description : </label>
            <textarea name="description" id="" cols="30" rows="5" class="sm:w-9/12 md:w-7/12" required><?= $description ?></textarea>
        </div>

        <div class="mt-5 px-3 flex">
            <label for="category">Category : </label>
            <select name="category" id="category" required class="ml-2">
                <option value="">-----</option>
                <?php foreach($category as $category){?>
                <option value="<?= $category->id ?>" <?= ($category->id == $category_id) ? "selected" : "" ?>><?= $category->name ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mt-10 px-3">
            <button type="submit" class="btn-product-crud bg-neutral-800 p-1 text-neutral-200 md:text-xl disabled:opacity-50">Buat</button>
        </div>
        <input type="hidden" name="create_product">
    </form>
    </div>
</div>
<?php }

function productEditForm($data){
    if(isset($_SESSION['edit_failed'])){
        if($_SESSION['edit_failed']['context'] == "product"){
            $name = $_SESSION['edit_failed']['name'];
            $slug = $_SESSION['edit_failed']['slug'];
            $image = $_SESSION['edit_failed']['image'];
            $price = $_SESSION['edit_failed']['price'];
            $weight = $_SESSION['edit_failed']['weight'];
            $stock = $_SESSION['edit_failed']['stock'];
            $material = $_SESSION['edit_failed']['material'];
            $active = $_SESSION['edit_failed']['active'];
            $description = $_SESSION['edit_failed']['description'];
            $category_id = $_SESSION['edit_failed']['category_id'];
            $old_slug = $_SESSION['edit_failed']['old_slug'];
        }else{
            unset($_SESSION['edit_failed']);
            header("Refesh:0");
        }
    }else{
        foreach($data['product'] as $product){
            $name = $product->name;
            $slug = $product->slug;
            $image = $product->image;
            $price = $product->price;
            $weight = $product->weight;
            $stock = $product->stock;
            $material = $product->material;
            $active = $product->active;
            $description = $product->description;
            $category_id = $product->category_id;
            $old_slug = $product->slug;
        }
    }
    ?>
<div id="edit-product">
    <div id="edit-product-header" class="flex justify-between border-b border-neutral-800 pb-2 border-dashed">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Edit Produk</h1>
        <button class="text-[14px] md:text-sm lg:text-base" onClick="javascript:history.back()">Kembali</button>
    </div>
    <?php if(isset($_SESSION['edit_failed'])){ ?>
        <div id="edit-product-failed" class="message bg-red-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
            <p class="w-11/12"><?= $_SESSION['edit_failed']['message'] ?></p>
            <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
        </div>
    <?php } ?>
    <div id="edit-product-container" class="mt-5">
    <form id="form-edit-product" action="<?= htmlspecialchars('index.php')?>" method="post" class="text-[13px] sm:text-[14px] md:text-base" enctype="multipart/form-data">
        <table class="border-separate border-spacing-x-3 border-spacing-y-2 md:border-spacing-y-3 overflow-hidden">
            <tr>
                <td><label for="nama">Nama</label></td>
                <td>: <input type="text" name="name" id="input-product-name" value="<?= $name ?>" required></td>
            </tr>
            <tr>
                <td><label for="slug">Slug</label></td>
                <td>: <input type="text" name="slug" id="input-product-slug" class="bg-neutral-300 opacity-50 slug-input" value="<?= $slug ?>" readonly required> </td>
            </tr>
            <tr>
                <td><label for="image">Image</label></td>
                <td class="flex w-fit max-w-fit">: <span class="ml-2"><input id="input-product-image" type="file" name="image" class="image-input text-[12px] md:text-[14px]" accept="image/png"></span><input type="hidden" name="old_image" value="<?= $image ?>" class="image-input-value" value="<?= $image ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><img src="" class="image-preview w-[80px] h-[80px] object-contain md:w-[100px] md:h-[100px]" alt=""></td>
            </tr>
            <tr>
                <td><label for="price">Harga</label></td>
                <td>: <input type="number" name="price" value="<?= $price ?>" required></td>
            </tr>
            <tr>
                <td><label for="weight">Berat</label></td>
                <td>: <input type="number" name="weight" value="<?= $weight ?>" required></td>
            </tr>
            <tr>
                <td><label for="stock">Stok</label></td>
                <td>: <input type="number" name="stock" value="<?= $stock ?>" required></td>
            </tr>
            <tr>
                <td><label for="material">Bahan</label></td>
                <td>: <input type="text" name="material" value="<?= $material  ?>" required></td>
            </tr>
            <tr>
                <td><label for="active">Active</label></td>
                <td>: <input type="checkbox" name="active" <?= ($active == true) ? "checked" : "" ?>></td>
            </tr>
        </table>
        
        <div class="px-3 flex flex-col">
            <label for="description">Description : </label>
            <textarea name="description" id="" cols="30" rows="5" class="sm:w-9/12 md:w-7/12" required><?= $description ?></textarea>
        </div>

        <div class="mt-5 px-3 flex">
            <label for="category">Category : </label>
            <select name="category" id="category" required class="ml-2">
                <?php foreach($data['category'] as $category){?>
                <option value="<?= $category->id ?>" <?= ($category->id == $category_id) ? "selected" : "" ?>><?= $category->name ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mt-10 px-3">
            <button type="submit" class="btn-product-crud bg-neutral-800 p-1 text-neutral-200 md:text-xl disabled:opacity-50">Update</button>
        </div>
        <input type="hidden" name="edit_product">
        <input type="hidden" id="old-slug" name="old_slug" value="<?= $old_slug ?>">
    </form>
    <form id="form-delete-product" action="<?= htmlspecialchars('index.php')?>" method="post" class="px-3 mt-6">
        <input type="hidden" name="delete_product">
        <input type="hidden" name="name" value=<?= $name ?>>
        <input type="hidden" name="slug" value=<?= $old_slug ?>>
        <button type="submit" id="delete-product-btn" class="border border-neutral-800 p-1 md:text-xl">Delete</button>
    </form>
    </div>
</div>
<?php } 

function categoryEditForm($data){
    if(isset($_SESSION['edit_failed'])){
        if($_SESSION['edit_failed']['context'] == "category"){
            $name = $_SESSION['edit_failed']['name'];
            $slug = $_SESSION['edit_failed']['slug'];
            $image = $_SESSION['edit_failed']['image'];
            $old_slug = $_SESSION['edit_failed']['old_slug'];
        }else{
            unset($_SESSION['edit_failed']);
            header("Refresh:0");
        }
    }else{
        foreach($data as $category){
            $name = $category->name;
            $slug = $category->slug;
            $image = $category->image;
            $old_slug = $category->slug;
        }
    }
    ?>
<div id="edit-category">
    <div id="edit-category-header" class="flex justify-between border-b border-neutral-800 pb-2 border-dashed">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Edit Kategori</h1>
        <button class="text-[14px] md:text-sm lg:text-base" onClick="javascript:history.back()">Kembali</button>
    </div>
    <?php if(isset($_SESSION['edit_failed'])){ ?>
        <div id="edit-category-failed" class="message bg-red-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
            <p class="w-11/12"><?= $_SESSION['edit_failed']['message'] ?></p>
            <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
        </div>
    <?php } ?>
    <div id="edit-category-container" class="mt-5">
    <form id="form-edit-category" action="<?= htmlspecialchars('index.php')?>" method="post" class="text-[13px] sm:text-[14px] md:text-base" enctype="multipart/form-data">
        <table class="border-separate border-spacing-x-3 border-spacing-y-2 md:border-spacing-y-3 overflow-hidden">
            <tr>
                <td><label for="name">Nama</label></td>
                <td>: <input type="text" name="name" id="input-category-name" value="<?= $name ?>" required></td>
            </tr>
            <tr>
                <td><label for="slug">Slug</label></td>
                <td>: <input type="text" name="slug" id="input-category-slug" class="bg-neutral-300 opacity-50 slug-input" value="<?= $slug ?>" readonly required> </td>
            </tr>
            <tr>
                <td><label for="image">Image</label></td>
                <td class="flex w-fit max-w-fit">: <span class="ml-2"><input id="input-category-image" type="file" name="image" class="image-input text-[12px] md:text-[14px]" accept="image/png"></span><input type="hidden" name="old_image" value="<?= $image ?>" class="image-input-value" value="<?= $image ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><img src="" class="image-preview w-[80px] h-[80px] object-contain md:w-[100px] md:h-[100px]" alt=""></td>
            </tr>
        </table>

        <div class="mt-10 px-3">
            <button type="submit" class="btn-product-crud bg-neutral-800 p-1 text-neutral-200 md:text-xl disabled:opacity-50">Update</button>
        </div>
        <input type="hidden" name="edit_category">
        <input type="hidden" id="old-slug" name="old_slug" value="<?= $old_slug ?>">
    </form>
    </div>
</div>
<?php }

function categoryCreateForm(){
    if(isset($_SESSION['create_failed'])){
        if($_SESSION['create_failed']['context'] == "category"){
            $name = $_SESSION['create_failed']['name'];
            $slug = $_SESSION['create_failed']['slug'];
            $image = $_SESSION['create_failed']['image'];
        }else{
            unset($_SESSION['create_failed']);
            header("Refresh:0");
        }
    }else{
        $name = "";
        $slug = "";
        $image = "";
    }
    ?>
<div id="create-category">
    <div id="create-category-header" class="flex justify-between border-b border-neutral-800 pb-2 border-dashed">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Buat Kategori</h1>
        <button class="text-[14px] md:text-sm lg:text-base" onClick="javascript:history.back()">Kembali</button>
    </div>
    <?php if(isset($_SESSION['create_failed'])){ ?>
        <div id="create-category-failed" class="message bg-red-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
            <p class="w-11/12"><?= $_SESSION['create_failed']['message'] ?></p>
            <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
        </div>
    <?php } ?>
    <div id="create-category-container" class="mt-5">
    <form id="form-create-category" action="<?= htmlspecialchars('index.php')?>" method="post" class="text-[13px] sm:text-[14px] md:text-base" enctype="multipart/form-data">
        <table class="border-separate border-spacing-x-3 border-spacing-y-2 md:border-spacing-y-3 overflow-hidden">
            <tr>
                <td><label for="name">Nama</label></td>
                <td>: <input type="text" name="name" id="input-category-name" value="<?= $name ?>" required></td>
            </tr>
            <tr>
                <td><label for="slug">Slug</label></td>
                <td>: <input type="text" name="slug" id="input-category-slug" class="bg-neutral-300 opacity-50 slug-input" value="<?= $slug ?>" readonly required> </td>
            </tr>
            <tr>
                <td><label for="image">Image</label></td>
                <td class="flex w-fit max-w-fit">: <span class="ml-2"><input id="input-category-image" type="file" name="image" class="image-input text-[12px] md:text-[14px]" accept="image/png"></span><input type="hidden" name="old_image" value="<?= $image ?>" class="image-input-value" value="<?= $image ?>" required></td>
            </tr>
            <tr>
                <td colspan="2"><img src="" class="image-preview w-[80px] h-[80px] object-contain md:w-[100px] md:h-[100px]" alt=""></td>
            </tr>
        </table>

        <div class="mt-10 px-3">
            <button type="submit" class="btn-product-crud bg-neutral-800 p-1 text-neutral-200 md:text-xl disabled:opacity-50">Buat</button>
        </div>
        <input type="hidden" name="create_category">
    </form>
    </div>
</div>
<?php } ?>