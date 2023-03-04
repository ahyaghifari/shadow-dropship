<div id="product-detail" class="fixed top-0 left-0 w-full h-screen flex justify-center items-center h-screen overflow-y-scroll">
    <div id="product-detail-container" class="bg-neutral-800 w-10/12 shadow shadow-neutral-600 p-2 flex flex-col md:flex-row">
        <img id="detail-image" src="src/products/" class="w-10/12 mx-auto md:w-3/6 lg:w-2/6" alt="">
        <div class="md:w-3/6 lg:w-4/6">
        <h1 id="detail-name" class="font-black text-xl sm:text-2xl md:text-3xl"></h1>
        <h3>Rp. <span id="detail-price"></span></h3>
        <p id="detail-description" class="text-[12px] text-neutral-400 mt-1"></p>
        <table class="text-[11px] sm:text-[13px] mt-5 border-separate border-spacing-x-1 border-spacing-y-2">
            <tr>
                <td>Bahan</td>
                <td>: <span id="detail-material"></span></td>
            </tr>
            <tr>
                <td>Stok</td>
                <td>: <span id="detail-stock"></span></td>
            </tr>
            <tr>
                <td>Berat</td>
                <td>: <span id="detail-weight"></span> Gram</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>: <span id="detail-category"></span> </td>
            </tr>
        </table>
    </div>
    </div>
</div>

<script>
    var modal = document.getElementById('product-detail')
    window.onclick = function (event) {
        if (event.target == modal) {
            $('#product-detail').removeClass('active')
            $('#product-detail-place').remove()
            $('body').removeClass('modal-open')
        }
    }
</script>