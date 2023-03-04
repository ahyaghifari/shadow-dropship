$(document).ready(function () {

    const cartalltotal = async () => {
        var p = [];
        var total = 0;
        $('.items-total').each(function (i, obj) {
            gettotal = parseInt(obj.innerHTML);
            p.push(gettotal);
        })
        p.forEach(element => {
            total += element  
        });
        $('#cart-alltotal').html(total);
    }

    cartalltotal();

    $('.qty-btn').on('click', function () {
        if ($(this).hasClass('plus')) {
           $(this).prev()[0].stepUp()
        } else {
            $(this).next()[0].stepDown()
        }
        $(this).siblings('.qty-items').trigger('change');
    });

    $('.qty-items').change(function () {
        var slug = $(this).data('slug');
        var size = $(this).data('size');
        var val = $(this).val();
        $.ajax({
            type: "POST",
            url: "cart.php",
            data: {
                quantity: true,
                slug: slug,
                size: size,
                val: val
            },
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    $('#cart-items-qty-' + slug + "-" + size).html(val);
                    $('#cart-items-total-' + slug + "-" + size).html(response.total);
                    cartalltotal();
                }
            }
        });
    })

    $('.cart-items-remove').click(function (){
        var slug = $(this).data('slug');
        var size = $(this).data('size');
        var conf = confirm("Kamu yakin ingin menghapus item ini?");
        if (conf == true) {
            $.ajax({
                type: "POST",
                url: "cart.php",
                data: {
                    remove: true,
                    slug: slug,
                    size: size
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == 200 && response.count > 0) {
                        $('#cart-items-' + slug + "-" + size).remove();
                        var count = parseInt($('#cart-count').html())
                        $('#cart-count').html(count - 1);       
                        cartalltotal();
                    } else if (response.count == 0) {
                        location.reload();
                    }
                }
            });
        } else {
            return false;
        }
    })

    $('#deleteall-btn').click(function () {
        var conf = confirm("Kamu yakin ingin menghapus semua item?");
        if (conf == true) {
            $.ajax({
                type: "POST",
                url: "cart.php",
                data: {
                    deleteall : true
                },
                dataType: "json",
                success: function (response) {
                    if(response.status == 200){
                        location.reload()
                    }
                }
            });
        } else {
            return false
        }
    })

});