$(document).ready(function () {

    function toCalendarTime(time) {
        var momentNow = moment(time, "YYYY-MM-DD hh:mm:ss").locale('id').calendar()
        return momentNow;
    }

    function toSlug(str) {
        var slug = str.toLowerCase().replace(/[^\w]+/g, '-');
        slug = slug.replace(/-{2,}/g, '-');
        slug = slug.replace(/^-+|-+$/g, '');
        return slug;
    }

    function deleteConfirm(form, message1, message2) {
        var conf1 = confirm(message1);
        if (conf1 == true) {
            var conf2 = confirm(message2);
            if (conf2 == true) {
                form.submit()
            }
        } else {
            return false;
        }
    }

    $('.to-calendar').each(function (i, e) {
        var time = e.innerHTML;
        e.innerHTML = toCalendarTime(time);
    });

    $('.admin-sidebar-btn').click(function () {
        $('#admin-sidebar').toggleClass('active');
    })

    $('.products-detail-more').hide();
    $('.see-more-detail').click(function () {
        $(this).siblings('.products-detail-more').slideToggle();
    })

    $('.image-preview').attr('src', '../src/products/' + $('.image-input-value').val());

    $('.image-input').change(function () {
        var file = this.files[0]
        var src = URL.createObjectURL(file)
        $('.image-preview').attr('src', src)
    })

    $('#input-product-name').keyup(function () {
        var result = toSlug($(this).val());
        $('#input-product-slug').val(result);
        $('.btn-product-crud').attr('disabled', 'disabled');
        if (result !== $('#old-slug').val()) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    check_slug_product: true,
                    slug: result
                },
                dataType: "json",
                success: function (response) {
                    $('#slug-check').remove();
                    var slugColor = 'green';
                    if (response.result == false) {
                        slugColor = 'red'
                    } else {
                        $('.btn-product-crud').removeAttr('disabled');
                    }
                    var slugOutput = `<span id="slug-check" class="text-[12px] md:text-[14px] text-${slugColor}-600">${response.message}</span>`;
                    
                    $('.slug-input').after(slugOutput)
                },
            });
        } else {
            $('#slug-check').remove();
            $('.btn-product-crud').removeAttr('disabled');
        }
    })

    $('.message-close-btn').click(function () {
        $('.message').remove();
    })

    $('#delete-product-btn').click(function (e) {
        e.preventDefault();
        deleteConfirm($('#form-delete-product'), "Yakin ingin menghapus produk ini?", "Tekan OK sekali lagi untuk menghapus");
    });

    $('#input-category-name').keyup(function () {
        var result = toSlug($(this).val());
        $('#input-category-slug').val(result);
        $('.btn-product-crud').attr('disabled', 'disabled');
        if (result !== $('#old-slug').val()) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    check_slug_category: true,
                    slug: result
                },
                dataType: "json",
                success: function (response) {
                    $('#slug-check').remove();
                    var slugColor = 'green';
                    if (response.result == false) {
                        slugColor = 'red'
                    } else {
                        $('.btn-product-crud').removeAttr('disabled');
                    }
                    var slugOutput = `<span id="slug-check" class="text-[12px] md:text-[14px] text-${slugColor}-600">${response.message}</span>`;
                    
                    $('.slug-input').after(slugOutput)
                },
            });
        } else {
            $('#slug-check').remove();
            $('.btn-product-crud').removeAttr('disabled');
        }
    })

    $('.users-more').hide();
    $('.users_expand-btn').click(function () {
        $('#users-more-' + $(this).data('username')).slideToggle();
    })

    if(!$('.form-user-filter').hasClass('show')){
        $('.form-user-filter').hide();
    }
    $('.user-filter-btn').click(function () {
        $('.form-user-filter').slideToggle();
    })

    $('.username-input').keyup(function () {
        var username = $(this).val();
        $('.btn-user-crud').attr('disabled', 'disabled');
        if (username != $('#old-username').val()) {   
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    check_username: true,
                    username: username
                },
                dataType: "json",
                success: function (response) {
                    $('#username-check').remove();
                    var usernameColor = 'green';
                    if (response.result == false) {
                        usernameColor = 'red'
                    } else {
                        $('.btn-user-crud').removeAttr('disabled');
                    }
                    var usernameOutput = `<span id="username-check" class="ml-2 text-[12px] md:text-[14px] text-${usernameColor}-600">${response.message}</span>`;
                    $('.username-input').after(usernameOutput);
                }
            });
        } else {
            $('#username-check').remove();            
            $('.btn-user-crud').removeAttr('disabled');
        }
    })

    $('#delete-user-btn').click(function (e){
        e.preventDefault();
        deleteConfirm($('#form-delete-user'), "Kamu yakin untuk menghapus user ini?", "Tekan OK untuk menghapus");
    })

    $('.order-approve-btn').click(function () {
        
        var order_code = $(this).data('order');
        var btn =  $('#approve-' + order_code + '-btn');
        btn.attr('disabled', 'disabled');
        var conf = confirm("Tekan OK untuk approve untuk pesanan " + order_code)
        
        if (conf == true) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    approve_order: true,
                    order_code: order_code
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {   
                        if (btn.hasClass('main-page')) {   
                            $('#order-' + order_code + ' .orders-info-actions').append(`
                            <p class="text-[10px] bg-neutral-400 text-neutral-200 p-[1px] text-center font-semibold capitalize">Belum Dikonfirmasi</p>
                            `)
                        } else if (btn.hasClass('detail-page')) {
                            $('#detail-order-status').html('Dalam-Proses')
                        }
                        btn.remove();
                    } else if (response.reload) {
                        alert('Kode pemesanan tidak ditemukan');
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    }
                }
            });
        } else {
            btn.removeAttr('disabled');
            return false;
        }
    })

    $('#order-info').hide();
    $('#order-info-btn').click(function () {
        $('#order-info').slideToggle();
    })

    $('.contacts-messages').hide();
    $('.contact_expand-btn').click(function () {
        $(this).prev().slideToggle();
    })

    $('.input-contacts-read').click(function () {
        var status = true;
        var id = $(this).data('id');

        if ($(this).prop('checked')) {
            status = true;
        } else {
            status = false;
        }

        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                read_contact: true,
                id: id,
                status: status
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
            }
        });
        
        if (status == true) {
            $('#contacts-readed-' + id).html("Sudah Dibaca");
            $('#contact-message-btn-'+id).removeClass('bg-neutral-400')
            $('#contact-message-btn-'+id).addClass('bg-neutral-300')
        } else {
            $('#contacts-readed-' + id).html("Belum Dibaca");
            $('#contact-message-btn-'+id).removeClass('bg-neutral-300')
            $('#contact-message-btn-'+id).addClass('bg-neutral-400')
        }
       
    })

});