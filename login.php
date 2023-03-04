<?php 
    include "components/base.php";
    include "database/config.php";
    include "database/Users.php";
    
    $username = "";
    $error_message = "";
    if(isset($_SESSION['login_failed'])){
        $username = $_SESSION['login_failed']['username'];
        $error_message = $_SESSION['login_failed']['message'];
        unset($_SESSION['login_failed']);
    }
    
    $users = new Users;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['remember_me'])){
            $users->login($conn, $_POST['username'], $_POST['password'], $_POST['remember_me']);
        }else{
            $users->login($conn, $_POST['username'], $_POST['password'], "");
        }
    }
    
?>
<title>Masuk | Shadow Dropship</title>

<div id="login" class="h-screen flex justify-center items-center">
    <div id="login-container" class="p-3 shadow-lg w-10/12 mx-auto border border-neutral-700 sm:w-8/12 md:w-1/2 py-10">
        <h3 class="font-black text-center text-xl mb-5 sm:text-2xl md:text-3xl">Masuk</h3>
        <?php if($error_message != ""){?>
        <p class="text-[11px] sm:text-[13px] md:text-sm bg-red-600 text-neutral-100 p-1 mx-auto text-center mb-2"><?= $error_message ?></p>
        <?php } ?>
        <form id="form-login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="w-10/12 md:w-7/12 mx-auto">
            <div class="form-login-components">
                <label for="username">Username : </label>
                <input type="text" name="username" id="username" placeholder="Username" value="<?= $username ?>" required>
            </div>
            <div class="form-login-components">
                <label for="password">Password : </label>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="text-[12px] flex items-center">
                <label for="remember-me" class="mr-2">Remember Me :</label>
                <input type="checkbox" name="remember_me" id="remember-me">
            </div>
            <button type="submit" class="p-1 text-center font-semibold bg-neutral-300 text-neutral-800 w-full mt-5">Masuk</button>
        </form>
        <div class="mt-5 text-[12px] text-right">
            <p class="text-neutral-400">Belum punya akun ? <a href="register.php" class="text-neutral-100 underline">Daftar</a></p>
        </div>
    </div>
</div>
<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/auth.js"></script>
<?php include "components/foot.php"?>