$(document).ready(function () {

    var chatbox = $('#chat-box');
    var chat_id = $('#chat_id').val();
    var username = $('#message-username').val();
    var role = $('#message-role').val();
    var responder = $('#responder').val();
    var selectedChatId = $('#selected_chat_id').val();

    function toCalendarTime (time) {
        var momentNow = moment(time, "YYYY-MM-DD hh:mm:ss").locale('id').calendar()
        return momentNow;
    }

    function toRelativeTime (time) {
        var momentNow = moment(time, "YYYY-MM-DD hh:mm:ss").locale('id').fromNow();
        return momentNow;
    }

    function checkOneHourChat(time) {

        var timestamp = moment(time, "YYYY-MM-DD hh:mm:ss")

        if (moment().diff(timestamp, "hours") >= 1){
            var timeresult = toCalendarTime(time);
        } else {
            var timeresult = toRelativeTime(time);  
        }
        return timeresult;
    }

    function scrollToBottom() {
        chatbox.scrollTop(chatbox[0].scrollHeight - chatbox.outerHeight())
    }


    function checkAdmin() {
        $.ajax({
            type: "POST",
            url: "tak.php",
            data: {
                get_current_admin: true,
                chat_id: chat_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.admin == true) {
                    $('#header-username').html(response.admin_username);
                } else {
                    $('#header-username').html("");
                }
            }
        });
        setInterval(function () {
            $.ajax({
                type: "POST",
                url: "talk.php",
                data: {
                    get_current_admin: true,
                    chat_id: chat_id,
                },
                dataType: "json",
                success: function (response) {
                    if (response.admin == true) {
                    $('#header-username').html(response.admin_username);
                } else {
                    $('#header-username').html("");
                }
                }
            });
            
        }, 25000)
    }

    if ((role == "user" || role == "anonymous") && (chat_id != "")) {
        checkAdmin();
    } 

    const adminAllChatsComp = (chat_ID, user_username, messages, senders, readeds, admin_username, last_message_created) => {
        var bg = "";

        if (chat_ID == selectedChatId) {
            bg = "bg-neutral-900"
        } else if(admin_username == null){
            bg = "bg-neutral-700"
        } else {
            bg = "bg-neutral-800"
        }
        
        var username = "anonymous";
        if (user_username != null) {
            username = user_username
        }
        var message = messages;
        if (message.length >= 20) {
            message = message.slice(0, 20) + "...";
        }
        var undread = "";
        if(senders != "admin" && readeds == 1){
            undread = '<p class="undread-chats-mark w-1/12 bg-neutral-200 text-neutral-100 aspect-ratio text-neutral-800 font-semibold text-center rounded-full text-[11px] flex justify-center items-center sm:text-[12px] md:text-[13px]"><span>U</span></p>';
        }
        var admin = "No Admin";
        var admin_mark = "font-semibold"
        if (admin_username != null){
            admin = admin_username;
            admin_mark = "";
        }

        var message_created = checkOneHourChat(last_message_created);

        $('#admin-allchats').append(`
            <div id="chat-${chat_ID}" class="all-chats p-2 border-b border-neutral-700 ${bg} hover:bg-neutral-900">
            <a href="talk.php?ID=${chat_ID}">
            <div class="all-chats-user flex justify-between">
                <h5 class="font-semibold text-[12px] sm:text-[13px] md:text-[14px]">${username}</h5>
                <p class="text-[12px]  md:text-[13px]">${admin}</p>
            </div>
            <div class="all-chats-message mt-1 flex gap-x-2 items-center">
                <p class="w-11/12">${message}</p>
                ${undread}
            </div>
            </a>
            <div class="all-chats-time-actions flex justify-between mt-1 items-center">
                <p class="text-[11px] opacity-75 to-relative-time md:text-[12px]">${message_created}</p>
                <div class="all-chats-actions relative flex justify-end items-end">
                    <button class="all-chats-btn">
                    <span class="material-symbols-rounded text-neutral-500 text-lg md:text-xl">
                    more_vert
                    </span>
                    </button>
                    <div class="all-chats-actions-content absolute right-1/2 left-auto md:left-1/2 md:right-auto -bottom-8 text-sm md:text-base bg-neutral-200 p-1 text-neutral-800 hidden">
                        <button data-id="${chat_ID}" class="delete-chat-btn">Hapus</button>
                    </div>
                </div>
            </div>
            </div>
        `)
    }


    if($('#admin-allchats').length > 0) {
        $.ajax({
            type: "POST",
            url: "talk.php",
            data: {
                get_all_chat: true,
            },
            dataType: "json",
            success: function (response) {
                $('#admin-allchats .all-chats').remove();
                $.each(response, function (i, v) {
                    adminAllChatsComp(v.chat_ID, v.user_username, v.message, v.sender, v.readed, v.admin_username, v.last_message_created);
                }); 
            }
        });
        setInterval(function () {
            $.ajax({
                type: "POST",
                url: "talk.php",
                data: {
                    get_all_chat: true,
                },
                dataType: "json",
                success: function (response) {
                    $('#admin-allchats .all-chats').remove();
                    $.each(response, function (i, v) {
                        adminAllChatsComp(v.chat_ID, v.user_username, v.message, v.sender, v.readed, v.admin_username, v.last_message_created);
                    }); 
                }
            });
        }, 10000)
    }
    
    if ($('.not-read').length > 0) {
        $.ajax({
            type: "POST",
            url: "talk.php",
            data: {
                read_all_chat: true,
                chat_id: chat_id,
                role: role,
                responder: responder
            },
            dataType: "json",
            success: function (response) {
                $('.chats').removeClass('not-read');
            }
        });
    }

    $('#header-username').html($('#responder').val());
    var undreadChats = false;


    function getUnreadChats() {
      setInterval(function () {
            $.ajax({
                type: "POST",
                url: "talk.php",
                data: {
                    get_unread_chats: true,
                    chat_id: chat_id,
                    role: role,
                    responder: responder 
                },
                dataType: "json",
                success: function (response) {
                    if (response.chat_not_found) {
                        alert(response.message);
                        setTimeout(function () {
                            window.location.replace('talk.php');
                        }, 500)
                        return;
                    }
                    undreadChats = true;
                    if (response.chats != "empty") {
                        var allChats = response.chats;
                        $.each(allChats, function (i, v) { 
                            $('#chat-box').append(`
                                <div class="chats receiver">
                                    <p class="chats-username">${v.sender}</p>
                                    <p class="chats-message">${v.message}</p>
                                    <p class="chats-time to-calendar">${ toCalendarTime(v.created_at) }</p>
                                </div>
                            `);
                            $.ajax({
                                type: "POST",
                                url: "talk.php",
                                data: {
                                    readed_chat: true,
                                    id: v.messages_id,
                                },
                                dataType: "json",
                                success: function (response) {                               
                                }
                            });
                        });
                    }
                }
            });
        }, 5000);  
    }

    if(!$('.chat-empty').length) {
        getUnreadChats();
    }



    $('#message-send').click(function (e) {
        if($('.chat-empty').length > 0) {
            $('#chat-box p').remove();
            $('#chat-box').removeClass('chat-empty');
        }
        e.preventDefault();
        if ($('#message-text').val() === '') {
            return;
        }
        var message = $('#message-text').val();
        var data = {
            new_message: true,
            chat_id: chat_id,
            message: message,
            username: username,
            role: role,
            responder: responder
        }

        $.ajax({
            type: "POST",
            url: "talk.php",
            data: data,
            dataType: "json",
            success: function (response) {
                $('#chat-box').append(`
                    <div class="chats sender">
                        <p class="chats-username">you</p>
                        <p class="chats-message">${message}</p>
                        <p class="chats-time to-calendar">${toCalendarTime(response.now)}</p>
                    </div>
                `);
                $('#message-text').val("");
                if (response.chat_id) {
                    $('#chat_id').val(response.chat_id);
                    chat_id = response.chat_id;
                    $('#chat-header-options-content').append(`
                    <button id="see-detail-chat-btn" class="px-2 py-1 hover:bg-neutral-300 flex items-center" data-id="${response.chat_id}"><span class="material-symbols-rounded text-[16px] mr-2">info</span>Info</button>
                    <button id="delete-chat-main-btn" class="px-2 py-1 hover:bg-neutral-300 flex items-center"><span class="material-symbols-rounded text-[16px] mr-2">delete</span>Hapus</button>
                    `)
                    checkAdmin();
                }

                if (response.chat_id_not_found) {
                    alert(response.message);
                    setTimeout(function () {
                        window.location.replace('talk.php');
                    }, 1000);
                }

                if (undreadChats == false) {   
                    getUnreadChats();
                }
                scrollToBottom();
            },
        });
    })

    $('.allchats-admin-btn').click(function () {
        $('#admin-allchats').toggleClass('active');
    });

    $('#chat-scrolltobottom').click(function () {
        scrollToBottom(); 
    });

    $(document).on('click', '.all-chats-btn', function () {
        $('.all-chats-actions-content').not($(this).next()).removeClass('hidden').addClass('hidden');
        $(this).next().toggleClass('hidden');
    })

    $(document).on('click', '.delete-chat-btn', function () {
        var chatID = $(this).data('id');
        var conf = confirm('Yakin ingin menghapus chat ' + chatID + " ?");
        if(conf === true){
            $.ajax({
                type: "POST",
                url: "talk.php",
                data: {
                    delete_chat: true,
                    chat_id: chatID
                },
                dataType: "json",
                success: function (response) {
                    if(response.status == 200){
                        $('#chat-'+chatID).remove();
                    }
                }
            });
        }else{
            return false;
        }
    })

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.all-chats-actions').length) {
            $('.all-chats-actions-content').addClass('hidden');
        }
        
    })
    
    $('#admin-takeover-btn').click(function () {
        var username = $(this).data('username');
        var chat_id = $('#selected_chat_id').val();
        var conf = confirm("Tekan ok untuk konfirmasi untuk ambil alih chat ini");
        if (conf == true) {
            $.ajax({
                type: "POST",
                url: "talk.php",
                data: {
                    admin_takeover: true,
                    username: username,
                    chat_id: chat_id
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {
                        location.reload();
                    }
                }
            });
        } else {
            return false;
        }
    })

    $(document).on('click', '#see-detail-chat-btn',function () {
        $.ajax({
            type: "POST",
            url: "talk.php",
            data: {
                see_detail_chat: true,
                chat_id: chat_id
            },
            dataType: "json",
            success: function (response) {
                var user = "anonymous";
                var admin = "Belum ditentukan";
                if (response.admin_username != null) {
                    admin = response.admin_username
                }
                if (response.user_username != null) {
                    user = response.user_username
                }

                $('#chat-info-place').remove();
                $('main').append(`<div id="chat-info-place"></div>`) 
                $('#chat-info-place').load('components/chat-info.php', function () {    
                    $('#detail-chat-id').html(response.chat_ID);
                    $('#detail-chat-user').html(user);
                    $('#detail-chat-admin').html(admin);
                    $('#detail-chat-message-count').html(response.message_count);
                    $('#detail-chat-created_at').html(toCalendarTime(response.created_at));
                    setTimeout(function () {
                        $('#chat-detail').addClass('active');
                    }, 300)
                })
            }
        });
        
    })

    $(document).on('click', '#close-chat-info-btn',function (){
        $('#chat-detail').removeClass('active');
        $('#chat-detail').addClass('nonactive');
        setTimeout(function () {
            $('#chat-info-place').remove();
        }, 300)
    })

    $(document).on('click', '#delete-chat-main-btn', function () {
        var conf = confirm("Kamu yakin hapus ruang chat ini ?");
        if (conf == true) {
            $.ajax({
                type: "POST",
                url: "talk.php",
                data: {
                    delete_chat: true,
                    chat_id: chat_id
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {
                        window.location.replace('talk.php');
                    }
                }
            });
        }
        
    })

    $('#chat-header-options-content').hide();

    $('#chat-header-options-btn').click(function () {
        $('#chat-header-options-content').toggle();
    })

    

});