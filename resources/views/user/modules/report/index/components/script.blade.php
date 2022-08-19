<script>

    var company_extract_report_company_id = $('#company_extract_report_company_id');
    var company_transaction_report_company_id = $('#company_transaction_report_company_id');
    var company_transaction_report_type_id = $('#company_transaction_report_type_id');
    var transaction_report_direction = $('#transaction_report_direction');
    var transaction_report_safebox_id = $('#transaction_report_safebox_id');
    var transaction_report_category_id = $('#transaction_report_category_id');

    var CompanyExtractReportButton = $('#CompanyExtractReportButton');
    var CompanyTransactionReportButton = $('#CompanyTransactionReportButton');
    var TransactionReportButton = $('#TransactionReportButton');
    var EInvoiceOutboxReportButton = $('#EInvoiceOutboxReportButton');
    var EInvoiceInboxReportButton = $('#EInvoiceInboxReportButton');

    function companyBalanceStatusReport() {
        $('#loader').fadeIn(250);
        $.ajax({
            type: 'get',
{{--            url: '{{ route('api.user.company.report.balanceStatus') }}',--}}
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
{{--                var basePath = '{{ asset('') }}';--}}
                window.location.href = `${basePath + response.response}`;
                $('#loader').fadeOut(250);
            },
            error: function (error) {
                console.log(error);
                toastr.error('Rapor Oluşturulurken Serviste Bir Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyiniz.');
                $('#loader').fadeOut(250);
            }
        });
    }

    function companyExtractReport() {
        company_extract_report_company_id.val('');
        $('#company_extract_report_date').val('');
        $('#CompanyExtractReportModal').modal('show');
    }

    function companyTransactionReport() {
        company_transaction_report_company_id.val('');
        company_transaction_report_type_id.val('');
        $('#company_transaction_report_start_date').val('');
        $('#company_transaction_report_end_date').val('');
        transaction_report_direction.val('');
        $('#CompanyTransactionReportModal').modal('show');
    }

    function transactionReport() {
        transaction_report_safebox_id.val('');
        transaction_report_direction.val('');
        transaction_report_category_id.val('');
        $('#transaction_report_start_date').val('');
        $('#transaction_report_end_date').val('');
        $('#TransactionReportModal').modal('show');
    }

    function productReport() {
        $('#loader').fadeIn(250);
        $.ajax({
            type: 'get',
{{--            url: '{{ route('api.user.product.report.all') }}',--}}
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                var basePath = '{{ asset('') }}';
                window.location.href = `${basePath + response.response}`;
                $('#loader').fadeOut(250);
            },
            error: function (error) {
                console.log(error);
                toastr.error('Rapor Oluşturulurken Serviste Bir Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyiniz.');
                $('#loader').fadeOut(250);
            }
        });
    }

    function eInvoiceOutboxReport() {
        $('#e_invoice_outbox_report_start_date').val('');
        $('#e_invoice_outbox_report_end_date').val('');
        $('#EInvoiceOutboxReportModal').modal('show');
    }

    function eInvoiceInboxReport() {
        $('#e_invoice_inbox_report_start_date').val('');
        $('#e_invoice_inbox_report_end_date').val('');
        $('#EInvoiceInboxReportModal').modal('show');
    }

    function getCompanies() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                company_extract_report_company_id.empty();
                $.each(response.response, function (i, company) {
                    company_extract_report_company_id.append($('<option>', {
                        value: company.id,
                        text: company.title
                    }));
                    company_extract_report_company_id.val('');
                    company_transaction_report_company_id.append($('<option>', {
                        value: company.id,
                        text: company.title
                    }));
                    company_transaction_report_company_id.val();
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Cariler Alınırken Serviste Bir Hata Oluştu!');
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
                transaction_report_safebox_id.empty();
                $.each(response.response, function (i, safebox) {
                    transaction_report_safebox_id.append($('<option>', {
                        value: safebox.id,
                        text: safebox.name
                    }));
                    transaction_report_safebox_id.val('');
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kasa & Banka Listesi Alınırken Serviste Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin.');
            }
        });
    }

    function getTransactionCategories() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customerTransactionCategory.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                transaction_report_category_id.empty().append($('<option>', {
                    value: 0,
                    text: 'Tümü'
                }));
                $.each(response.response, function (i, category) {
                    transaction_report_category_id.append($('<option>', {
                        value: category.id,
                        text: category.name
                    }));
                    transaction_report_category_id.val('');
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Gelir & Gider Kategorileri Alınırken Serviste Bir Hata Oluştu!');
            }
        });
    }

    function getTransactionTypes() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transactionType.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                company_transaction_report_type_id.empty().append($('<option>', {
                    value: 0,
                    text: 'Tümü'
                }));
                $.each(response.response, function (i, type) {
                    company_transaction_report_type_id.append($('<option>', {
                        value: type.id,
                        text: type.name
                    }));
                    company_transaction_report_type_id.val('');
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('İşlem Türleri Alınırken Serviste Bir Sorun Oluştu!');
            }
        });
    }

    getCompanies();
    getSafeboxes();
    getTransactionCategories();
    getTransactionTypes();

    CompanyExtractReportButton.click(function () {
        var companyId = company_extract_report_company_id.val();
        var date = $('#company_extract_report_date').val();

        if (!companyId) {
            toastr.warning('Lütfen Cari Seçiniz!');
        } else if (!date) {
            toastr.warning('Lütfen Tarih Seçiniz!');
        } else {
            $('#loader').fadeIn(250);
            $('#CompanyExtractReportModal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.company.report.extract') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    date: date
                },
                success: function (response) {
                    var basePath = '{{ asset('') }}';
                    window.open(basePath + response.response, '_blank');
                    $('#loader').fadeOut(250);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Rapor Oluşturulurken Serviste Bir Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyiniz.');
                    $('#loader').fadeOut(250);
                }
            });
        }
    });

    CompanyTransactionReportButton.click(function () {
        var companyId = company_transaction_report_company_id.val();
        var startDate = $('#company_transaction_report_start_date').val();
        var endDate = $('#company_transaction_report_end_date').val();
        var typeId = company_transaction_report_type_id.val();

        if (!companyId) {
            toastr.warning('Lütfen Cari Seçiniz!');
        } else if (!startDate) {
            toastr.warning('Lütfen Başlangıç Tarihi Seçiniz!');
        } else if (!endDate) {
            toastr.warning('Lütfen Bitiş Tarihi Seçiniz!');
        } else if (!typeId) {
            toastr.warning('Lütfen İşlem Türü Seçiniz!');
        } else {
            $('#loader').fadeIn(250);
            $('#CompanyTransactionReportModal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.company.report.transaction') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    startDate: startDate,
                    endDate: endDate,
                    typeId: typeId,
                },
                success: function (response) {
                    var basePath = '{{ asset('') }}';
                    window.open(basePath + response.response, '_blank');
                    $('#loader').fadeOut(250);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Rapor Oluşturulurken Serviste Bir Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyiniz.');
                    $('#loader').fadeOut(250);
                }
            });
        }
    });

    TransactionReportButton.click(function () {
        var safeboxId = transaction_report_safebox_id.val();
        var direction = transaction_report_direction.val();
        var categoryId = transaction_report_category_id.val();
        var startDate = $('#transaction_report_start_date').val();
        var endDate = $('#transaction_report_end_date').val();

        if (!safeboxId) {
            toastr.warning('Lütfen Kasa & Banka Seçiniz!');
        } else if (!direction) {
            toastr.warning('Lütfen İşlem Türü Seçiniz!');
        } else if (!categoryId) {
            toastr.warning('Lütfen Kategori Seçiniz!');
        } else if (!startDate) {
            toastr.warning('Lütfen Başlangıç Tarihi Seçiniz!');
        } else if (!endDate) {
            toastr.warning('Lütfen Bitiş Tarihi Seçiniz!');
        } else {
            $('#loader').fadeIn(250);
            $('#TransactionReportModal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.report.get') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    safeboxId: safeboxId,
                    direction: direction,
                    categoryId: categoryId,
                    startDate: startDate,
                    endDate: endDate,
                },
                success: function (response) {
                    var basePath = '{{ asset('') }}';
                    window.open(basePath + response.response, '_blank');
                    $('#loader').fadeOut(250);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Rapor Oluşturulurken Serviste Bir Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyiniz.');
                    $('#loader').fadeOut(250);
                }
            });
        }
    });

    EInvoiceOutboxReportButton.click(function () {
        var startDate = $('#e_invoice_outbox_report_start_date').val();
        var endDate = $('#e_invoice_outbox_report_end_date').val();

        if (!startDate) {
            toastr.warning('Lütfen Başlangıç Tarihi Seçiniz!');
        } else if (!endDate) {
            toastr.warning('Lütfen Bitiş Tarihi Seçiniz!');
        } else {
            $('#loader').fadeIn(250);
            $('#EInvoiceOutboxReportModal').modal('hide');

            $.ajax({
                type: 'post',
                url: '{{ route('api.user.eInvoice.report.outbox') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    startDate: startDate,
                    endDate: endDate,
                },
                success: function (response) {
                    var basePath = '{{ asset('') }}';
                    window.open(basePath + response.response, '_blank');
                    $('#loader').fadeOut(250);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Rapor Oluşturulurken Serviste Bir Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyiniz.');
                    $('#loader').fadeOut(250);
                }
            });
        }
    });

    EInvoiceInboxReportButton.click(function () {
        var startDate = $('#e_invoice_inbox_report_start_date').val();
        var endDate = $('#e_invoice_inbox_report_end_date').val();

        if (!startDate) {
            toastr.warning('Lütfen Başlangıç Tarihi Seçiniz!');
        } else if (!endDate) {
            toastr.warning('Lütfen Bitiş Tarihi Seçiniz!');
        } else {
            $('#loader').fadeIn(250);
            $('#EInvoiceInboxReportModal').modal('hide');

            $.ajax({
                type: 'post',
                url: '{{ route('api.user.eInvoice.report.inbox') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    startDate: startDate,
                    endDate: endDate,
                },
                success: function (response) {
                    var basePath = '{{ asset('') }}';
                    window.open(basePath + response.response, '_blank');
                    $('#loader').fadeOut(250);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Rapor Oluşturulurken Serviste Bir Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyiniz.');
                    $('#loader').fadeOut(250);
                }
            });
        }
    });

</script>
