<title>Daftar | Shadow Dropship</title>
<?php 
    include "components/base.php";
    include "database/config.php";
    include "database/Users.php";

    $username = "";
    $store_name = "";
    $error_message = "";
    if(isset($_SESSION['register_failed'])){
        $username = $_SESSION['register_failed']['username'];
        $store_name = $_SESSION['register_failed']['store_name'];
        $error_message = $_SESSION['register_failed']['message'];
        unset($_SESSION['register_failed']);
    }

    $users = new Users;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
        if(isset($_POST['remember_me'])){
            $users->register($conn, $_POST['username'], $_POST['store_name'], $_POST['password'], $_POST['confirm_password'], $_POST['remember_me']);
        }else{
            $users->register($conn, $_POST['username'], $_POST['store_name'], $_POST['password'], $_POST['confirm_password'], "");
        }
    }
?>
<div id="register" class="min-h-screen pt-16 flex justify-center items-center">
        <div id="register-container" class="p-3 shadow-lg w-10/12 mx-auto border border-neutral-700 sm:w-8/12 md:w-1/2 py-9">
            <h3 class="font-black text-center text-xl mb-5 sm:text-2xl md:text-3xl">Daftar</h3>
            <?php if($error_message != ""){ ?>
            <p class="text-[11px] sm:text-[13px] md:text-sm bg-red-600 text-neutral-100 p-1 mx-auto text-center mb-2"><?= $error_message; ?></p>
            <?php } ?>
            <form id="form-register" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="w-10/12 md:w-8/12 mx-auto">
                <div class="form-register-components">
                    <label for="username">Username : <span class="text-red-500">*</span> </label>
                    <input type="text" name="username" id="username" placeholder="Username" value="<?= $username ?>" required>
                </div>
                <div class="form-register-components">
                    <label for="store-name">Nama Toko : </label>
                    <input type="text" name="store_name" id="store-name" value="<?= $store_name ?>" placeholder="Nama Toko">
                </div>
                <div class="form-register-components">
                    <label for="password">Password : <span class="text-red-500">*</span> </label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <div class="form-register-components">
                    <label for="confirm-password">Konfirmasi Password : <span class="text-red-500">*</span> </label>
                    <input type="password" name="confirm_password" id="confirm-password" placeholder="Konfirmasi Password" required>
                </div>
                <div class="text-[12px] flex items-center">
                    <label for="remember-me" class="mr-2">Remember Me :</label>
                    <input type="checkbox" name="remember_me" id="remember-me">
                </div>
                <button type="submit" class="p-1 text-center font-semibold bg-neutral-300 text-neutral-800 w-full mt-5">Daftar</button>
            </form>
            <div class="mt-5 text-[12px] text-right">
                <p class="text-neutral-400">Sudah punya akun? <a href="login.php" class="text-neutral-100 underline">Masuk</a></p>
            </div>
            <div class="text-[10px] md:text-[11px] mt-5 text-neutral-400">
                <ul>
                    <li>Karena ini merupakan aplikasi demo, kami tidak ingin mendapatkan data pribadi apapun dari user</li>
                    <li>Harap diingat karena fitur autentikasi tidak lengkap, maka user perlu mengingat passwordnya dengan benar</li>
                    <li>Jika user lupa password, user dapat chat admin atau kontak superadmin di <a href="contact.php" class="underline text-neutral-300">halaman ini</a></li>
                </ul>
            </div>
        </div>
    </div>
<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/auth.js"></script>
<?php include "components/foot.php"?>