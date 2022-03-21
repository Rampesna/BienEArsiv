<script>

    var customer_province_id = $('#customer_province_id');
    var customer_district_id = $('#customer_district_id');

    var CompleteWizardButton = $('#CompleteWizardButton');
    WizardStepperSelector = document.querySelector("#WizardStepper");
    WizardStepper = new KTStepper(WizardStepperSelector);

    WizardStepper.on("kt.stepper.changed", (function (e) {

    }));

    WizardStepper.on("kt.stepper.next", (function (e) {
        e.goNext();
        KTUtil.scrollTop();
    }));

    WizardStepper.on("kt.stepper.previous", (function (e) {
        e.goPrevious();
        KTUtil.scrollTop();
    }));

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

    function initializePage() {
        getProvinces();
    }

    initializePage();

    customer_province_id.change(function () {
        getDistricts();
    });

    CompleteWizardButton.click(function (e) {
        var title = $('#customer_title').val();
        var taxOffice = $('#customer_tax_office').val();
        var taxNumber = $('#customer_tax_number').val();
        var gibPassword = $('#customer_gib_password').val();
        var phone = $('#customer_phone').val();
        var email = $('#customer_email').val();
        var address = $('#customer_address').val();
        var provinceId = customer_province_id.val();
        var districtId = customer_district_id.val();

        if (!title) {
            toastr.warning('Firma Ünvanı Boş Olamaz!');
            WizardStepper.goTo(1);
        } else if (!email) {
            toastr.warning('Firma E-posta Adresi Boş Olamaz!');
            WizardStepper.goTo(1);
        } else if (!phone) {
            toastr.warning('Firma Telefon Numarası Boş Olamaz!');
            WizardStepper.goTo(1);
        } else if (!address) {
            toastr.warning('Firma Adresi Boş Olamaz!');
            WizardStepper.goTo(1);
        } else if (!taxNumber) {
            toastr.warning('Vergi Numarası Boş Olamaz!');
            WizardStepper.goTo(2);
        } else if (!taxOffice) {
            toastr.warning('Vergi Dairesi Boş Olamaz!');
            WizardStepper.goTo(2);
        } else if (!gibPassword) {
            toastr.warning('Gib Portalı Şifresi Boş Olamaz!');
            WizardStepper.goTo(2);
        } else {
            $('#loader').fadeIn(250);
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.wizard.complete') }}',
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
                    toastr.success('Aktivasyon İşleminiz Başarıyla Gerçekleştirildi.');
                    setTimeout(function () {
                        window.location.href = '{{ route('web.user.dashboard.index') }}';
                    }, 2000);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Aktivasyon İşleminiz Gerçekleştirilirken Serviste Bir Sorun Oluştu. Lütfen Bilgilerinizi Kontrol Ediniz.');
                    $('#loader').fadeOut(250);
                }
            });
        }
    });

</script>
