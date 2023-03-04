<div id="product-addtocart" class="fixed bottom-0 left-0 bg-neutral-800 w-full md:left-auto md:right-0 md:w-[50%] md:h-full lg:w-[30%]">
    <div id="product-addtocart-close" class="border-b border-neutral-800 text-right p-1">
         <span  id="product-addtocart-close-btn" class="material-symbols-rounded text-xl md:text-2xl cursor-pointer">
            close
        </span>
    </div>
    <div id="product-addtocart-product" class="flex p-2">
        <div class="bg-neutral-800 p-1">
            <img id="addtocart-image" src="src/products/chino.png" class="w-[100px] h-[100px] md:w-[150px] md:h-[150px] object-contain" alt="">
        </div>
        <div class="ml-2">
            <h5 class="font-black text-lg md:text-xl" id="addtocart-name">SHIRT</h5>
            <h5 class="text-sm">Rp. <span id="addtocart-price"> 150000 </span></h5>
            <h5 class="text-neutral-400 text-[13px] mt-2">Berat : <span id="addtocart-weight"></span> G</h5>
            <h5 class="text-neutral-400 text-[13px]">Stok : <span id="addtocart-stock">100</span></h5>
        </div>
    </div>
    <form action="products.php" method="post">
    <div id="product-addtocart-sizes" class="p-4 py-6 flex justify-evenly flex-wrap border-b border-neutral-600">
        <p class="text-neutral-400 text-sm">Ukuran: </p>
        <div>
            <input type="radio" name="size" id="xs" value="XS" required>
            <label for="xs">XS</label>
        </div>
        <div>
            <input type="radio" name="size" id="s" value="S" >
            <label for="s">S</label>
        </div>
        <div>
            <input type="radio" name="size" id="m" value="M">
            <label for="m">M</label>
        </div>
        <div>
            <input type="radio" name="size" id="l" value="L">
            <label for="l">L</label>
        </div>
        <div>
            <input type="radio" name="size" id="xl" value="XL">
            <label for="xl">XL</label>
        </div>
        <div>
            <input type="radio" name="size" id="xxl" value="XXL">
            <label for="xxl">XXL</label>
        </div>
    </div>
    <div id="product-addtocart-quantity" class="p-4 py-6 flex justify-between items-center border-b border-neutral-600">
        <p class="text-neutral-400 text-sm">Qty : </p>  
        <div class="flex items-center text-sm">
            <span class="material-symbols-rounded cursor-pointer text-neutral-400 qty-btn minus">
                remove
            </span>
            <input type="number" name="qty" id="qty" max="10" min="1" value="1" class="mx-3 text-center">
            <span class="material-symbols-rounded cursor-pointer text-neutral-400 qty-btn plus">
                add
            </span>
        </div>
    </div>
    <div class="py-7 p-4 flex justify-center">
        <button type="submit" id="addtocart-btn" class="bg-neutral-200 text-neutral-800 font-semibold p-1 w-full text-sm">Masukkan Keranjang</button>
    </div>
    </form>
</div>
<script>
    $('#product-addtocart-close-btn').click(function(){
        $('#product-addtocart').removeClass('active')
        setTimeout(function(){
            $('#product-addtocart-place').remove();
        }, 300)
    })

    $('.qty-btn').on('click', function () {
        if ($(this).hasClass('plus')) {
           $(this).prev()[0].stepUp()
        } else {
            $(this).next()[0].stepDown()
        }
    });
    
    $('#addtocart-btn').click(function(e){
        e.preventDefault();
        if(!$('input[name="size"]:checked').length){
            alert("Pilih ukuran terlebih dahulu");
            return false;
        }
        var slug = $(this).data('product');
        var size = $('input[name="size"]:checked').val()
        var qty = $('input[name="qty"]').val()
        $.ajax({
            type: "POST",
            url: "products.php",
            data: {
                cart: "add",
                slug: slug,
                size: size,
                qty: qty
            },
            dataType: "json",
            success: function (response) {
                if(response.status == 200){
                    if(response.context == "new"){
                        var count = parseInt($('#cart-count').html())
                        $('#cart-count').html(count + 1);               
                    }
                    $('#product-addtocart').removeClass('active')
                    setTimeout(function(){
                        $('#product-addtocart-place').remove();
                    }, 300)
                    $('#nav-cart').addClass('animate');
                    setTimeout(function(){
                        $('#nav-cart').removeClass('animate');
                    }, 3000)
                }
            }
        });

    })
</script>