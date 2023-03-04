<?php

class Products{
    public function allProducts($conn){
        $products = $conn->query("SELECT * FROM products");
        while($pro = $products->fetch_object()){
            $result[] = $pro;
        }
        return $result;
    }
    public function allCategory($conn){
        $categories = $conn->query("SELECT * FROM category");
        while($category = $categories->fetch_object()){
            $result[] = $category;
        }
        return $result;
    }
    public function detailProduct($conn, $slug){
        $product = $conn->query("SELECT products.*, category.*, products.name as products_name, products.image as products_image FROM products INNER JOIN category ON products.category_id = category.id WHERE products.slug='$slug'");
        while($pro = $product->fetch_object()){
            $result[] = $pro;
        }
        return $result;
    }

    public function addToCart($conn, $slug){
        $product = $conn->query("SELECT id, name, slug, image, price, stock, weight FROM products WHERE slug='$slug'");
        while($pro = $product->fetch_assoc()){
            $result[] = $pro;
        }
        return $result;
    } 
    public function checkSlugProduct($conn, $slug){
        $response = [];
        $checkSlug = $conn->query("SELECT slug FROM products WHERE slug='$slug'");
        if($checkSlug->num_rows > 0){
            $response += [
                "result" => false,
                "message" => "Nama produk tidak tersedia"
            ];
        }else{
            $response += [
                "result" => true,
                "message" => "Nama produk tersedia"
            ];
        }
        $response += [
            "status" => 200
        ];
        echo json_encode($response);
        return $response;
    }

    public function editProduct($conn, $slug){
        $getProduct = $conn->query("SELECT *, products.id as product_id, products.name as product_name FROM products WHERE slug='$slug'");
        $product_data = [];
        while($product = $getProduct->fetch_object()){
            $product_data[] = $product;
        }
        
        $getCategory = $conn->query("SELECT category.id, category.name FROM category");
        $category_data = [];
        while($category = $getCategory->fetch_object()){
            $category_data[] = $category;
        }
        
        $result = [
            "product" => $product_data,
            "category" => $category_data
        ];

        return $result;
    }
    public function updateProduct($conn, $post, $files){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // DATA
        $name = test_input($post['name']);
        $slug = test_input($post['slug']);
        $price = $post['price'];
        $weight = $post['weight'];
        $stock = $post['stock'];
        $material = test_input($post['material']);
        $active = true;
        if(!$post['active']){
            $active = false;
        }
        $description = $post['description'];
        $category_id = $post['category'];
        
        $old_image = test_input($post['old_image']);
        $old_slug = test_input($post['old_slug']);
        
        //IMAGE
        $image = $files['image']['name'];
        $image_from = $files['image']['tmp_name'];
        $image_error = $files['image']['error'];
        $image_format = pathinfo($image, PATHINFO_EXTENSION);

        function editFailed($message){
            $_SESSION['edit_failed'] = [
                "name" => $name,
                "slug" => $slug,
                "price" => $price,
                "weight" => $weight,
                "stock" => $stock,
                "material" => $material,
                "active" => $active,
                "description" => $description,
                "category_id" => $category_id,
                "old_slug" => $old_slug,
                
                "image" => $image_from,

                "context" => "product",
                "message" => $message
            ];
            header("location: index.php?pages=edit_product&slug=".$old_slug);
            
        }

        // CHECK SLUG
        if($slug != $old_slug){
            $checkSlug = $conn->query("SELECT slug FROM products WHERE slug='$slug'");
            if($checkSlug->num_rows > 0){
                editFailed("Nama produk tidak tersedia");
                return;
            }
        }

        // CHECK IMAGE
        if($image != NULL && $image != $old_image){
            $deleteOldImage = unlink("../src/products/".$old_image);
            if(!$deleteOldImage){
                editFailed("Gambar lama tidak berhasil dihapus");
                return;
            }
            if($image_error == 0){
                if($image_format == "png"){
                    $image = str_replace("png", "", $image);
                    $image = $slug.".png";

                    $move = move_uploaded_file($image_from, "../src/products/".$image);

                    if (!$move) {
                        editFailed("Gambar tidak berhasil dipindahkan");    
                    }
                }else{
                    editFailed("Gambar hanya untuk format png");
                    return;
                }
            }else{
                editFailed("Gambar error");
                return;
            }
        }else{
            $image = htmlspecialchars($old_image);
        }

        //UPDATE
        $updateProduct = $conn->query("UPDATE products SET name='$name', slug='$slug', image='$image', price='$price', weight='$weight', stock='$stock', material='$material', active='$active', description='$description', category_id='$category_id' WHERE slug='$old_slug'");

        if($updateProduct === TRUE){
            $_SESSION['success'] = [
                "message" => "Produk ". $name . " berhasil diperbaharui"
            ];
            header("location: index.php?pages=product");
        }else{
            editFailed("Produk tidak berhasil diperbaharui");
            return;
        }

    }
    public function createProduct($conn, $post, $files){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // DATA
        $name = test_input($post['name']);
        $slug = test_input($post['slug']);
        $price = $post['price'];
        $weight = $post['weight'];
        $stock = $post['stock'];
        $material = test_input($post['material']);
        $active = true;
        if(!$post['active']){
            $active = false;
        }
        $description = $post['description'];
        $category_id = $post['category'];
        
        //IMAGE
        $image = $files['image']['name'];
        $image_from = $files['image']['tmp_name'];
        $image_error = $files['image']['error'];
        $image_format = pathinfo($image, PATHINFO_EXTENSION);

        function createFailed($message){
            session_start();
            $_SESSION['create_failed'] = [
                "name" => $name,
                "slug" => $slug,
                "weight" => $weight,
                "stock" => $stock,
                "material" => $material,
                "active" => $active,
                "description" => $description,
                "category_id" => $category_id,
                
                "image" => $image_from,

                "context" => "product",
                "message" => $message
            ];
            header("location: index.php?pages=create_product");
        }

        // CHECK SLUG
        $checkSlug = $conn->query("SELECT slug FROM products WHERE slug='$slug'");
        if($checkSlug->num_rows > 0){
            createFailed("Nama produk tidak tersedia");
            return;
        }

        // CHECK IMAGE
        if($image_error == 0){
            if($image_format == "png"){
                $image = str_replace("png", "", $image);
                $image = $slug.".png";

                $move = move_uploaded_file($image_from, "../src/products/".$image);

                if (!$move) {
                    createFailed("Gambar tidak berhasil dipindahkan");    
                }
                }else{
                    createFailed("Gambar hanya untuk format png");
                    return;
                }
            }else{
                createFailed("Gambar error");
                return;
            }

        //CREATE
        $createProduct = $conn->query("INSERT INTO  products (name, slug, price, weight, stock, material, active, description, category_id, image) VALUES ('$name', '$slug', '$price', $weight, $stock, '$material', $active, '$description', '$category_id', '$image')");

        if($createProduct === TRUE){
            $_SESSION['success'] = [
                "message" => "Produk ". $name . " berhasil dibuat"
            ];
            header("location: index.php?pages=product");
        }else{
            createFailed("Produk tidak berhasil dibuat");
            return;
        }
    }
    public function deleteProduct($conn, $post){
        $name = $post['name'];
        $slug = $post['slug'];

        $deleteImage = unlink("../src/products/".$slug.".png");
        $deleteProduct = $conn->query("DELETE FROM products WHERE slug='$slug'");

        if($deleteImage == $deleteProduct){
            $_SESSION['success'] = [
                "message" => "Produk ". $name . " berhasil dihapus"
            ];
            header("location: index.php?pages=product");
        }
    }

    public function checkSlugCategory($conn, $slug){
        $response = [];
        $checkSlug = $conn->query("SELECT slug FROM category WHERE slug='$slug'");
        if($checkSlug->num_rows > 0){
            $response += [
                "result" => false,
                "message" => "Nama kategori tidak tersedia"
            ];
        }else{
            $response += [
                "result" => true,
                "message" => "Nama kategori tersedia"
            ];
        }
        $response += [
            "status" => 200
        ];
        echo json_encode($response);
        return $response;
    }
    public function editCategory($conn, $slug){
        $getCategory = $conn->query("SELECT * FROM category WHERE slug='$slug'");
        while($category = $getCategory->fetch_object()){
            $result[] = $category;
        }
        return $result;
    }
    public function updateCategory($conn, $post, $files){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $name = test_input($post['name']);
        $slug = test_input($post['slug']);

        //IMAGE
        $image = $files['image']['name'];
        $image_from = $files['image']['tmp_name'];
        $image_error = $files['image']['error'];
        $image_format = pathinfo($image, PATHINFO_EXTENSION);

        $old_slug = test_input($post['old_slug']);

        function editFailed($message){
            $_SESSION['edit_failed'] = [
                "name" => $name,
                "slug" => $slug,
                "old_slug" => $old_slug,
                
                "image" => $image_from,

                "context" => "category",
                "message" => $message
            ];
            header("location: index.php?pages=edit_category&slug=".$old_slug);
            
        }

        // CHECK SLUG
        if($slug != $old_slug){
            $checkSlug = $conn->query("SELECT slug FROM category WHERE slug='$slug'");
            if($checkSlug->num_rows > 0){
                editFailed("Nama kategori tidak tersedia");
                return;
            }
        }

        // IMAGE
        if($image != NULL && $image != $old_image){
            if($image_error == 0){
                    if($image_format == "png"){
                        $image = str_replace("png", "", $image);
                        $image = $slug.".png";

                        $move = move_uploaded_file($image_from, "../src/category/".$image);

                        if (!$move) {
                            editFailed("Gambar tidak berhasil dipindahkan");    
                        }
                    }else{
                        editFailed("Gambar hanya untuk format png");
                        return;
                    }
                }else{
                    editFailed("Gambar error");
                    return;
                }
        }else{
            $image = htmlspecialchars($old_image);
        }

        // UPDATE
        $updateCategory = $conn->query("UPDATE category SET name='$name', slug='$slug', image='$image' WHERE slug='$old_slug'");

        if($updateCategory === TRUE){
            $_SESSION['success'] = [
                "message" => "Kategori ". $name . " berhasil diperbaharui"
            ];
            header("location: index.php?pages=product#category-container");
        }else{
            editFailed("Kategori tidak berhasil diperbaharui");
            return;
        }

    }
    public function createCategory($conn, $post, $files){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $name = test_input($post['name']);
        $slug = test_input($post['slug']);

        //IMAGE
        $image = $files['image']['name'];
        $image_from = $files['image']['tmp_name'];
        $image_error = $files['image']['error'];
        $image_format = pathinfo($image, PATHINFO_EXTENSION);

        function createFailed($message){
            $_SESSION['create_failed'] = [
                "name" => $name,
                "slug" => $slug,
                
                "image" => $image_from,

                "context" => "category",
                "message" => $message
            ];
            header("location: index.php?pages=create_category");
            
        }

        // CHECK SLUG
        $checkSlug = $conn->query("SELECT slug FROM category WHERE slug='$slug'");
        if($checkSlug->num_rows > 0){
            createdFailed("Nama kategori tidak tersedia");
            return;
        }

        // IMAGE
            if($image_error == 0){
                    if($image_format == "png"){
                        $image = str_replace("png", "", $image);
                        $image = $slug.".png";

                        $move = move_uploaded_file($image_from, "../src/category/".$image);

                        if (!$move) {
                            createdFailed("Gambar tidak berhasil dipindahkan");    
                        }
                    }else{
                        createdFailed("Gambar hanya untuk format png");
                        return;
                    }
                }else{
                    createdFailed("Gambar error");
                    return;
                }

        // CREATE
        $createCategory = $conn->query("INSERT INTO category (name, slug, image) VALUES ('$name', '$slug', '$image')");

        if($createCategory === TRUE){
            $_SESSION['success'] = [
                "message" => "Kategori ". $name . " berhasil dibuat"
            ];
            header("location: index.php?pages=product#category-container");
        }else{
            createdFailed("Kategori tidak berhasil dibuat");
            return;
        }

    }


}