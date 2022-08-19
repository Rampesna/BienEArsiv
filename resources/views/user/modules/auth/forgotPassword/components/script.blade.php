<script>

    var ResetPasswordButton = $('#ResetPasswordButton');

    ResetPasswordButton.click(function () {
        var email = $('#email').val();

        if (!email) {
            toastr.warning('E-posta Adresi Boş Olamaz!');
        } else {
            $('#loader').show();
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.sendPasswordResetEmail') }}',
                headers: {
                    'Accept': 'application/json',
                },
                data: {
                    email: email,
                },
                success: function (response) {
                    toastr.success('Şifre Sıfırlama Linki Mail Adresinize Gönderildi!');
                    $('#email').val('');
                    $('#loader').hide();
                },
                error: function (error) {
                    console.log(error);
                    $('#loader').hide();
                    if (parseInt(error.status) === 404) {
                        toastr.error('Bu E-posta Adresi ile Kayıtlı Bir Kullanıcı Bulunamadı!');
                    } else if (parseInt(error.status) === 406) {
                        toastr.error('Bu E-posta Adresi ile Daha Önce Şifre Sıfırlama İsteği Gönderilmiş!');
                    } else {
                        toastr.error('E-posta Sıfırlama Servisinde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
                    }
                }
            });
        }
    });

</script>
