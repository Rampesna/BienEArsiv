<script>

    var CreateInvoiceButton = $('#CreateInvoiceButton');

    var products = `<option value="" selected hidden></option>`;
    var units = `<option value="" selected hidden></option>`;
    var invoiceProducts = $('#invoiceProducts');

    var create_invoice_company_id = $('#create_invoice_company_id');
    var create_invoice_type_id = $('#create_invoice_type_id');

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
                create_invoice_company_id.empty().append(`<option value="" selected hidden></option>`);
                $.each(response.response, function (i, company) {
                    create_invoice_company_id.append(`<option value="${company.id}">${company.title}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Cari Listesi Alınırken Serviste Bir Sorun Oluştu!');
            }
        });
    }

    function getTransactionTypes() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transactionType.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                invoice: 1
            },
            success: function (response) {
                create_invoice_type_id.empty().append(`<option value="" selected hidden></option>`);
                $.each(response.response, function (i, transactionType) {
                    create_invoice_type_id.append(`<option value="${transactionType.id}">${transactionType.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Fatura Türleri Alınırken Serviste Bir Sorun Oluştu!');
            }
        });
    }

    function getProducts() {
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.product.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                $.each(response.response, function (i, product) {
                    products += `<option value="${product.id}">${product.name}</option>`;
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getUnits() {
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.unit.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                $.each(response.response, function (i, unit) {
                    units += `<option value="${unit.id}">${unit.name}</option>`;
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function initializePage() {
        getCompanies();
        getTransactionTypes();
        getProducts();
        getUnits();
        newInvoiceProduct();
    }

    function newInvoiceProduct() {
        invoiceProducts.append(`
            <div class="row invoiceProductRow mb-5">
                <div class="col-xl-3">
                    <div class="form-group">
                        <select class="form-select form-select-sm form-select-solid invoiceProductProductId" data-control="select2" data-placeholder="Ürün Ara">${products}</select>
                    </div>
                </div>
                <div class="col-xl-2">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm form-control-solid moneyMask invoiceProductQuantity" placeholder="Miktar">
                    </div>
                </div>
                <div class="col-xl-2">
                    <div class="form-group">
                        <select class="form-select form-select-sm form-select-solid invoiceProductUnitId" data-control="select2" data-placeholder="Birim">${units}</select>
                    </div>
                </div>
                <div class="col-xl-2">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm form-control-solid moneyMask invoiceProductUnitPrice" placeholder="Birim Fiyat">
                    </div>
                </div>
                <div class="col-xl-1">
                    <div class="form-group">
                        <select class="form-select form-select-sm form-select-solid invoiceProductVatRate" data-control="select2" data-placeholder="KDV" data-hide-search="true">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="8">8</option>
                            <option value="18">18</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-2">
                    <div class="row">
                        <div class="col-xl-10">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm form-control-solid invoiceProductTotalPrice" placeholder="Tutar" disabled>
                            </div>
                        </div>
                        <div class="col-xl-2 text-end">
                            <i class="fas fa-trash-alt text-danger cursor-pointer mt-3 deleteInvoiceProductRow" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="dropdown"></i>
                        </div>
                    </div>
                </div>
            </div>
        `);
        $('.invoiceProductProductId').select2();
        $('.invoiceProductUnitId').select2();
        $('.invoiceProductVatRate').select2({
            minimumResultsForSearch: Infinity
        });
    }

    var NewInvoiceProductButton = $('#NewInvoiceProductButton');

    NewInvoiceProductButton.click(function () {
        newInvoiceProduct();
    });

    initializePage();

    $(document).delegate('.deleteInvoiceProductRow', 'click', function (e) {
        e.preventDefault();
        rows = $('.invoiceProductRow');

        if (rows.length > 1) {
            $(this).closest('.invoiceProductRow').remove();
        } else {
            toastr.warning('En az bir ürün olmalıdır.');
        }
    });

    CreateInvoiceButton.click(function () {
        var taxNumber = $('#create_invoice_tax_number').val();
        var companyId = create_invoice_company_id.val();
        var typeId = create_invoice_type_id.val();
        var companyStatementDescription = $('#create_invoice_company_statement_description').val();
        var datetime = $('#create_invoice_datetime').val();
        var number = $('#create_invoice_number').val();
        var vatIncluded = $('#create_invoice_vat_included').is(':checked');
        var waybillNumber = $('#create_invoice_waybill_number').val();
        var waybillDatetime = $('#create_invoice_waybill_datetime').val();
        var orderNumber = $('#create_invoice_order_number').val();
        var orderDatetime = $('#create_invoice_order_datetime').val();

        var invoiceProducts = [];
        var invoiceProductRows = $('.invoiceProductRow');

        $.each(invoiceProductRows, function (i, invoiceProductRow) {
            var productId = $(this).find('.invoiceProductProductId').val();
            console.log(productId);
        });

        // if (!companyId) {
        //     toastr.warning('Cari Seçmediniz!');
        // } else if (!typeId) {
        //     toastr.warning('Fatura Türü Seçmediniz!');
        // } else if (!datetime) {
        //     toastr.warning('Fatura Tarihi Seçmediniz!');
        // } else {
        //
        // }
    });

</script>
