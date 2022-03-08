<script>

    var defaultImage = '{{ asset('assets/media/svg/files/upload.svg') }}';
    var defaultAsset = '{{ asset('') }}';

    var customerStamp = $('#customerStamp');
    var customerLogo = $('#customerLogo');

    var customerStampInput = $('#customerStampInput');
    var customerLogoInput = $('#customerLogoInput');

    function getCustomer() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customer.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                customerStamp.attr('src', response.response.stamp ? defaultAsset + response.response.stamp : defaultImage);
                customerLogo.attr('src', response.response.logo ? defaultAsset + response.response.logo : defaultImage);
            },
            error: function (error) {
                console.log(error);
                toastr.error('Firma Verileri Alınırken Serviste Bir Hata Oluştu!');
            }
        });
    }

    getCustomer();

    customerStamp.click(function () {
        customerStampInput.trigger('click');
    });

    customerLogo.click(function () {
        customerLogoInput.trigger('click');
    });

    customerStampInput.change(function () {
        var data = new FormData();
        data.append('stamp', this.files[0]);
        $.ajax({
            processData: false,
            contentType: false,
            type: 'post',
            url: '{{ route('api.user.customer.updateStamp') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: data,
            success: function () {
                getCustomer();
            },
            error: function (error) {
                console.log(error);
                toastr.error('Firma Kaşesi Güncellenirken Serviste Bir Hata Oluştu!');
            }
        });
    });

    customerLogoInput.change(function () {
        var data = new FormData();
        data.append('logo', this.files[0]);
        $.ajax({
            processData: false,
            contentType: false,
            type: 'post',
            url: '{{ route('api.user.customer.updateLogo') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: data,
            success: function () {
                getCustomer();
            },
            error: function (error) {
                console.log(error);
                toastr.error('Firma Logosu Güncellenirken Serviste Bir Hata Oluştu!');
            }
        });
    });

</script>
