$(document).ready(function () {
    $('.nav-user-toggle').click(function () {
        $('#nav-user-list').toggleClass('active');
    })
    $('#cancel-order-btn').click(function(e){
        e.preventDefault();
        var conf = confirm("Kamu yakin untuk membatalkan pesanan ini?");
        if (conf == true) {
            $('#form-cancelorder').submit();
        } else {
            return false;
        }
    })

    $('#form-changeprofile').validate({
          rules: {
            username: {
              required: true,
              minlength: 3,
              maxlength: 16,
              pattern: /^[a-z0-9_-]+$/
            }
          },
          messages: {
            username: {
              required: 'Username harus diisi',
              minlength: 'Username setidaknya memiliki 3 karakter',
              maxlength: 'Username hanya sampai 16 karakter',
              pattern: 'Username hanya bisa berisi huruf kecil, angka, underscore dan strip'
              },
          },
          errorClass: 'is-invalid',
        errorElement: 'div',  
        });

});