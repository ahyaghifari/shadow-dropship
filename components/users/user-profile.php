<?php function userProfilComp($user_profile){?>
    <div id="user-profile" class="mx-auto sm:w-11/12 md:w-11/12">
        <div id="user-profile-header" class="flex items-end w-10/12 md:w-1/2 lg:w-2/6 justify-between">
            <h3 class="text-3xl sm:text-3xl font-semibold">Profil</h3>
            <a href="user.php?ubah-profil=0" class="ml-5"><span><span class="material-symbols-rounded text-xl text-neutral-400">
            edit
        </span></span></a>
        </div>
        <?php if(isset($_SESSION['changeprofile_response'])){?>
        <div id="change-profile-response" class="<?= ($_SESSION['changeprofile_response']['status'] == 400) ? "text-red-600" : "text-green-500" ?>">
            <p><?= $_SESSION['changeprofile_response']['message'] ?></p>
        </div>
        <?php unset($_SESSION['changeprofile_response']); } ?>
        <div id="user-profile-container" class="mt-4 text-sm md:text-base">
            <?php foreach ($user_profile as $pro) {?>
                <table class="border-separate border-x-spacing-3 border-spacing-y-4">
                    <tr>
                        <td class="text-neutral-400">Username</td>
                        <td>: <?= $pro->username ?></td>
                    </tr>
                    <tr>
                        <td class="text-neutral-400">Nama Toko</td>
                        <td>: <?= ($pro->store_name == "") ? "Belum diatur" : $pro->store_name ?></td>
                    </tr>
                    <tr>
                        <td class="text-neutral-400">Sebagai</td>
                        <td class="capitalize">: <?= $pro->role?></td>
                    </tr>
                    <tr>
                        <td class="text-neutral-400">Bergabung pada</td>
                        <td>: <?= $pro->created_at ?> <span class="to-relative-time opacity-50"><?= $pro->created_at ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-neutral-400">Terakhir diubah</td>
                        <td>: <?= $pro->updated_at ?> <span class="to-relative-time opacity-50"><?= $pro->updated_at ?> </span> <span class="opacity-50"> <?= ($pro->edited_by != "") ? "oleh $pro->edited_by" : "" ?></span></td>
                    </tr>
                </table>
            <?php } ?>
        </div>
    </div>
<?php }

function userProfilChangeComp($user_profile){
    foreach($user_profile as $pro){
        $username = $pro->username;
        $store_name = $pro->store_name;
    }
    ?>
    <div id="user-profile-change" class="mx-auto sm:w-11/12 md:w-11/12">
        <div id="user-profile-change-header" class="flex items-end w-10/12 md:w-1/2 lg:w-2/6 justify-between">
            <h3 class="text-3xl sm:text-3xl font-semibold">Ubah Profil</h3>
            <a href="user.php?profil=0" class="ml-5"><span><span class="material-symbols-rounded text-xl text-neutral-400">
            arrow_back_ios
        </span></span></a>
        </div>
        
        <?php if(isset($_SESSION['changeprofile_response'])){?>
        <div id="change-profile-response" class="<?= ($_SESSION['changeprofile_response']['status'] == 400) ? "text-red-600" : "text-green-500" ?>">
            <p><?= $_SESSION['changeprofile_response']['message'] ?></p>
        </div>
        <?php unset($_SESSION['changeprofile_response']); } ?>

        <div id="user-profile-container" class="mt-4 text-sm md:text-base">
            <form id="form-changeprofile" action="user.php" method="post">
                <input type="hidden" name="change_profile">
                    <table class="border-separate border-x-spacing-3 border-spacing-y-4">
                    <tr>
                        <td>Username</td>
                        <td>: <input type="text" name="username" value="<?= $username ?>" required></td>
                    </tr>
                    <tr>
                        <td>Nama Toko</td>
                        <td>: <input type="text" name="store_name" value="<?= $store_name ?>"></td>
                    </tr>
                </table>
                <button type="submit" class="mt-5 bg-neutral-200 text-neutral-800 font-black p-1 text-lg">Ubah</button>
            </form>
        </div>
    </div>
<?php } ?>