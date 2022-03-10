<script>

    var UpdateCustomerButton = $('#UpdateCustomerButton');

    var customer_province_id = $('#customer_province_id');
    var customer_district_id = $('#customer_district_id');

    function getProvinces() {
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.province.getByCountryId') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                countryId: 223
            },
            success: function (response) {
                customer_province_id.empty();
                $.each(response.response, function (i, province) {
                    customer_province_id.append(`<option value="${province.id}">${province.name}</option>`);
                });
                customer_province_id.val('').select2();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getDistricts() {
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.district.getByProvinceId') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                provinceId: customer_province_id.val()
            },
            success: function (response) {
                customer_district_id.empty();
                $.each(response.response, function (i, district) {
                    customer_district_id.append(`<option value="${district.id}">${district.name}</option>`);
                });
                customer_district_id.val('').select2();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getCustomer() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customer.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: '{{ auth()->user()->getCustomerId() }}'
            },
            success: function (response) {
                console.log(response);
                $('#customer_title').val(response.response.title);
                $('#customer_tax_office').val(response.response.tax_office);
                $('#customer_tax_number').val(response.response.tax_number);
                $('#customer_gib_password').val(response.response.gib_password);
                $('#customer_phone').val(response.response.phone);
                $('#customer_email').val(response.response.email);
                $('#customer_address').val(response.response.address);
                customer_province_id.val(response.response.province_id).select2();
                getDistricts();
                customer_district_id.val(response.response.district_id).select2();
            },
            error: function (error) {
                console.log(error);
                toastr.error('Firma Bilgileri Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function initializePage() {
        getProvinces();
        getCustomer();
    }

    initializePage();

    customer_province_id.change(function () {
        getDistricts();
    });

    UpdateCustomerButton.click(function () {
        var title = $('#customer_title').val();
        var taxOffice = $('#customer_tax_office').val();
        var taxNumber = $('#customer_tax_number').val();
        var gibPassword = $('#customer_gib_password').val();
        var phone = $('#customer_phone').val();
        var email = $('#customer_email').val();
        var address = $('#customer_address').val();
        var provinceId = customer_province_id.val();
        var districtId = customer_district_id.val();

        $.ajax({
            type: 'put',
            url: '{{ route('api.user.customer.update') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: '{{ auth()->user()->getCustomerId() }}',
                title: title,
                taxOffice: taxOffice,
                taxNumber: taxNumber,
                gibPassword: gibPassword,
                phone: phone,
                email: email,
                address: address,
                provinceId: provinceId,
                districtId: districtId
            },
            success: function () {
                toastr.success('Başarıyla Güncellendi!');
                getCustomer();
            },
            error: function (error) {
                console.log(error);
                toastr.error('Firma Bilgileri Güncellenirken Serviste Bir Hata Oluştu.');
            }
        });
    });

</script>
