<?php 
$filter_show = "";
$username_filter = "";
$store_name_filter = "";
$role_filter = "";
$sort_by_filter = "";

if(isset($_GET['username']) && isset($_GET['store_name']) && isset($_GET['role']) && isset($_GET['sort_by'])){
    $allUsers = $users->allUsers($conn, $_GET['username'], $_GET['store_name'], $_GET['role'], $_GET['sort_by']);
    $filter_show = "show";
    $username_filter = $_GET['username'];
    $store_name_filter = $_GET['store_name'];
    $role_filter = $_GET['role'];
    $sort_by_filter = $_GET['sort_by'];
    
}else{
    $allUsers = $users->allUsers($conn, "", "", "", "");
}
?>
<div id="user">
    <div id="user-header" class="border-b border-neutral-800 pb-2 border-dashed flex justify-between items-stretch">
        <h1 class="text-xl sm:text-2xl md:text-3xl">User</h1>
        <?php if($admin_role == "superadmin"){?>
        <div id="product-header-right" class="text-[13px] flex">
            <a href="index.php?pages=create_user" class="bg-neutral-800 text-neutral-200 p-1 md:p-2 rounded-sm h-full">Buat User</a>
        </div>
        <?php } ?>
    </div>
    <?php if(isset($_SESSION['success'])){?>
    <div id="user-success" class="message bg-green-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
        <p class="w-11/12"><?= $_SESSION['success']['message'] ?></p>
        <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
    </div>
    <?php  unset($_SESSION['success']); } ?>
    <div id="user-filter">
        <button class="user-filter-btn float-right"><span class="material-symbols-rounded text-2xl md:text-3xl text-neutral-700">filter_alt</span></button>
        <form action="index.php" method="get" class="form-user-filter <?= $filter_show ?> clear-both text-[12px] grid grid-cols-2  gap-x-2 gap-y-2 sm:text-[13px] md:text-sm lg:text-base md:grid-cols-3 lg:grid-cols-5 md:items-start md:gap-x-5 md:gap-y-0">
            <input type="hidden" name="pages" value="user">
            <div class="flex flex-col">
                <label for="username">Username</label>
                <input type="text" name="username" value="<?= $username_filter ?>">
            </div>
            <div class="flex flex-col">
                <label for="store_name">Nama Toko</label>
                <input type="text" name="store_name" value="<?= $store_name_filter ?>">
            </div>
            <div class="flex flex-col">
                <label for="role">Role</label>
                <select name="role" id="role">
                    <option value="">-----</option>
                    <option value="user" <?= ($role_filter == "user") ? "selected" : "" ?>>User</option>
                    <option value="admin" <?= ($role_filter == "admin") ? "selected" : "" ?>>Admin</option>
                    <option value="superadmin" <?= ($role_filter == "superadmin") ? "selected" : "" ?>>Superadmin</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="sort-by">Urutkan</label>
                <select name="sort_by" id="sort-by">
                    <option value="">-----</option>
                    <option value="newest" <?= ($sort_by_filter == "newest") ? "selected" : "" ?>>Paling Baru</option>
                    <option value="oldest" <?= ($sort_by_filter == "oldest") ? "selected" : "" ?>>Paling Lama</option>
                </select>
            </div>
            <div class="col-start-1 md:col-start-auto h-full flex items-center">
                <button type="submit" class="bg-neutral-700 p-1 px-3 text-neutral-200">Cari</button>
                <a href="index.php?pages=user" class="bg-neutral-300 p-1 px-3 text-neutral-700 ml-3">Reset </a>
            </div>
        </form>
    </div>
    <div id="user-container" class="clear-both mt-5 h-screen overflow-y-scroll pr-2 border-y border-neutral-400 py-2">
        <?php if($allUsers > 0){ foreach($allUsers as $user){?>
        <div class="users border border-neutral-700 rounded-md mb-3 shadow-sm sm:w-11/12 mx-auto md:w-9/12 md:mb-5">
            <div class="users-username-store-role p-2 grid grid-cols-3 text-[11px] text-center sm:text-[12px] md:text-[13px]">
                <h3 class="text-[13px] md:text-sm"><?= $user->username ?></h3>
                <h3><?= ($user->store_name != "") ? $user->store_name : "Belum diatur" ?></h3>
                <h3><?= $user->role ?></h3>
            </div>
            <div id="users-more-<?= $user->username ?>" class="users-more text-[10px] p-2 border-t sm:text-[11px] md:text-[12px] border-neutral-300 text-neutral-500">
                <table>
                    <?php if($user->role == "user"){?>
                    <tr>
                        <td>Jumlah Pesanan</td>
                        <td>: <span class="text-neutral-700"><?= ($user->user_order_count != NULL) ? $user->user_order_count : 0 ?></span></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Bergabung pada</td>
                        <td>: <span class="text-neutral-700 to-calendar"><?= $user->created_at ?></span></td>
                    </tr>
                    <tr>
                        <td>Terakhir diubah</td>
                        <td>: <span class="text-neutral-700 to-calendar"><?= $user->updated_at ?></span> <?= ($user->edited_by != NULL) ? "(oleh $user->edited_by)" : "" ?></td>
                    </tr>
                </table>
            </div>
            <div class="users-actions grid grid-cols-3 bg-neutral-300 px-3">
                <button data-username="<?= $user->username ?>" class="users_expand-btn col-start-2 text-neutral-700"><span class="material-symbols-rounded text-2xl text-neutral-500">expand_more</span></button>
                <?php if(($_SESSION['user']['username'] != $user->username) && ($admin_role == "superadmin" || ($admin_role =="admin" && $user->role != "superadmin"))){?>
                <a href="index.php?pages=edit_user&username=<?= $user->username ?>" class="text-right place-self-end"><span class="material-symbols-rounded text-base md:text-xl">edit</span></a>
                <?php } ?>
            </div>
        </div>
        <?php } }else{?>
                <p class="text-sm py-3 text-center">User tidak ditemukan</p>  
        <?php } ?>
    </div>
</div>