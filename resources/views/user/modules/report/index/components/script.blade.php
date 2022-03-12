<script>

    var company_extract_report_company_id = $('#company_extract_report_company_id');
    var company_transaction_report_company_id = $('#company_transaction_report_company_id');
    var company_transaction_report_type_id = $('#company_transaction_report_type_id');
    var transaction_report_safebox_id = $('#transaction_report_safebox_id');
    var transaction_report_category_id = $('#transaction_report_category_id');

    function companyTransactionStatusReport() {

    }

    function companyExtractReport() {
        $('#CompanyExtractReportModal').modal('show');
    }

    function companyTransactionReport() {
        $('#CompanyTransactionReportModal').modal('show');
    }

    function transactionReport() {
        $('#TransactionReportModal').modal('show');
    }

    function productReport() {

    }

    function eInvoiceOutboxReport() {
        $('#EInvoiceOutboxReportModal').modal('show');
    }

    function eInvoiceInboxReport() {
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
                        text: company.name
                    }));
                    company_extract_report_company_id.val('');
                    company_transaction_report_company_id.append($('<option>', {
                        value: company.id,
                        text: company.name
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
                transaction_report_category_id.empty();
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
                company_transaction_report_type_id.empty();
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

</script>
