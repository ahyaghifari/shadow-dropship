$(document).ready(function () {
    $('#form-register').validate({
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
              password: {
                required: 'Password harus diisi'
            },
            confirm_password: {
                  required: 'Password harus diisi'
              }
          },
          errorClass: 'is-invalid',
        errorElement: 'div',  
        });

});