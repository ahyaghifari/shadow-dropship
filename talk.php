<?php 
    include "database/config.php";
    include "database/Chats.php";
    $chats = new Chats;

    if(isset($_POST['get_all_chat'])){
        $all = $chats->getAllChats($conn);
        echo json_encode($all);
        return $all;
    }

    if(isset($_POST['read_all_chat'])){
        $readChats = $chats->readAllChats($conn, $_POST['chat_id'], $_POST['role'], $_POST['responder']);
        return $readChats;
    }

    if(isset($_POST['get_unread_chats'])){
        $getChats = $chats->getUnreadChats($conn, $_POST['chat_id'], $_POST['role'], $_POST['responder']);
        return $getChats;
    }

    if(isset($_POST['readed_chat'])){
        $readedChat = $chats->readedChat($conn, $_POST['id']);
        return $readedChat;
    }

    if(isset($_POST['get_current_admin'])){
        $getAdmin = $chats->getAdmin($conn, $_POST['chat_id']);
        return $getAdmin;
    }

    if(isset($_POST['new_message'])){
        $createchat = $chats->new($conn, $_POST['chat_id'], $_POST['message'], $_POST['username'], $_POST['role'], $_POST['responder']);
        return $createchat;
    }

    if(isset($_POST['see_detail_chat'])){
        $seeDetailChat = $chats->seeDetail($conn, $_POST['chat_id']);
        return $seeDetailChat;
    }

    if(isset($_POST['delete_chat'])){
        $deleteChat = $chats->deleteChat($conn, $_POST['chat_id']);
        return $deleteChat;
    }

    if(isset($_POST['admin_takeover'])){
        $adminTakeOver = $chats->adminTakeOver($conn, $_POST['username'], $_POST['chat_id']);
        return $adminTakeOver;
    }

    ?> <title>Chat Admin | Shadow Dropship</title> <?php 
    $username = "";
    $chat_ID = "";
    $user_role = "anonymous";
    $responder = "";
    $admin_username = "";

    include "components/base_chat.php";

    if(isset($_SESSION['login'])){
        if(isset($_COOKIE['chat_ID'])){     
            setcookie('chat_ID', "", time() - 3600);
        }
        $username = $_SESSION['user']['username'];
        $user_role = $_SESSION['user']['role'];
        $getChatID = $chats->checkIdByUser($conn, $username);
        $chat_ID = $getChatID;
    }
    
    if(isset($_COOKIE['chat_ID'])){
            $checkCookie = $chats->checkByCookie($conn, $_COOKIE['chat_ID']);
            if($checkCookie == $_COOKIE['chat_ID']){
                $chat_ID = $_COOKIE['chat_ID'];
            }else{ 
                setcookie('chat_ID', "", time() - 3600);
            }
    }

    if($user_role == "admin"){
        if(isset($_GET['ID'])){
            $chat_ID = $_GET['ID'];
        }
        //  var_dump($adminAllChats);
        //   die;
    }

     $allChats = $chats->getChats($conn, $chat_ID);
    // $allChats = $chats->seeDetail($conn, $chat_ID);
    // var_dump($allChats);
    // die;
?>
<div id="chat" class="h-screen p-3 mx-auto flex <?= ($user_role != "admin") ? "sm:w-10/12 md:w-8/12" : "gap-5" ?>">
    <?php if($user_role == "admin"){
        $selected_chat_id = "";
        if(isset($_GET['ID'])){
            $selected_chat_id = $_GET['ID'];
        }
        ?>
        <div id="admin-allchats" class="fixed top-0 right-0 bg-neutral-800 w-[65%] h-screen md:static w-3/6 lg:w-2/6 md:pt-16">
            <input type="hidden" id="selected_chat_id" name="selected_chat_id" value="<?= $selected_chat_id ?>">
            <div id="admin-allchats-header">
                <div class="text-right p-2 mb-3 md:hidden">
                    <button class="allchats-admin-btn text-right">
                        <span class="material-symbols-rounded">
                            close
                        </span>
                    </button>
                </div>
            </div>
            
        </div>
    <?php } ?>
    <div id="main-chat" class="w-full relative">
    <div id="chat-header" class="bg-neutral-900 p-3 shadow-md flex items-center justify-between">
        <div id="chat-header-admin">
            <h3 class="md:text-xl"><?php if($user_role == "admin" && !isset($_GET['ID'])){echo "Pilih ruang chat"; }else{ ?><?= ($user_role == "user" || $user_role == "anonymous" ) ? "Admin" : "User" ?><?php } ?>: <span id="header-username" class="font-semibold"></span></h3>
        </div>
        <div id="chat-header-right" class="flex items-center">
            <div id="chat-header-options" class="relative">
                <button id="chat-header-options-btn" class="text-neutral-400">
                    <span class="material-symbols-rounded">
                        more_vert
                    </span>
                </button>
                <div id="chat-header-options-content" class="absolute bg-neutral-200 w-[130px] flex flex-col -bottom-16 right-1/2 text-neutral-600 text-left z-[1] text-sm md:text-base">
                    <?php if($chat_ID != ""){?>
                    <button id="see-detail-chat-btn" class="px-2 py-1 hover:bg-neutral-300 flex items-center" data-id="<?= $chat_ID ?>"><span class="material-symbols-rounded text-[16px] mr-2">info</span>Info</button>
                    <button id="delete-chat-main-btn" class="px-2 py-1 hover:bg-neutral-300 flex items-center"><span class="material-symbols-rounded text-[16px] mr-2">delete</span>Hapus</button>
                <?php } ?>
                </div>
            </div>
            <?php if($user_role == "admin"){ ?>
            <button class="allchats-admin-btn md:hidden">
                <span class="material-symbols-rounded text-neutral-400">
                    chat
                </span>
            </button>
            <?php } ?>
        </div>
    </div>
    <div id="chat-container" class="h-full min-h-[90%]">
        <?php if($allChats){ foreach($allChats as $getChats){
            if($user_role == "user" || $user_role == "anonymous"){
                $responder = $getChats['admin']['username'];
            }else{
                $responder = $getChats['user']['username'];
            }
            $admin_username = $getChats['admin']['username'];
        ?>
        <div id="chat-box">
            <?php foreach($getChats['messages'] as $chats ){
                $role = ($chats['sender'] == $user_role) ? "sender" : "receiver";
                $not_read = ($chats['readed'] == 1 && $chats['sender'] != $user_role) ? "not-read" : "";
                ?>
                <div class="chats <?= $role ?> <?= $not_read ?>">
                    <p class="chats-username"><?php 
                        if($role == "sender" ){
                            echo "you";
                        }else{
                            if($user_role == "user" || $user_role == "anonymous"){
                                echo "admin";
                            }else{
                                echo "user ";
                            };
                        }
                    ?></p>
                    <p class="chats-message"><?= $chats['message'] ?></p>
                    <p class="chats-time to-calendar"><?= $chats['created_at'] ?></p>
                </div>
            <?php } ?>
        </div>
        <?php } }else{ ?>
            <div id="chat-box" class="chat-empty">
                <p class="no-chats text-center">Belum ada percakapan</p>
            </div>    
        <?php } ?>

        <?php if(($user_role == "user" || $user_role == "anonymous") || ($user_role == "admin" && isset($_GET['ID']) )){ if(($user_role == "user" || $user_role == "anonymous") || ($user_role == "admin" && ($username == $admin_username || $admin_username == ""))){?>
        <form action="chat.php" id="chat-form" method="post" class="bg-neutral-900 p-2 flex">
            <textarea id="message-text" class="bg-transparent resize-none w-[85%] text-sm md:text-base focus:outline-none p-1 border border-neutral-500 max-h-[20vh]" placeholder="Masukkan pesan..." required></textarea>
            <button type="submit" id="message-send" class="w-[15%] bg-neutral-200 p-1 text-neutral-800 active:bg-neutral-800 active:text-neutral-200"><span class="material-symbols-rounded md:text-2xl lg:text-3xl">
            send
        </span></button>
            <input id="message-username" type="hidden" value="<?= $username ?>">
            <input id="message-role" type="hidden" value="<?= $user_role ?>">
            <input id="chat_id" type="hidden" value="<?= $chat_ID ?>">
            <input id="responder" type="hidden" value="<?= $responder ?>">
        </form>
        <?php }else{ ?>
            <div id="admin-takeover" class="p-2 bg-neutral-700 text-sm flex items-center mt-4">
                <p class="w-9/12">Chat ini sudah ditangani oleh admin : <span class="font-semibold"> <?= $admin_username ?></span></p>
                <button id="admin-takeover-btn" data-username="<?= $username ?>" class="w-3/12 bg-neutral-800 p-2 active:bg-neutral-900 shadow rounded-md">Ambil Alih</button>
            </div>  
        <?php }?>

    </div>
    <div id="chat-scrolltobottom" class="absolute bottom-28 left-1/2 -translate-x-8 right-auto cursor-pointer p-1 apsect-ratio w-[44px] h-[44px] border-2 border-dashed rounded-full flex justify-center">
        <span class="material-symbols-rounded text-3xl">
            expand_more
        </span>
    </div>
    <?php } ?>
    </div>
</div>



<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<script src="assets/js/talk.js"></script>
<?php include "components/foot.php"?> 