<?php
include "functions.php";
date_default_timezone_set('Asia/Singapore');

class Chats{
    public function new($conn, $chat_id, $message, $username, $role, $responder){
        function getRandomString($code, $n){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';

            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }

            return "$code-$randomString";
        }
        $user_id = 0;
        
        if($username == ""){
            $username = "anonymous";
            $role = "anonymous";
        }else{
            $getuser = $conn->query("SELECT id, role FROM users WHERE username='$username'");
            $user = $getuser->fetch_object();
            $user_id = $user->id;
            $role = $user->role;
        }

        $response = [];

        $check_chat = $conn->query("SELECT id FROM chats_chats WHERE chat_ID='$chat_id'");

        if($check_chat->num_rows == 0 && $responder != ""){
            if(isset($_COOKIE['chat_ID'])){
                setcookie('chat_ID', '', time() - 3600);
            }
            $response = [
                "status" => 400,
                "chat_id_not_found" => true,
                "message" => "Ruang chat tidak ditemukan"
            ];
            echo json_encode($response);
            return $response;
            die;
        }
        
        if($check_chat->num_rows == 0){
            $newChatID = getRandomString("SHDW-CHT", 10);
            if($username != "anonymous"){
                $newchat = $conn->query("INSERT INTO chats_chats (chat_ID, user_id) VALUES ('$newChatID', '$user_id')");
            }else{
                $newchat = $conn->query("INSERT INTO chats_chats (chat_ID) VALUES ('$newChatID')");
            }
            $id = $conn->insert_id;
            $response += ["chat_id" => $newChatID];
            if($username == "anonymous" && !isset($_COOKIE['chat_ID'])){
                setcookie("chat_ID", $newChatID, time()+(60 * 24 * 60 * 60));
            }
        }else{
            $chat = $check_chat->fetch_object();
            $id = $chat->id;
        }

        if($role == "admin"){
            $getIdAdmin = $conn->query("UPDATE chats_chats SET admin_id='$user_id' WHERE id='$id'");
        }

        $newmessage = $conn->query("INSERT INTO chats_messages (chat_id, message, sender, readed) VALUES ('$id', '$message', '$role', 1)");

        if($newmessage === TRUE){
            $response += [
                "status" => 200,
            ];
        }else{
            $response = [
                "status" => 400,
            ];
        }

        $response += [
            "now" => date('Y-m-d H:i:s')
        ];

        echo json_encode($response);
        return $response;
    }

    public function readAllChats($conn, $chat_id, $role, $responder){
        if($role == "user" || $role == "anonymous"){
            $sender = "admin";
        }else{
            if($responder != ""){
                $sender = "user";
            }else{
                $sender = "anonymous";
            }
        }
        $getChatId  = $conn->query("SELECT id FROM chats_chats WHERE chat_ID='$chat_id'");
        $getId = $getChatId->fetch_object();
        $id = $getId->id;

        $readAllChats = $conn->query("UPDATE chats_messages SET readed=0 WHERE chat_id='$id' AND sender='$sender'");
        
        $response = [
            "status" => 200,
            "message" => "All chat readed"
        ];

        echo json_encode($response);
        return $response;
    }

    public function getUnreadChats($conn, $chat_id, $role, $responder){

        $checkId = $conn->query("SELECT chat_ID from chats_chats WHERE chat_ID='$chat_id'");
        if($checkId->num_rows == 0){
            $response = [
                "status" => 400,
                "chat_not_found" => true,
                "message" => "Ruang chat tidak ditemukan"
            ];
            echo json_encode($response);
            return $response;
            die;
        }
        
        $response = [
            "chat_ID" => $chat_id
        ];

        if($role == "user" || $role == "anonymous"){
            $sender = "admin";
        }else{
            if($responder != ""){
                $sender = "user";
            }else{
                $sender = "anonymous";
            }
        }

        $response += [
            "sender" => $sender
        ];

        $getUnreadChats = $conn->query("SELECT chats_chats.id as chat_id, chats_chats.chat_ID as ID_chat, chats_messages.*, chats_messages.id as messages_id FROM chats_chats LEFT JOIN chats_messages ON chats_chats.id=chats_messages.chat_id WHERE chats_chats.chat_ID='$chat_id' AND chats_messages.readed='1' AND sender='$sender'");
        $chats = [];

        if($getUnreadChats->num_rows > 0){ 
            while($chat = $getUnreadChats->fetch_object()){
                $chats[] = $chat;
            }
            $response += [
                "chats" => $chats
            ];
        }else{
            $response += [
                "chats" => "empty"
            ];
        }

        echo json_encode($response);
        return $response;
    }

    public function readedChat($conn, $id){
        $readChat = $conn->query("UPDATE chats_messages SET readed=0 WHERE id='$id'");
        $response = [
            "status" => "200",
            "message" => "readed"
        ];
        echo json_encode($response);
        return $response;
    }

    public function getChats($conn, $chat_id){
        $getAllChats = $conn->query("SELECT chats_chats.*, chats_chats.id as chats_id, chats_messages.*, chats_messages.id as messages_id, chats_messages.created_at as messages_created_at, users.*, users.id as user_id, users.username as user_username, admin.id as admin_id, admin.username as admin_username FROM chats_chats LEFT JOIN chats_messages ON chats_chats.id = chats_messages.chat_id LEFT JOIN users ON chats_chats.user_id=users.id LEFT JOIN users AS admin ON chats_chats.admin_id=admin.id WHERE chats_chats.chat_ID='$chat_id'");
        $allChats = [];
        while($chats = $getAllChats->fetch_object()){
            $chats_id = $chats->chats_id;
            $messages_id = $chats->messages_id;

            if(!isset($allChats[$chats_id])){
                $allChats[$chats_id] = [
                    "chat_ID" => $chats->chat_ID,
                    "user" => [],
                    "admin" => [],
                    "messages" => []
                ];
            }

            $allChats[$chats_id]['user'] = [
                "user_id" => $chats->user_id,
                "username" => $chats->user_username,
            ];

            $allChats[$chats_id]['admin'] = [
                "admin_id" => $chats->admin_id,
                "username" => $chats->admin_username
            ];

            $allChats[$chats_id]["messages"][$messages_id] = [
                "message" => $chats->message,
                "sender" => $chats->sender,
                "readed" => $chats->readed,
                "created_at" => $chats->messages_created_at
            ];
            // $allChats[] = $chats;
        }
        return $allChats;
    }

    public function getAdmin($conn, $chat_id){
        $getIdAdmin = $conn->query("SELECT admin_id FROM chats_chats WHERE chat_ID='$chat_id'");
        $getId = $getIdAdmin->fetch_object();
        $admin_id = $getId->admin_id;
        if($admin_id != null){

            $getAdminUsername = $conn->query("SELECT username FROM users WHERE id='$admin_id'");
            $getUsername = $getAdminUsername->fetch_object();
            $admin_username = $getUsername->username;
            $response = [
                "status" => 200,
                "admin" => true,
                "admin_username" => $admin_username
            ];

        }else{
            $response = [
                "status" => 200,
                "admin" => false,
            ];
        }
        echo json_encode($response);
        return $response;
    }

    public function seeDetail($conn, $chat_id){
        $getDetailChat = $conn->query("SELECT chats_chats.*, chats_chats.id as chats_id, COUNT(chats_messages.id) as message_count, users.id as user_id, users.username as user_username, admin.id as admin_id, admin.username as admin_username FROM chats_chats LEFT JOIN chats_messages ON chats_chats.id=chats_messages.chat_id LEFT JOIN users ON users.id=chats_chats.user_id LEFT JOIN users as admin ON admin.id=chats_chats.admin_id WHERE chats_chats.chat_ID='$chat_id'");
        $detailChat = [];
        while($chat = $getDetailChat->fetch_object()){
            $detailChat = $chat;
        }
        echo json_encode($detailChat);
        return $detailChat;
    }

    public function checkIdByUser($conn, $username){
        $check_username = $conn->query("SELECT id FROM users WHERE username='$username'");
        $getid = $check_username->fetch_object();
        $id = $getid->id;
        $check_chat = $conn->query("SELECT chat_ID FROM chats_chats WHERE user_id=$id");
        if($check_chat->num_rows > 0){
            $getchatid = $check_chat->fetch_object();
            $chat_id = $getchatid->chat_ID;
            return $chat_id;
        }else{
            return "";
        }
    }

    public function checkByCookie($conn, $cookie){
        $check = $conn->query("SELECT chat_ID FROM chats_chats WHERE chat_ID='$cookie'");
        if($check->num_rows > 0){
            return $cookie;
        }else{
            return "";
        }
    }

    public function getAllChats($conn){
        $getAllChats = $conn->query("SELECT chats_chats.id as chats_id, chats_chats.chat_ID, chats_chats.user_id, chats_chats.admin_id, max_ids.*, chats_messages.message, chats_messages.sender, chats_messages.readed, users.id as id_users, users.username as user_username, admin.id as id_admin, admin.username as admin_username FROM chats_chats LEFT JOIN (SELECT chat_id, MAX(id) as max_id, MAX(created_at) as last_message_created FROM chats_messages GROUP BY chat_id) max_ids ON chats_chats.id=max_ids.chat_id LEFT JOIN chats_messages ON max_ids.max_id=chats_messages.id LEFT JOIN users ON chats_chats.user_id=users.id LEFT JOIN users AS admin ON chats_chats.admin_id=admin.id GROUP BY chats_chats.id ORDER BY max_ids.last_message_created DESC");
        
        $allChats = [];
        while($chats = $getAllChats->fetch_object()){
            $allChats[] = $chats;
        }
        return $allChats;
    }

    public function deleteChat($conn, $chat_id){
        $deleteChat = $conn->query("DELETE FROM chats_chats WHERE chat_ID='$chat_id'");
        if($deleteChat === TRUE){
            if(isset($_COOKIE['chat_ID'])){
                setcookie('chat_ID', "", time() - 3600);
            }
            $response = [
                "status" => 200
            ];
        }else{
            $response = [
                "status" => 400
            ];
        }
        echo json_encode($response);
        return $response;
    }

    public function adminTakeOver($conn, $username, $chat_id){
        session_start();
        $response = [];
        if($username = $_SESSION['user']['username']){

            $getIdAdmin = $conn->query("SELECT id FROM users WHERE username='$username'");
            $getId = $getIdAdmin->fetch_object();
            $id = $getId->id;

            $updateAdminIdChat = $conn->query("UPDATE chats_chats SET admin_id='$id' WHERE chat_ID='$chat_id'");
            if($updateAdminIdChat === TRUE){
                $response += [
                    "status" => 200,
                    "message" => "Chat success take over"
                ];
            }
        }else{
            $response += [
                "status" => "400",
                "username" => "Username not found"
            ];
        }
        echo json_encode($response);
        return $response;
    }

}