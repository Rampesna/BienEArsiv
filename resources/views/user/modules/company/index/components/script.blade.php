<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

<script>

    var allCountries = [];
    var allProvinces = [];
    var allDistricts = [];

    var companies = $('#companies');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var create_company_types = $('#create_company_types');
    var create_company_country_id = $('#create_company_country_id');
    var create_company_province_id = $('#create_company_province_id');
    var create_company_district_id = $('#create_company_district_id');

    var CreateCompanyButton = $('#CreateCompanyButton');


    function createCompany() {
        create_company_types.val([]).select2();
        $('#create_company_tax_number').val('');
        $('#create_company_tax_office').val('');
        $('#create_company_title').val('');
        $('#create_company_manager_name').val('');
        $('#create_company_manager_surname').val('');
        $('#create_company_email').val('');
        $('#create_company_phone').val('');
        create_company_country_id.empty().append($('<option>', {
            value: null,
            text: null
        }));
        $.each(allCountries, function (i, country) {
            create_company_country_id.append($('<option>', {
                value: country.id,
                text: country.name
            }));
        });
        create_company_province_id.empty();
        create_company_district_id.empty();
        $('#create_company_postcode').val('');
        $('#CreateCompanyModal').modal('show');
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getCompanies();
    }

    function getCompanies() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();
        var keyword = $('#filter_company_keyword').val();
        var accountType = $('#filter_company_account_type').val();
        var balanceType = $('#filter_company_balance_type').val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                pageIndex: pageIndex,
                pageSize: pageSize,
                keyword: keyword,
                accountType: accountType,
                balanceType: balanceType
            },
            success: function (response) {
                companies.empty();
                $.each(response.response.companies, function (i, company) {
                    companies.append(`
                    <tr class="cursor-pointer">
                        <td onclick="goToDetail(${company.id})">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="black"/>
                                    <path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="black"/>
                                </svg>
                            </span>
                            <span class="text-dark">${company.title}</span>${company.tax_number ? `<br><small>${company.tax_number}</small>` : ''}
                        </td>
                        <td class="hideIfMobile" onclick="goToDetail(${company.id})">${company.email ?? ''}</td>
                        <td class="hideIfMobile" onclick="goToDetail(${company.id})">${company.phone ?? ''}</td>
                        <td class="text-end" onclick="goToDetail(${company.id})">${reformatNumberToMoney(company.balance)} ₺</td>
                        <td class="text-end">
                            <button class="btn btn-icon btn-success" title="Ekstre"><i class="fas fa-arrows-alt-h"></i></button>
                            <button class="btn btn-icon btn-primary" title="Mutabakat"><i class="fas fa-file-signature"></i></button>
                        </td>
                    </tr>
                    `);
                });

                if (response.response.totalCount <= (pageIndex + 1) * pageSize) {
                    pageUpButton.attr('disabled', true);
                } else {
                    pageUpButton.attr('disabled', false);
                }

                checkScreen();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function goToDetail(companyId) {
        window.location.href = `{{ route('web.user.company.detail') }}/${companyId}`;
    }

    function getCountries() {
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.country.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                allCountries = response.response;
            },
            error: function (error) {
                console.log(error);
                toastr.error('Ülke Listesi Alınırken Serviste Hata Oluştu.');
            }
        });
    }

    function getProvinces() {
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.province.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                allProvinces = response.response;
            },
            error: function (error) {
                console.log(error);
                toastr.error('Şehir Listesi Alınırken Serviste Hata Oluştu.');
            }
        });
    }

    function getDistricts() {
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.district.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                allDistricts = response.response;
            },
            error: function (error) {
                console.log(error);
                toastr.error('İlçe Listesi Alınırken Serviste Hata Oluştu.');
            }
        });
    }

    getCompanies();
    getCountries();
    getProvinces();
    getDistricts();

    pageUpButton.click(function () {
        changePage(parseInt(page.html()) + 1);
    });

    pageDownButton.click(function () {
        changePage(parseInt(page.html()) - 1);
    });

    pageSizeSelector.change(function () {
        changePage(1);
    });

    $('body').on('contextmenu', function (e) {
        var top = e.pageY - 10;
        var left = e.pageX - 10;

        $("#context-menu").css({
            display: "block",
            top: top,
            left: left
        });

        return false;
    }).on("click", function () {
        $("#context-menu").hide();
    }).on('focusout', function () {
        $("#context-menu").hide();
    });

    $(document).delegate('.filterInput', 'change keyup', function () {
        getCompanies();
    });

    create_company_country_id.change(function () {
        create_company_province_id.empty().append($('<option>', {
            value: null,
            text: null
        }));
        create_company_district_id.empty();
        $.each(allProvinces, function (i, province) {
            if (parseInt(create_company_country_id.val()) === parseInt(province.country_id)) {
                create_company_province_id.append($('<option>', {
                    value: province.id,
                    text: province.name
                }));
            }
        });
    });

    create_company_province_id.change(function () {
        create_company_district_id.empty().append($('<option>', {
            value: null,
            text: null
        }));
        $.each(allDistricts, function (i, district) {
            if (parseInt(create_company_province_id.val()) === parseInt(district.province_id)) {
                create_company_district_id.append($('<option>', {
                    value: district.id,
                    text: district.name
                }));
            }
        });
    });

    CreateCompanyButton.click(function () {
        var types = create_company_types.val();
        var tax_number = $('#create_company_tax_number').val();
        var tax_office = $('#create_company_tax_office').val();
        var title = $('#create_company_title').val();
        var manager_name = $('#create_company_manager_name').val();
        var manager_surname = $('#create_company_manager_surname').val();
        var email = $('#create_company_email').val();
        var phone = $('#create_company_phone').val();
        var country_id = $('#create_company_country_id').val();
        var province_id = $('#create_company_province_id').val();
        var district_id = $('#create_company_district_id').val();
        var postCode = $('#create_company_postcode').val();
        var isCustomer = $.inArray('1', types) !== -1 ? 1 : 0;
        var isSupplier = $.inArray('2', types) !== -1 ? 1 : 0;

        if (!title) {
            toastr.warning('Firma Adı Boş Olamaz.');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.company.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    taxNumber: tax_number,
                    taxOffice: tax_office,
                    title: title,
                    managerName: manager_name,
                    managerSurname: manager_surname,
                    email: email,
                    phone: phone,
                    countryId: country_id,
                    provinceId: province_id,
                    districtId: district_id,
                    postCode: postCode,
                    isCustomer: isCustomer,
                    isSupplier: isSupplier
                },
                success: function () {
                    $('#CreateCompanyModal').modal('hide');
                    toastr.success('Cari Başarıyla Oluşturuldu.');
                    getCompanies();
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Cari Oluşturulurken Serviste Bir Hata Oluştu.');
                }
            });
        }
    });


</script>
