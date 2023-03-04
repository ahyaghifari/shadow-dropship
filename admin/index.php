<?php 
    session_start();
    if(!isset($_SESSION['login'])){
        header("location: ../login.php");
        return;
    }
    if (isset($_SESSION['user'])){
        if($_SESSION['user']['role'] == "user"){
            echo "<script>history.back()</script>";
        }
    }
    
    function superadminOnly($location){
        if($_SESSION['user']['role'] != "superadmin"){
            header("location: $location");
        }
    }

    $admin_role = $_SESSION['user']['role'];

    include "../database/config.php";

// PRODUCT
    include "../database/Products.php";
    $products = new Products;

    if(isset($_POST['check_slug_product'])){
        $checkSlug = $products->checkSlugProduct($conn, $_POST['slug']);
        return $checkSlug;
    }
    if(isset($_POST['edit_product'])){
        $products->updateProduct($conn, $_POST, $_FILES);
    }

    if(isset($_POST['create_product'])){
        $products->createProduct($conn, $_POST, $_FILES);
    }

    if(isset($_POST['delete_product'])){
        $products->deleteProduct($conn, $_POST);
    }

    if(isset($_POST['check_slug_category'])){
        $checkSlug = $products->checkSlugCategory($conn, $_POST['slug']);
        return $checkSlug;
    }

    if(isset($_POST['edit_category'])){
        $products->updateCategory($conn, $_POST, $_FILES);
    }

    if(isset($_POST['create_category'])){
        $products->createCategory($conn, $_POST, $_FILES);
    }


// ORDER
    include "../database/Orders.php";
    $orders = new Orders;
    
    if(isset($_POST['approve_order'])){
        $approveOrder = $orders->approveOrder($conn, $_POST['order_code']);
        return $approveOrder;
    }

// USER
    include "../database/Users.php";
    $users = new Users;

    if(isset($_POST['check_username'])){
        $checkUsername = $users->checkUsername($conn, $_POST['username']);
        return $checkUsername;
    }

    if(isset($_POST['edit_user'])){
        $editUser = $users->updateUser($conn, $_POST);
    }

    if(isset($_POST['create_user'])){
        $createUser = $users->createUser($conn, $_POST);
    }
    
    if(isset($_POST['delete_user'])){
        $deleteUser = $users->deleteUser($conn, $_POST['username']);
    }

// CONTACT
    include "../database/Contacts.php";
    $contacts = new Contacts;
    if(isset($_POST['read_contact'])){
        $readContact = $contacts->readContact($conn, $_POST['id'], $_POST['status']);
        return $readContact;
    }

include "components/head.php";
?>
<div id="admin-main" class="border border-neutral-800 w-full min-h-screen lg:w-10/12 p-3 rounded-md">
    <?php 
        if(isset($_GET['pages'])){
            $pages = $_GET['pages'];

            if($pages == "product"){
                echo "<title>Produk Admin | Shadow Dropship</title>";
                include "pages/product.php"; 
                }else if($pages == "edit_product"){
                    superadminOnly("index.php?pages=product");
                    $getProdcuct = $products->editProduct($conn, $_GET['slug']);
                    include "pages/product-forms.php";
                    productEditForm($getProdcuct);
                }else if($pages == "create_product"){
                    superadminOnly("index.php?pages=product");
                    $getCategory = $products->allCategory($conn);
                    include "pages/product-forms.php";
                    productCreateForm($getCategory);
                }else if($pages == "edit_category"){
                    superadminOnly("index.php?pages=product");
                    $getCategory = $products->editCategory($conn, $_GET['slug']);
                    include "pages/product-forms.php";
                    categoryEditForm($getCategory);
                }else if($pages == "create_category"){
                    superadminOnly("index.php?pages=product");
                    include "pages/product-forms.php";
                    categoryCreateForm();
            }

            if($pages == "order"){
                echo "<title>Pesanan Admin | Shadow Dropship</title>";
                include "pages/order.php";
            }else if($pages == "order_detail"){
                echo "<title>Pesanan Admin | Shadow Dropship</title>";
                $getOrderDetail = $orders->orderDetail($conn, $_GET['order_code']);
                include "pages/order-detail.php";
                orderDetailComp($getOrderDetail);
            }

            if($pages == "user"){
                echo "<title>User Admin | Shadow Dropship</title>";
                include "pages/user.php";
                }else if($pages == "edit_user"){
                    if($_SESSION['user']['username'] == $_GET['username']){
                        echo "<script>history.back()</script>";
                    }
                    $getUser = $users->editUser($conn, $_GET['username']);
                    include "pages/user-forms.php";
                    userEditForm($getUser);
                }else if($pages == "create_user"){
                    superadminOnly("index.php?pages=user");
                    include "pages/user-forms.php";
                    userCreateForm();
            }

            if($pages == "contact"){
                echo "<title>Kontak Admin | Shadow Dropship</title>";
                superadminOnly("index.php");
                include "pages/contact.php";
            }
            
        }else{
            echo "<title>Beranda Admin | Shadow Dropship</title>";
            include "pages/home.php";        
        }
    ?>
</div>
<?php include "components/footer.php" ?>
