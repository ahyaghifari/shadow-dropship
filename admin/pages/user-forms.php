<?php 
function userCreateForm(){
    if(isset($_SESSION['create_failed'])){
        if($_SESSION['create_failed']['context'] == "user"){
            $username = $_SESSION['create_failed']['username'];
            $store_name = $_SESSION['create_failed']['store_name'];
            $role = $_SESSION['create_failed']['role'];
            
        }else{
            unset($_SESSION['create_failed']);
            header("Refesh:0");
        }
    }else{
        $username = "";
        $store_name = "";
        $role = "";
    }
    ?>
    
<div id="create-user">
    <div id="create-user-header" class="flex justify-between border-b border-neutral-800 pb-2 border-dashed">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Buat User</h1>
        <button class="text-[14px] md:text-sm lg:text-base" onClick="javascript:history.back()">Kembali</button>
    </div>
    <?php if(isset($_SESSION['create_failed'])){ ?>
        <div id="create-user-failed" class="message bg-red-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
            <p class="w-11/12"><?= $_SESSION['create_failed']['message'] ?></p>
            <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
        </div>
    <?php } ?>
    <div id="create-user-container" class="mt-5">
    <form id="form-create-user" action="<?= htmlspecialchars('index.php')?>" method="post" class="text-[13px] sm:text-[14px] md:text-base">
        <table class="border-separate border-spacing-x-3 border-spacing-y-2 md:border-spacing-y-3 overflow-hidden">
            <tr>
                <td><label for="username">Username</label></td>
                <td>: <input type="text" name="username" id="username" value="<?= $username ?>" class="username-input" required></td>
            </tr>
            <tr>
                <td><label for="store-name">Nama Toko</label></td>
                <td>: <input type="text" name="store_name" id="store-name" value="<?= $store_name ?>"></td>
            </tr>
            <tr>
                <td><label for="role">Role</label></td>
                <td>: <select name="role" id="role" required>
                    <option value="user" <?= ($role == "user") ? "selected" : "" ?>>User</option>
                    <option value="admin" <?= ($role == "admin") ? "selected" : "" ?>>Admin</option>
                    <option value="superadmin" <?= ($role == "superadmin") ? "selected" : "" ?>>Superadmin</option>
                </select></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td>: <input type="password" name="password" id="password" required></td>
            </tr>
        </table>

        <div class="mt-10 px-3">
            <button type="submit" class="btn-user-crud bg-neutral-800 p-1 text-neutral-200 md:text-xl disabled:opacity-50">Buat</button>
        </div>

        <input type="hidden" name="create_user">
    </form>
    </div>
</div>
<?php }

function userEditForm($data){
    $admin_role = $_SESSION['user']['role'];

    if(isset($_SESSION['edit_failed'])){
        if($_SESSION['edit_failed']['context'] == "user"){
            $username = $_SESSION['edit_failed']['username'];
            $store_name = $_SESSION['edit_failed']['store_name'];
            $role = $_SESSION['edit_failed']['role'];
            $old_username = $_SESSION['edit_failed']['old_username'];
        }else{
            unset($_SESSION['edit_failed']);
            header("Refresh:0");
        }
    }else{
        foreach($data as $user){
            $username = $user->username;
            $store_name = $user->store_name;
            $role = $user->role;
            $old_username = $user->username;
        }
    }
    ?>
<div id="edit-user">
    <div id="edit-user-header" class="flex justify-between border-b border-neutral-800 pb-2 border-dashed">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Edit User</h1>
        <button class="text-[14px] md:text-sm lg:text-base" onClick="javascript:history.back()">Kembali</button>
    </div>
    <?php if(isset($_SESSION['edit_failed'])){ ?>
        <div id="edit-user-failed" class="message bg-red-700 p-1 text-[11px] md:text-[12px] text-neutral-200 flex items-center justify-between sm:w-10/12 mx-auto">
            <p class="w-11/12"><?= $_SESSION['edit_failed']['message'] ?></p>
            <button class="message-close-btn"><span class="material-symbols-rounded">close</span></button>
        </div>
    <?php } ?>
    <div id="edit-user-container" class="mt-5">
    <form id="form-edit-user" action="<?= htmlspecialchars('index.php')?>" method="post" class="text-[13px] sm:text-[14px] md:text-base">
        <table class="border-separate border-spacing-x-3 border-spacing-y-2 md:border-spacing-y-3 overflow-hidden">
            <tr>
                <td><label for="username">Username</label></td>
                <td>: <input type="text" name="username" id="username" value="<?= $username ?>" class="username-input" required></td>
            </tr>
            <tr>
                <td><label for="store-name">Nama Toko</label></td>
                <td>: <input type="text" name="store_name" id="store-name" value="<?= $store_name ?>"></td>
            </tr>
            <?php if($admin_role == "superadmin"){ ?>
            <tr>
                <td><label for="role">Role</label></td>
                <td>: <select name="role" id="role" required>
                    <option value="user" <?= ($role == "user") ? "selected" : "" ?>>User</option>
                    <option value="admin" <?= ($role == "admin") ? "selected" : "" ?>>Admin</option>
                    <option value="superadmin" <?= ($role == "superadmin") ? "selected" : "" ?>>Superadmin</option>
                </select></td>
            </tr>
            <?php } ?>
            <tr>
                <td><label for="new-password">New Password</label></td>
                <td>: <input type="password" name="new_password" id="new-password"></td>
            </tr>
        </table>

        <div class="mt-10 px-3">
            <button type="submit" class="btn-user-crud bg-neutral-800 p-1 text-neutral-200 md:text-xl disabled:opacity-50">Update</button>
        </div>
        <input type="hidden" name="edit_user">
        <input type="hidden" id="old-username" name="old_username" value="<?= $old_username ?>">
    </form>

    <?php if($admin_role == "superadmin"){?>
    <form id="form-delete-user" action="<?= htmlspecialchars('index.php')?>" method="post" class="px-3 mt-6">
        <input type="hidden" name="delete_user">
        <input type="hidden" name="username" value=<?= $old_username ?>>
        <button type="submit" id="delete-user-btn" class="border border-neutral-800 p-1 md:text-xl">Delete</button>
    </form>
    <?php } ?>
    </div>
</div>
<?php } 
