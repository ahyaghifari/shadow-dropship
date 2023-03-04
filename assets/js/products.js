$(document).ready(function () {

    $('.product-info').click(function () {
        var slug = $(this).data('product');
        $.ajax({
            type: "GET",
            url: "products.php",
            data: {
                getdetail: true,
                slug: slug
            },
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                $('#product-detail-place').remove();
                $('main').append(`<div id="product-detail-place"></div>`) 
                $('#product-detail-place').load('components/product-detail.php', function () {
                    $('#detail-name').html(response.products_name);
                    $('#detail-image').attr('src','src/products/' + response.products_image)
                    $('#detail-price').html(response.price);
                    $('#detail-description').html(response.description);
                    $('#detail-material').html(response.material);
                    $('#detail-stock').html(response.stock);
                    $('#detail-weight').html(response.weight);
                    $('#detail-category').html(response.name);
                    $('body').addClass('modal-open');
                    $('#product-detail').addClass("active");
                })
            }
        });
    })

    $('.order').click(function () {
        var slug = $(this).data('product');
        $.ajax({
            type: "GET",
            url: "products.php",
            data: {
                addtocart: true,
                slug: slug
            },
            dataType: "json",
            success: function (response) {
                $('#product-addtocart-place').remove();
                $('main').append(`<div id="product-addtocart-place"></div>`)
                $('#product-addtocart-place').load('components/product-addtocart.php', function () {
                    $('#addtocart-image').attr('src', 'src/products/' + response.image);
                    $('#addtocart-name').html(response.name)
                    $('#addtocart-price').html(response.price)
                    $('#addtocart-weight').html(response.weight)
                    $('#addtocart-stock').html(response.stock)
                    $('#addtocart-btn').attr('data-product', response.slug)
                    setTimeout(function () {
                        $('#product-addtocart').addClass('active')
                    }, 100)
                })
            }
        });
    })

    

});