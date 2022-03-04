<script>

    var allCountries = [];
    var allProvinces = [];
    var allDistricts = [];

    var transactions = $('#transactions');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var new_collection_safebox_id = $('#new_collection_safebox_id');
    var new_payment_safebox_id = $('#new_payment_safebox_id');

    var update_company_types = $('#update_company_types');
    var update_company_country_id = $('#update_company_country_id');
    var update_company_province_id = $('#update_company_province_id');
    var update_company_district_id = $('#update_company_district_id');

    var NewCreditButton = $('#NewCreditButton');
    var NewDebitButton = $('#NewDebitButton');
    var NewCollectionButton = $('#NewCollectionButton');
    var NewPaymentButton = $('#NewPaymentButton');
    var UpdateCompanyButton = $('#UpdateCompanyButton');
    var DeleteCompanyButton = $('#DeleteCompanyButton');

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getTransactions();
    }

    function getCompanyById() {
        var id = '{{ $id }}';
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id
            },
            success: function (response) {
                $('#subheaderCompanyTitleSpan').html(`${response.response.tax_number ? `#${response.response.tax_number} - ` : ``}${response.response.title}`);
                $('#companyTitle').html(`${response.response.title}`);
                $('#companyTaxNumber').html(`${response.response.tax_number ?? '--'}`);
                $('#companyTaxOffice').html(`${response.response.tax_office ?? '--'}`);
                $('#companyEmail').html(`${response.response.email ?? '--'}`);
                $('#companyPhone').html(`${response.response.phone ?? '--'}`);
                $('#companyAddress').html(`${response.response.address ?? ''}`);
                $('#balanceSpan').html(`${response.response.balance ? reformatNumberToMoney(response.response.balance) : '0.00'} ₺`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getSafeboxes() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                new_collection_safebox_id.empty();
                new_payment_safebox_id.empty();
                new_collection_safebox_id.append(`<option value="" selected disabled></option>`);
                new_payment_safebox_id.append(`<option value="" selected disabled></option>`);
                $.each(response.response, function (i, safebox) {
                    new_collection_safebox_id.append(`<option value="${safebox.id}">${safebox.name}</option>`);
                    new_payment_safebox_id.append(`<option value="${safebox.id}">${safebox.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kasa & Banka Listesi Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function getTransactions() {
        var companyId = '{{ $id }}';
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transaction.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                pageIndex: pageIndex,
                pageSize: pageSize,
                companyId: companyId,
            },
            success: function (response) {
                transactions.empty();
                $.each(response.response.transactions, function (i, transaction) {
                    var icon = transaction.direction === 0 ?
                        `
                        <span class="svg-icon svg-icon-success svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M13.4 10L5.3 18.1C4.9 18.5 4.9 19.1 5.3 19.5C5.7 19.9 6.29999 19.9 6.69999 19.5L14.8 11.4L13.4 10Z" fill="black"/>
                                <path opacity="0.3" d="M19.8 16.3L8.5 5H18.8C19.4 5 19.8 5.4 19.8 6V16.3Z" fill="black"/>
                            </svg>
                        </span>
                        ` :
                        `
                        <span class="svg-icon svg-icon-danger svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.4 14.8L19.5 6.69999C19.9 6.29999 19.9 5.7 19.5 5.3C19.1 4.9 18.5 4.9 18.1 5.3L10 13.4L11.4 14.8Z" fill="black"/>
                                <path opacity="0.3" d="M5 8.5L16.3 19.8H6C5.4 19.8 5 19.4 5 18.8V8.5Z" fill="black"/>
                            </svg>
                        </span>
                        `;
                    transactions.append(`
                    <tr>
                        <td>
                            ${icon}
                            ${reformatDatetimeTo_DD_MM_YYYY_WithDot(transaction.datetime)}
                            <br>
                            ${transaction.type ? `<span class="badge badge-light-${transaction.type.class} ms-9">${transaction.type.name}</span>` : ``}
                        </td>
                        <td class="hideIfMobile">
                            ${transaction.receipt_number ?? ``}
                        </td>
                        <td class="text-end">
                            ${reformatNumberToMoney(transaction.amount)} ₺
                        </td>
                        <td class="text-end">
                            <button class="btn btn-icon btn-sm btn-primary" title="Düzenle"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-icon btn-sm btn-danger" title="Mutabakat"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    `);
                });

                if (response.response.totalCount <= (pageIndex + 1) * pageSize) {
                    pageUpButton.attr('disabled', true);
                } else {
                    pageUpButton.attr('disabled', false);
                }
            },
            error: function (error) {
                console.log(error);
                toastr.error('İşlemler Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function newCredit() {
        $('#new_credit_date').val('');
        $('#new_credit_amount').val('');
        $('#new_credit_description').val('');
        $('#NewCreditModal').modal('show');
    }

    function newDebit() {
        $('#new_debit_date').val('');
        $('#new_debit_amount').val('');
        $('#new_debit_description').val('');
        $('#NewDebitModal').modal('show');
    }

    function newCollection() {
        getSafeboxes();
        $('#new_collection_datetime').val('');
        $('#new_collection_amount').val('');
        $('#new_collection_description').val('');
        $('#NewCollectionModal').modal('show');
    }

    function newPayment() {
        getSafeboxes();
        $('#new_payment_datetime').val('');
        $('#new_payment_amount').val('');
        $('#new_payment_description').val('');
        $('#NewPaymentModal').modal('show');
    }

    function updateCompany() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: '{{ $id }}'
            },
            success: function (response) {
                var types = [];
                if (response.response.is_customer === 1) types.push('1');
                if (response.response.is_supplier === 1) types.push('2');
                update_company_types.val(types).select2();
                $('#update_company_tax_number').val(response.response.tax_number);
                $('#update_company_tax_office').val(response.response.tax_office);
                $('#update_company_title').val(response.response.title);
                $('#update_company_manager_name').val(response.response.manager_name);
                $('#update_company_manager_surname').val(response.response.manager_surname);
                $('#update_company_email').val(response.response.email);
                $('#update_company_phone').val(response.response.phone);
                update_company_country_id.empty().append($('<option>', {
                    value: null,
                    text: null
                }));
                $.each(allCountries, function (i, country) {
                    update_company_country_id.append($('<option>', {
                        value: country.id,
                        text: country.name
                    }));
                });
                update_company_country_id.val(response.response.country_id).select2();
                update_company_country_id.trigger('change');
                update_company_province_id.val(response.response.province_id).select2();
                update_company_province_id.trigger('change');
                update_company_district_id.val(response.response.district_id).select2();
                $('#update_company_postcode').val(response.response.post_code);
            },
            error: function (error) {
                console.log(error);
            }
        });
        $('#UpdateCompanyModal').modal('show');
    }

    function deleteCompany() {
        $('#DeleteCompanyModal').modal('show');
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

    getCountries();
    getProvinces();
    getDistricts();
    getCompanyById();
    getTransactions();

    pageUpButton.click(function () {
        changePage(parseInt(page.html()) + 1);
    });

    pageDownButton.click(function () {
        changePage(parseInt(page.html()) - 1);
    });

    pageSizeSelector.change(function () {
        changePage(1);
    });

    NewCreditButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_credit_date').val();
        var amount = $('#new_credit_amount').val();
        var description = $('#new_credit_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 5,
                    receiptNumber: '',
                    description: description,
                    safeboxId: null,
                    direction: 0,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#NewCreditModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

    NewDebitButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_debit_date').val();
        var amount = $('#new_debit_amount').val();
        var description = $('#new_debit_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 6,
                    receiptNumber: '',
                    description: description,
                    safeboxId: null,
                    direction: 1,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#NewDebitModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

    NewCollectionButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_collection_datetime').val();
        var amount = $('#new_collection_amount').val();
        var safeboxId = new_collection_safebox_id.val();
        var description = $('#new_collection_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 1,
                    receiptNumber: '',
                    description: description,
                    safeboxId: safeboxId,
                    direction: 0,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#NewCollectionModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

    NewPaymentButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_payment_datetime').val();
        var amount = $('#new_payment_amount').val();
        var safeboxId = new_payment_safebox_id.val();
        var description = $('#new_payment_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 2,
                    receiptNumber: '',
                    description: description,
                    safeboxId: safeboxId,
                    direction: 1,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#NewPaymentModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

    update_company_country_id.change(function () {
        update_company_province_id.empty().append($('<option>', {
            value: null,
            text: null
        }));
        update_company_district_id.empty();
        $.each(allProvinces, function (i, province) {
            if (parseInt(update_company_country_id.val()) === parseInt(province.country_id)) {
                update_company_province_id.append($('<option>', {
                    value: province.id,
                    text: province.name
                }));
            }
        });
    });

    update_company_province_id.change(function () {
        update_company_district_id.empty().append($('<option>', {
            value: null,
            text: null
        }));
        $.each(allDistricts, function (i, district) {
            if (parseInt(update_company_province_id.val()) === parseInt(district.province_id)) {
                update_company_district_id.append($('<option>', {
                    value: district.id,
                    text: district.name
                }));
            }
        });
    });

    UpdateCompanyButton.click(function () {
        var id = '{{ $id }}';
        var types = update_company_types.val();
        var tax_number = $('#update_company_tax_number').val();
        var tax_office = $('#update_company_tax_office').val();
        var title = $('#update_company_title').val();
        var manager_name = $('#update_company_manager_name').val();
        var manager_surname = $('#update_company_manager_surname').val();
        var email = $('#update_company_email').val();
        var phone = $('#update_company_phone').val();
        var country_id = $('#update_company_country_id').val();
        var province_id = $('#update_company_province_id').val();
        var district_id = $('#update_company_district_id').val();
        var postCode = $('#update_company_postcode').val();
        var isCustomer = $.inArray('1', types) !== -1 ? 1 : 0;
        var isSupplier = $.inArray('2', types) !== -1 ? 1 : 0;

        if (!title) {
            toastr.warning('Firma Adı Boş Olamaz.');
        } else {
            $.ajax({
                type: 'put',
                url: '{{ route('api.user.company.update') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    id: id,
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
                    $('#UpdateCompanyModal').modal('hide');
                    toastr.success('Başarıyla Güncellendi');
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Cari Güncellenirken Serviste Bir Hata Oluştu.');
                }
            });
        }
    });

    DeleteCompanyButton.click(function () {
        var companyId = '{{ $id }}';
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transaction.count') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                companyId: companyId
            },
            success: function (response) {
                if (response.response > 0) {
                    toastr.warning('İşlem Görmüş Cari Silinemez.');
                } else {
                    $.ajax({
                        type: 'get',
                        url: '{{ route('api.user.invoice.count') }}',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': token
                        },
                        data: {
                            companyId: companyId
                        },
                        success: function (response) {
                            if (response.response > 0) {
                                toastr.warning('İşlem Görmüş Cari Silinemez.');
                            } else {
                                $('#loader').fadeIn(250);
                                $('#DeleteCompanyModal').modal('hide');
                                $.ajax({
                                    type: 'delete',
                                    url: '{{ route('api.user.company.delete') }}',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Authorization': token
                                    },
                                    data: {
                                        id: companyId
                                    },
                                    success: function () {
                                        toastr.success('Başarıyla Silindi');
                                        window.location.href = '{{ route('web.user.company.index') }}';
                                    },
                                    error: function (error) {
                                        console.log(error);
                                        toastr.error('Cari Silinirken Serviste Bir Hata Oluştu.');
                                        $('#loader').fadeOut(250);
                                    }
                                });
                            }
                        },
                        error: function (error) {
                            console.log(error);
                            toastr.error('Cari Silinirken Serviste Bir Hata Oluştu.');
                        }
                    });
                }
            },
            error: function (error) {
                console.log(error);
                toastr.error('Cari Silinirken Serviste Bir Hata Oluştu.');
            }
        });
    });

</script>
