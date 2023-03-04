<?php 

function changePasswordComp(){?>
<div id="change-password" class="mx-auto sm:w-11/12 md:w-10/12">
    <div id="change-password-header">
        <h1 class="text-xl font-semibold sm:text-2xl">Ubah Password</h1>
    </div>
    <?php if(isset($_SESSION['changepassword_response'])){?>
    <div id="change-password-response" class="<?= ($_SESSION['changepassword_response']['status'] == 400) ? "text-red-600" : "text-green-500" ?>">
        <p><?= $_SESSION['changepassword_response']['message'] ?></p>
    </div>
    <?php unset($_SESSION['changepassword_response']); } ?>
    <div id="change-password-container" class="mt-8 w-11/12 mx-auto">
        <form id="form-changepassword" action="user.php" method="post" class="sm:text-lg md:text-xl">
            <input type="hidden" name="change_password">
            <div>
                <label for="old-password">Old password : </label>
                <input type="password" name="old_password" id="old-password" required>
            </div>
            <div>
                <label for="new-password">New password : </label>
                <input type="password" name="new_password" id="new-password" required>
            </div>
            <div>
                <label for="confirm-password">Confirm new password : </label>
                <input type="password" name="confirm_password" id="confirm-new-password" required>
            </div>
            <button type="submit" class="mt-5 bg-neutral-200 text-neutral-800 font-black p-1 text-lg">Ubah</button>
        </form>
    </div>
</div>
<?php }