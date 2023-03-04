<?php
include "database/config.php";
include "database/Orders.php";
include "database/Users.php";
$orders = new Orders;
$users = new Users;

if(isset($_POST['confirm_order'])){
    $orders->confirmOrder($conn, $_POST['order_code']);
}

if(isset($_POST['cancel_order'])){
    $orders->cancelOrder($conn, $_POST['order_code']);
}

if(isset($_POST['change_profile'])){
    $users->changeProfile($conn, $_POST['username'], $_POST['store_name']);
}

if(isset($_POST['change_password'])){
    $users->changePassword($conn, $_POST['old_password'], $_POST['new_password'], $_POST['confirm_password']);
}

include "components/base.php"; 
if(!isset($_SESSION['login'])){
    header("location: login.php");
}

?>

<div id="user" class="p-3 mt-16">
    <div id="user-header" class="bg-neutral-900 p-1 flex justify-between items-center sm:w-11/12 mx-auto">
        <h1 class="text-sm sm:text-base md:text-xl lg:text-2xl">Hai, <span class="font-semibold"><?= $_SESSION['user']['username'] ?></span></h1>
        <div class="nav-user-toggle cursor-pointer md:hidden">
            <span class="material-symbols-rounded text-xl text-neutral-500">
                menu
            </span>
        </div>
        <div id="nav-user-list" class="fixed top-0 right-0 bg-neutral-800 h-screen w-[40%] md:static md:h-fit">
            <div id="nav-user-list-close" class="text-right md:hidden">
                <button class="nav-user-toggle">
                    <span class="material-symbols-rounded text-xl text-neutral-500 p-2 text-right">
                        close
                    </span>    
                </button>
            </div>
            <div class="flex flex-col h-[90%] md:flex-col">
                <a href="user.php?orders=semua" class="pt-10 pb-2 px-3 hover:bg-neutral-900 border-b border-neutral-700 md:py-1 px-2">Pesanan</a>
                <a href="user.php?profil=0" class="pt-10 pb-2 px-3 hover:bg-neutral-900 border-b border-neutral-700 md:py-1 px-2">Profil</a>
                <a href="user.php?ubah-password=0" class="pt-10 pb-2 px-3 hover:bg-neutral-900 border-b border-neutral-700 md:py-1 px-2">Ubah Password</a>
            </div>
        </div>
    </div>
    <div id="user-container" class="mt-8 min-h-screen">
<?php 

    if(isset($_GET['orders'])){
        if($_GET['orders'] == "semua" || ""){
            $user_orders = $orders->userOrders($conn, $_SESSION['user']['username'], "");
        }else{
            $user_orders = $orders->userOrders($conn, $_SESSION['user']['username'], $_GET['orders']);
        }
        include "components/users/user-orders.php";
            userOrdersComp($user_orders);
        
    }else if(isset($_GET['order-detail'])){
        $order_detail = $orders->orderDetail($conn, $_GET['order-detail']);
        include "components/users/user-orders.php";
        userOrderDetailComp($order_detail);

    }else if(isset($_GET['profil'])){
        $user_profil = $users->profil($conn, $_SESSION['user']['username']);
        include "components/users/user-profile.php";
        userProfilComp($user_profil);
    
    }else if(isset($_GET['ubah-profil'])){
        $user_profil = $users->profil($conn, $_SESSION['user']['username']);
        include "components/users/user-profile.php";
        userProfilChangeComp($user_profil);
        

    }else if(isset($_GET['ubah-password'])){
        include "components/users/user-changepassword.php";
        changePasswordComp();
    
    }else{
        $user_orders = $orders->userOrders($conn, $_SESSION['user']['username'], "");
        include "components/users/user-orders.php";
        userOrdersComp($user_orders);
    }

?>
    </div>
</div>

<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/user.js"></script>
<?php include "components/foot.php"?>
