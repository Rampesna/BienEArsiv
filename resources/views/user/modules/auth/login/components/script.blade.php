<script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>

<script>

    var LoginButton = $('#LoginButton');

    LoginButton.click(function () {
        var email = $('#email').val();
        var password = $('#password').val();

        if (!email) {
            toastr.warning('E-posta Adresinizi Giriniz!');
        } else if (!password) {
            toastr.warning('Şifrenizi Giriniz!')
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.login') }}',
                headers: {
                    'Accept': 'application/json',
                },
                data: {
                    email: email,
                    password: password,
                },
                success: function (response) {
                    window.location.href = `{{ route('web.user.authentication.oAuth') }}?oAuth=${response.response.oauth}`;
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404) {
                        toastr.error('Bilgilerinizi Kontrol Ediniz!');
                    } else {
                        toastr.error('Giriş Yapılırken Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

</script>
