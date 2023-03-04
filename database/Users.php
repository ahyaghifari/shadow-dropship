<?php
class Users{
    public function login($conn, $username, $password, $remember_me){
        session_start();
        if(isset($_COOKIE['remember_me'])){
            if(!isset($_SESSION['login'])){
            $check_username = $conn->query("SELECT username FROM users");
            while($user = $check_username->fetch_object()) {
                if(md5($user->username) == $_COOKIE['remember_me']){
                    $getuser = $conn->query("SELECT * FROM users WHERE username='$user->username'");
                    if($getuser->num_rows > 0){
                        $thisuser = $getuser->fetch_object();
                        $_SESSION['login'] = true;
                        $_SESSION['user'] = [
                            'username' => $thisuser->username,
                            'role' => $thisuser->role,
                            'store_name' => $thisuser->store_name
                        ];
                    }else{
                        setcooke('remember_me', "", time()- 3600, '/');
                    }
                    break;
                    header("location: index.php");
                    return;
                }
            }
            header("location: index.php");
            return;
            }
        }
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $username = test_input($username);
        $password = md5(test_input($password));

        $getuser = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
        if($getuser->num_rows > 0){
            $user = $getuser->fetch_object();
            $_SESSION['login'] = true;
            $_SESSION['user'] = [
                'username' => $user->username,
                'role' => $user->role,
                'store_name' => $user->store_name
            ];
            if($remember_me != ""){
                setcookie('remember_me', md5($username), time() + 24 * 60 * 60, '/');
            }
            header("location: index.php");        
        }else{
            $_SESSION['login_failed'] = [
                'username' => $username,
                'message' => "Your data not match in our credentials"
            ];
            header("location: login.php");
        }

    }
    public function logout(){
        session_start();
        unset($_SESSION['login']);
        unset($_SESSION['user']);
        if(isset($_COOKIE["remember_me"])){
            setcookie("remember_me", "", time() - 3600, '/');
        }
        header("location: ../index.php");
    }
    public function register($conn, $username, $store_name, $password, $confirm_password, $remember_me){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        session_start();

        $username = test_input($username);
        $store_name = test_input($store_name);
        $password = test_input($password);
        $confirm_password = test_input($confirm_password);
        
        $check_username = $conn->query("SELECT * FROM users WHERE 
        username='$username'");
        if($check_username->num_rows == 0 ){
            if($password == $confirm_password){

                $password = md5($password);
                $newuser = $conn->query("INSERT INTO users (username, store_name, password, role) VALUES ('$username', '$store_name', '$password', 'user')");
                if($newuser === TRUE){
                    $_SESSION['login'] = true;
                    $_SESSION['user'] = [
                        'username' => $username,
                        'role' => 'user',
                        'store_name' => $store_name
                    ];
                    if($remember_me != ""){
                        setcookie("remember_me", md5($username), time() + 24 * 60 * 60, "/");
                    }
                    header("location: index.php"); 
                }else{
                    $_SESSION['register_failed'] = [
                        'username' => $username,
                        'store_name'  => $store_name,
                        'message' => "Daftar gagal coba lagi nanti"
                    ];
                    header("location: register.php");
                }
            }else{
                $_SESSION['register_failed'] = [
                    'username' => $username,
                    'store_name'  => $store_name,
                    'message' => "Password tidak sama"
                ];
                header("location: register.php");
            }
        }else{
            $_SESSION['register_failed'] = [
                'username' => "",
                'store_name'  => $store_name,
                'message' => "Username sudah digunakan"
            ];
            header("location: register.php");
        }
    }
    public function changePassword($conn, $oldpass, $newpass, $confirmnewpass){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        session_start();

        $oldpassword = test_input($oldpass);
        $newpassword = test_input($newpass);
        $confirmpassword = test_input($confirmnewpass);
        
        if($newpassword == $confirmpassword){
            $username = $_SESSION['user']['username'];
            $getuserpassword = $conn->query("SELECT password FROM users WHERE username='$username'");
            $getpassword = $getuserpassword->fetch_object();
            $thepassword = $getpassword->password;
            
            if(md5($oldpassword) == $thepassword){

                $newpassword = md5($newpassword);

                $updatepassword = $conn->query("UPDATE users SET password='$newpassword' WHERE username='$username'");
                if($updatepassword === TRUE){
                    $_SESSION['changepassword_response'] = [
                        "status" => 200,
                        "message" => "Password sudah berhasil diubah"
                    ];
                }
            }else{
                $_SESSION['changepassword_response'] = [
                    "status" => 400,
                    "message" => "Password salah"
                ];
            }
        }else{
            $_SESSION['changepassword_response'] = [
                "status" => 400,
                "message" => "Password baru tidak sama"
            ];
        }
        header("location: user.php?ubah-password=0");
    }

    public function profil($conn, $username){
        $getProfil = $conn->query("SELECT * FROM users WHERE username='$username'");
        while($profil = $getProfil->fetch_object()){
            $result[] = $profil;
        }
        return $result;
    }
    public function changeProfile($conn, $username, $store_name){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        session_start();
        $username = test_input($username);
        $store_name = test_input($store_name);
        $_SESSION['changeprofile_response']= [];

        if($username == $_SESSION['user']['username']){
            $change_storename = $conn->query("UPDATE users SET store_name='$store_name', edited_by='user' WHERE username='$username'");
            if($change_storename === TRUE){
                $_SESSION['changeprofile_response'] += [
                    "status" => 200.,
                    "message" => "Profil berhasil diubah"
                ];
                header("location: user.php?profil=0");
            }else{
                $_SESSION['changeprofile_response'] += [
                    "status" => 400,
                    "message" => "Profil tidak berhasil diubah"
                ];
                header("location: user.php?ubah-profil=0");
            }
        }else{
            $check_username = $conn->query("SELECT username FROM users WHERE username='$username'");
            if($check_username->num_rows == 0){
                $currentUsername = $_SESSION['user']['username'];
                $updateProfil = $conn->query("UPDATE users SET username='$username', store_name='$store_name', edited_by='user' WHERE username='$currentUsername'");
                if($updateProfil === TRUE){
                    $_SESSION['user']['username'] = $username;
                    $_SESSION['changeprofile_response'] += [
                    "status" => 200.,
                    "message" => "Profil berhasil diubah"
                    ];
                    header("location: user.php?profil=0");
                }else{
                    $_SESSION['changeprofile_response'] += [
                        "status" => 400,
                        "message" => "Profil tidak berhasil diubah"
                     ];
                    header("location: user.php?ubah-profil=0");
                }
            }else{
                $_SESSION['changeprofile_response'] += [
                    "status" => 400,
                    "message" => "Username sudah digunakan"
                ];
                header("location: user.php?ubah-profil=0");
            }
        }
    }

    public function allUsers($conn, $username, $store_name, $role, $sort_by){
        $filter = "WHERE ";

        if($username != ""){
            $filter .= "users.username LIKE '%$username%'";
        }

        if($store_name != ""){
            if(strlen($filter) > 6){
                $filter .= " AND ";
            }
            $filter .= "users.store_name LIKE '%$store_name%' ";
        }

        if($role != ""){
            if(strlen($filter) > 6){
                $filter .= " AND ";
            }
            $filter .= "users.role='$role'";
        }

        $order_by = "users.created_at DESC";
        if($sort_by != ""){
            if($sort_by == "oldest"){
                $order_by = "users.created_at";
            }
        }

        if($username == "" && $store_name == "" && $role == ""){
            $filter = "";
        }


        $getAllUsers = $conn->query("SELECT users.*, order_count.* FROM users LEFT JOIN (SELECT user_id, COUNT(user_id) as user_order_count FROM orders_orders) order_count ON users.id=order_count.user_id $filter ORDER BY $order_by");
        if($getAllUsers->num_rows > 0){
            while($users = $getAllUsers->fetch_object()){
                $result[] = $users;
            }
        }else{
            $result = 0;
        }

        return $result;
    }
    public function editUser($conn, $username){
        $getUser = $conn->query("SELECT * FROM users WHERE username='$username'");
        while($user = $getUser->fetch_object()){
            $result[] = $user;
        }
        return $result;
    }
    public function updateUser($conn, $post){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = test_input($post['username']);
        $store_name = test_input($post['store_name']);
        $role = "";
        $new_password = "";
        $old_username = test_input($post['old_username']);

        function editFailed($message){
            $_SESSION['edit_failed']= [
                "username" => $username,
                "store_name" => $store_name,
                "role" => $role,
                "old_username" => $old_username,
                "context" => "user",
                "message" => $message
            ];
            header("location: index.php?pages=edit_user&username=".$old_username);
        }

        // CHECK USERNAME
        if($username != $old_username){
            $checkUsername = $conn->query("SELECT username FROM users WHERE username='$username'");
            if($checkUsername->num_rows > 0 ){
                editFailed("Username tidak tersedia");
            }
        }

        // IF NEW PASSWORD FILLED
        if($post['new_password'] != NULL){
            $new_password = test_input($post['new_password']);
            $new_password = md5($new_password);
            $new_password = ", password='$new_password'";
        }

        //IF SUPERUSER EDITED
        if($_SESSION['user']['role'] == "superadmin"){
            $role = $post['role'];
            $role = ", role='$role'";
        }

        $edited_by = $_SESSION['user']['role'];

        // UPDATE
        $updateUser = $conn->query("UPDATE users SET username='$username', store_name='$store_name' $role $new_password WHERE username='$old_username'");

        if($updateUser === TRUE){
            $_SESSION['success'] = [
                "message" => "User ". $old_username . " berhasil diperbaharui"
            ];
            header("location: index.php?pages=user");
        }else{
            editFailed("User tidak berhasil diperbaharui");
        }

    }
    public function createUser($conn, $post){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = test_input($post['username']);
        $store_name = test_input($post['store_name']);
        $role = $post['role'];
        $password = test_input($post['password']);

        function createFailed($message){
            $_SESSION['create_failed']= [
                "username" => $username,
                "store_name" => $store_name,
                "role" => $role,
                "context" => "user",
                "message" => $message
            ];
            header("location: index.php?pages=create_user");
        }

        // CHECK USERNAME
        $checkUsername = $conn->query("SELECT username FROM users WHERE username='$username'");
        if($checkUsername->num_rows > 0 ){
            editFailed("Username tidak tersedia");
        }

        // PASSWORD
        $password = md5($password);

        // UPDATE
        $createUser = $conn->query("INSERT INTO users (username, store_name, role, password) VALUES ('$username', '$store_name', '$role', '$password')");

        if($createUser === TRUE){
            $_SESSION['success'] = [
                "message" => "User ". $username . " berhasil dibuat"
            ];
            header("location: index.php?pages=user");
        }else{
            editFailed("User tidak dibuat");
        }

    }
    public function deleteUser($conn, $username){
        $deleteUser = $conn->query("DELETE FROM users WHERE username='$username'");
        if($deleteUser){
            $_SESSION['success'] = [
                "message" => "User ". $username ." berhasil dihapus"
            ];
            header("location: index.php?pages=user");
        }
    }

    public function checkUsername($conn, $username){
        $checkUsername = $conn->query("SELECT username FROM users WHERE username='$username'");
        if($checkUsername->num_rows > 0 ){
            $response = [
                "result" => false,
                "message" => "Username tidak tersedia"
            ];
        }else{
            $response = [
                "result" => true,
                "message" => "Username bisa digunakan"
            ];
        }
        echo json_encode($response);
        return $response;
    }
}

if(isset($_POST['logout'])){
    $user = new Users;
    $user->logout();
}