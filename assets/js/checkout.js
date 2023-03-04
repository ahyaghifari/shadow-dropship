$(document).ready(function () {

    const allTotal = async () => {
        var subtotalProduct = parseInt($('.subtotal-product').html())
        var shippingCost = parseInt($('.shipping-cost').html())
        if (subtotalProduct != 0 && shippingCost != 0) {
            var total = subtotalProduct + shippingCost;
            $('.all-total').html(total);
            $('#all_total').val(total);
        } else {
            return;
        }
    }
    
    allTotal();

    const productTotals = async () => {
        var p = [];
        var total = 0;
        $('.product-totals').each(function (i, obj) {
            gettotal = parseInt(obj.innerHTML);
            p.push(gettotal);
        })
        p.forEach(element => {
            total += element  
        });
        var w = [];
        var total_weight = 0;
        $('.weight-totals').each(function (i, obj) {
            gettotal = parseInt(obj.innerHTML);
            w.push(gettotal);
        })
        w.forEach(element => {
            total_weight += element  
        });
        $('.subtotal-product').html(total);
        $('.checkout-total_weight').html(total_weight/1000);
    }

    productTotals();

    $.ajax({
        type: "GET",
        url: "checkout.php",
        data: {
            province: true
        },
        dataType:"json",
        success: function (response) {
            var province = response.rajaongkir.results;
            var select = $('#receiver-province');
            $.each(province, function (i, val) {  
                var option = $('<option></option>').val(val.province).text(val.province).attr('data-id', val.province_id)
                select.append(option);
            });
        }
    });
    $('#receiver-province').change(function () {
        $('#receiver-city option').remove()
        if ($(this).val() == "") {
            $('#receiver-city').attr('disabled', 'disabled')
            $('#receiver-city').append($('<option></option>').val("").text("------"))
            $('#shipping-type option').remove();
            $('#shipping-type').append($('<option></option>').val("").text("------"));
            $('#shipping-cost').val("");
            $('.shipping-cost').html("0");
            $('#shipping-weight').val("");
            $('.all-total').html("0");
            $('#all_total').val("");
            allTotal();
            return;
        }
        var selectedProvince = $(this).find('option:selected');
        var id = selectedProvince.data('id').toString();
        $('#receiver-city').removeAttr('disabled');

        $.ajax({
            type: "GET",
            url: "checkout.php",
            data: {
                city: true,
                id: id,
            },
            dataType: "json",
            success: function (response) {
                var cities = response.rajaongkir.results;
                var select = $('#receiver-city');
                select.append($('<option></option>').val("").text("------"))
                $.each(cities, function (i, val) {  
                    var option = $('<option></option>').val(val.city_name).text(val.city_name).attr('data-id', val.city_id)
                    select.append(option);
                });
            }
        });
    })
    $('#receiver-city').change(function () {
        var selectedCity = $(this).find('option:selected');
        var id = selectedCity.data('id').toString();
        var weight = $('.checkout-total_weight').html()*1000
        select = $('#shipping-type option').remove()
        $.ajax({
            type: "POST",
            url: "checkout.php",
            data: {
                shipping_cost: true,
                id: id,
                weight: weight
            },
            dataType: "json",
            success: function (response) {
                var cost = response.rajaongkir.results[0].costs;
                var select = $('#shipping-type');
                $.each(cost, function (i, val) {  
                    var option = $('<option></option>').val(val.service).text(val.service + "(Rp. " + val.cost[0].value + ") est. " + val.cost[0].etd + " hari").attr('data-cost', val.cost[0].value);
                    select.append(option);
                });
                $('#shipping-type').trigger('change');
            }
        });
    })
    $('#shipping-type').change(function () {
        var selectedCost = $(this).find('option:selected');
        var cost = selectedCost.data('cost');
        var shippingCost = parseInt($('.shipping-cost').html(cost)) 
        $('#shipping-cost').val(parseInt(cost));
        allTotal()
    })
});