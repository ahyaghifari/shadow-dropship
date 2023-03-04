$(document).ready(function () {
   
    function checkNotApproveOrder() {
        $.ajax({
            type: "POST",
            url: "orders.php",
            data: {
                check_not_approve_order: true
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.status == 200) {
                    $('#nav-not-approve-container').append(`
                    <div id="not-approve-mark" class="w-[7px] h-[7px] md:w-[10px] md:h-[10px] rounded-full absolute top-0 right-0 bg-neutral-100"></div>
                    `)
                } else {
                    $('#not-approve-mark').remove();
                }        
            }
        });
    }

    checkNotApproveOrder();

    setInterval(() => {
        checkNotApproveOrder();
    }, 15000);

    $('.order-approve-btn').click(function () {
        
        var order_code = $(this).data('order');
        var btn =  $('#approve-' + order_code + '-btn');
        var conf = confirm("Tekan OK untuk approve untuk pesanan " + order_code)
    
        if (conf == true) {
            $.ajax({
                type: "POST",
                url: "orders.php",
                data: {
                    approve_order: true,
                    order_code: order_code
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {   
                        if (btn.hasClass('main-page')) {   
                            $('#order-' + order_code + ' .orders-info').append(`
                            <p class="text-[10px] bg-neutral-700 text-neutral-300 p-1 text-center font-semibold capitalize">Belum Dikonfirmasi</p>
                            `)
                            if (response.not_approve_count == 0) {
                                $('#not-approve-mark').remove();                            
                            }
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
            return false;
        }
    })

    if(!$('#orders-filter').hasClass('show')){
        $('#orders-filter').hide();
    }
    
    $('.orders-filter-btn').click(function () {
        $('#orders-filter').slideToggle();
    })
   
});