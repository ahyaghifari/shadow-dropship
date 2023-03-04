<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FONT RUBIK -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;600;900&display=swap" rel="stylesheet">
    <!-- ICONS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
        theme: {
            extend: {
                fontFamily:{
                    rubik: ['Rubik', 'sans-serif']
                }
            }
        }
        }
    </script>

    <!-- OWL CAROUSEL -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css"
        integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="assets/style.css">

</head>
<body class="bg-neutral-200 font-rubik text-neutral-800 overflow-x-hidden">
<header class="bg-neutral-800 p-2 text-neutral-200 text-sm flex items-center justify-between">
    <button class="admin-sidebar-btn md:hidden">
        <div id="header-left">
            <span class="material-symbols-rounded ">
                menu
            </span>
        </button>
        <h1 class="ml-2 md:text-xl">Halo, <span class="capitalize"><?= $_SESSION['user']['role'] ?></span> : <?= $_SESSION['user']['username'] ?></h1>
    </div>
    <div id="header-right">
        <a href="../index.php"><span class="material-symbols-rounded md:text-3xl">
            home
        </span></a>
    </div>
</header>
<main class="flex p-2 gap-x-3 md:gap-x-10 mt-2 md:mt-0">

<div id="admin-sidebar" class="fixed md:static top-0 h-screen left-0 w-4/12 bg-neutral-200 border border-neutral-800 rounded-md text-xl lg:w-2/12 flex flex-col">
    <button class="admin-sidebar-btn absolute top-2 right-2 text-neutral-500 md:hidden">
        <span class="material-symbols-rounded ">
            close
        </span>
    </button>
    <?php 
        $nav_pages = "";
        if(isset($_GET['pages'])){
            $nav_pages = $_GET['pages'];
        }
    ?>
    <a href="index.php" class="pt-12 pb-3 px-3 w-full border-b border-neutral-800 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out <?= ($nav_pages == "") ? "bg-neutral-800 text-neutral-200" : "" ?>">Beranda</a>
    <a href="index.php?pages=product" class="pt-12 pb-3 px-3 w-full border-b border-neutral-800 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out <?= ($nav_pages == "product" || $nav_pages == "edit_product" || $nav_pages == "create_product") ? "bg-neutral-800 text-neutral-200" : "" ?>">Produk</a>
    <a href="index.php?pages=order" class="pt-12 pb-3 px-3 w-full border-b border-neutral-800 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out <?= ($nav_pages == "order" || $nav_pages == "order_detail") ? "bg-neutral-800 text-neutral-200" : "" ?>">Pesanan</a>
    <a href="index.php?pages=user" class="pt-12 pb-3 px-3 w-full border-b border-neutral-800 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out <?= ($nav_pages == "user" || $nav_pages == "edit_user" || $nav_pages == "create_user") ? "bg-neutral-800 text-neutral-200" : "" ?>">User</a>
    <?php if($_SESSION['user']['role'] == "superadmin"){?>
    <a href="index.php?pages=contact" class="pt-12 pb-3 px-3 w-full border-b border-neutral-800 hover:bg-neutral-800 hover:text-neutral-200 transition duration-300 ease-in-out <?= ($nav_pages == "contact" ) ? "bg-neutral-800 text-neutral-200" : "" ?>">Kontak</a>
    <?php } ?>
</div>