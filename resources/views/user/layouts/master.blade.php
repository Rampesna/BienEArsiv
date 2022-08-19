<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title'){{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <meta name="viewport" content="width=device-width, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

    @if(auth()->user()->theme() == 1)
        <link id="themePlugin" href="{{ asset('assets/plugins/global/plugins.dark.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link id="themeBundle" href="{{ asset('assets/css/style.dark.bundle.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link id="themePlugin" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link id="themeBundle" href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    @endif
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

    @yield('customStyles')

</head>

<body id="kt_body" class="header-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">

<div id="loader"></div>

@include('user.layouts.modals.quickActions')
@include('user.layouts.modals.quickActionsNewCompany')
@include('user.layouts.modals.quickActionsNewPayment')
@include('user.layouts.modals.quickActionsNewCollection')
@include('user.layouts.modals.quickActionsNewEarn')
@include('user.layouts.modals.quickActionsNewExpense')

<div class="d-flex flex-column flex-root" id="rootDocument">
    <div class="page d-flex flex-row flex-column-fluid">

        @include('user.layouts.sidebar')
        @include('user.layouts.body')

    </div>
</div>

<div class="hideIfMobile">
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
            </svg>
        </span>
    </div>
</div>

<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/jquery.touchSwipe.js') }}"></script>

<script src="{{ asset('assets/js/custom.js?v=v3.7') }}"></script>

<script>

    var token = 'Bearer {{ auth()->user()->apiToken() }}';
    var toggleDarkTheme = $('#toggleDarkTheme');
    var QuickActionsButton = $('#QuickActionsButton');

    var quick_actions_new_payment_company_id = $('#quick_actions_new_payment_company_id');
    var quick_actions_new_payment_safebox_id = $('#quick_actions_new_payment_safebox_id');

    var quick_actions_new_collection_company_id = $('#quick_actions_new_collection_company_id');
    var quick_actions_new_collection_safebox_id = $('#quick_actions_new_collection_safebox_id');

    var quick_actions_new_earn_safebox_id = $('#quick_actions_new_earn_safebox_id');
    var quick_actions_new_earn_category_id = $('#quick_actions_new_earn_category_id');

    var quick_actions_new_expense_safebox_id = $('#quick_actions_new_expense_safebox_id');
    var quick_actions_new_expense_category_id = $('#quick_actions_new_expense_category_id');

    var QuickActionsNewCompanyButton = $('#QuickActionsNewCompanyButton');
    var QuickActionsNewPaymentButton = $('#QuickActionsNewPaymentButton');
    var QuickActionsNewCollectionButton = $('#QuickActionsNewCollectionButton');
    var QuickActionsNewEarnButton = $('#QuickActionsNewEarnButton');
    var QuickActionsNewExpenseButton = $('#QuickActionsNewExpenseButton');

    toggleDarkTheme.change(function () {
        $('#loader').fadeIn(250);
        var theme = toggleDarkTheme.is(':checked') ? 1 : 0;
        $.ajax({
            type: 'post',
            url: '{{ route('api.user.updateTheme') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                theme: theme
            },
            success: function () {
                location.reload();
            },
            error: function (error) {
                console.log(error);
                toastr.error('Tema Değiştirilirken Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
                $('#loader').fadeOut(250);
            }
        });
    });

    QuickActionsButton.click(function () {
        $('#QuickActionsModal').modal('show');
    });

    function quickActionsNewCompany() {
        $('#QuickActionsModal').modal('hide');
        $('#QuickActionsNewCompanyModal').modal('show');
    }

    function quickActionsNewPayment() {
        $('#QuickActionsModal').modal('hide');
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                quick_actions_new_payment_company_id.empty();
                $.each(response.response, function (i, company) {
                    quick_actions_new_payment_company_id.append($('<option>', {
                        value: company.id,
                        text: company.title
                    }));
                });
                quick_actions_new_payment_company_id.val('').select2();
                $.ajax({
                    type: 'get',
                    url: '{{ route('api.user.safebox.all') }}',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token
                    },
                    data: {},
                    success: function (response) {
                        quick_actions_new_payment_safebox_id.empty();
                        $.each(response.response, function (i, safebox) {
                            quick_actions_new_payment_safebox_id.append($('<option>', {
                                value: safebox.id,
                                text: safebox.name
                            }));
                        });
                        quick_actions_new_payment_safebox_id.val('').select2();
                        $('#QuickActionsNewPaymentModal').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error('Kasa & Banka Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
                    }
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Cari Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
            }
        });
    }

    function quickActionsNewCollection() {
        $('#QuickActionsModal').modal('hide');
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                quick_actions_new_collection_company_id.empty();
                $.each(response.response, function (i, company) {
                    quick_actions_new_collection_company_id.append($('<option>', {
                        value: company.id,
                        text: company.title
                    }));
                });
                quick_actions_new_collection_company_id.val('').select2();
                $.ajax({
                    type: 'get',
                    url: '{{ route('api.user.safebox.all') }}',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token
                    },
                    data: {},
                    success: function (response) {
                        quick_actions_new_collection_safebox_id.empty();
                        $.each(response.response, function (i, safebox) {
                            quick_actions_new_collection_safebox_id.append($('<option>', {
                                value: safebox.id,
                                text: safebox.name
                            }));
                        });
                        quick_actions_new_collection_safebox_id.val('').select2();
                        $('#QuickActionsNewCollectionModal').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error('Kasa & Banka Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
                    }
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Cari Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
            }
        });
    }

    function quickActionsNewEarn() {
        $('#QuickActionsModal').modal('hide');
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                quick_actions_new_earn_safebox_id.empty();
                $.each(response.response, function (i, safebox) {
                    quick_actions_new_earn_safebox_id.append($('<option>', {
                        value: safebox.id,
                        text: safebox.name
                    }));
                });
                quick_actions_new_earn_safebox_id.val('').select2();
                $.ajax({
                    type: 'get',
                    url: '{{ route('api.user.customerTransactionCategory.all') }}',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token
                    },
                    data: {},
                    success: function (response) {
                        quick_actions_new_earn_category_id.empty();
                        $.each(response.response, function (i, category) {
                            quick_actions_new_earn_category_id.append($('<option>', {
                                value: category.id,
                                text: category.name
                            }));
                        });
                        quick_actions_new_earn_category_id.val('').select2();
                        $('#QuickActionsNewEarnModal').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error('Gelir & Gider Kategori Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
                    }
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kasa & Banka Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
            }
        });
    }

    function quickActionsNewExpense() {
        $('#QuickActionsModal').modal('hide');
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                quick_actions_new_expense_safebox_id.empty();
                $.each(response.response, function (i, safebox) {
                    quick_actions_new_expense_safebox_id.append($('<option>', {
                        value: safebox.id,
                        text: safebox.name
                    }));
                });
                quick_actions_new_expense_safebox_id.val('').select2();
                $.ajax({
                    type: 'get',
                    url: '{{ route('api.user.customerTransactionCategory.all') }}',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token
                    },
                    data: {},
                    success: function (response) {
                        quick_actions_new_expense_category_id.empty();
                        $.each(response.response, function (i, category) {
                            quick_actions_new_expense_category_id.append($('<option>', {
                                value: category.id,
                                text: category.name
                            }));
                        });
                        quick_actions_new_expense_category_id.val('').select2();
                        $('#QuickActionsNewExpenseModal').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error('Gelir & Gider Kategori Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
                    }
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kasa & Banka Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
            }
        });
    }

    QuickActionsNewCompanyButton.click(function () {
        var types = $('#quick_actions_new_company_types').val();
        var tax_number = $('#quick_actions_new_company_tax_number').val();
        var tax_office = $('#quick_actions_new_company_tax_office').val();
        var title = $('#quick_actions_new_company_title').val();
        var manager_name = $('#quick_actions_new_company_manager_name').val();
        var manager_surname = $('#quick_actions_new_company_manager_surname').val();
        var email = $('#quick_actions_new_company_email').val();
        var phone = $('#quick_actions_new_company_phone').val();
        var country_id = $('#quick_actions_new_company_country_id').val();
        var province_id = $('#quick_actions_new_company_province_id').val();
        var district_id = $('#quick_actions_new_company_district_id').val();
        var postCode = $('#quick_actions_new_company_postcode').val();
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
                    $('#QuickActionsNewCompanyModal').modal('hide');
                    toastr.success('Cari Başarıyla Oluşturuldu.');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Cari Oluşturulurken Serviste Bir Hata Oluştu.');
                }
            });
        }
    });

    QuickActionsNewPaymentButton.click(function () {
        var companyId = quick_actions_new_payment_company_id.val();
        var datetime = $('#quick_actions_new_payment_datetime').val();
        var amount = $('#quick_actions_new_payment_amount').val();
        var safeboxId = quick_actions_new_payment_safebox_id.val();
        var description = $('#quick_actions_new_payment_description').val();

        if (!companyId) {
            toastr.warning('Cari Seçmediniz!');
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
                    $('#QuickActionsNewPaymentModal').modal('hide');
                    toastr.success('Ödeme Başarılı');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
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

    QuickActionsNewCollectionButton.click(function () {
        var companyId = quick_actions_new_collection_company_id.val();
        var datetime = $('#quick_actions_new_collection_datetime').val();
        var amount = $('#quick_actions_new_collection_amount').val();
        var safeboxId = quick_actions_new_collection_safebox_id.val();
        var description = $('#quick_actions_new_collection_description').val();

        if (!companyId) {
            toastr.warning('Cari Seçmediniz!');
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
                    $('#QuickActionsNewCollectionModal').modal('hide');
                    toastr.success('Tahsilat Başarılı');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
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

    QuickActionsNewEarnButton.click(function () {
        var safeboxId = quick_actions_new_earn_safebox_id.val();
        var datetime = $('#quick_actions_new_earn_date').val();
        var amount = $('#quick_actions_new_earn_amount').val();
        var categoryId = quick_actions_new_earn_category_id.val();
        var description = $('#quick_actions_new_earn_description').val();

        if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!categoryId) {
            toastr.warning('Kategori Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: null,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 3,
                    categoryId: categoryId,
                    receiptNumber: '',
                    description: description,
                    safeboxId: safeboxId,
                    direction: 0,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#QuickActionsNewEarnModal').modal('hide');
                    toastr.success('Gelir Oluşturuldu');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Sistemsel Bir Hata Oluştu!');
                }
            });
        }
    });

    QuickActionsNewExpenseButton.click(function () {
        var safeboxId = quick_actions_new_expense_safebox_id.val();
        var datetime = $('#quick_actions_new_expense_date').val();
        var amount = $('#quick_actions_new_expense_amount').val();
        var categoryId = quick_actions_new_expense_category_id.val();
        var description = $('#quick_actions_new_expense_description').val();

        if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!categoryId) {
            toastr.warning('Kategori Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: null,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 4,
                    categoryId: categoryId,
                    receiptNumber: '',
                    description: description,
                    safeboxId: safeboxId,
                    direction: 1,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#QuickActionsNewExpenseModal').modal('hide');
                    toastr.success('Gider Oluşturuldu');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Sistemsel Bir Hata Oluştu!');
                }
            });
        }
    });

</script>

@yield('customScripts')

</body>
</html>
