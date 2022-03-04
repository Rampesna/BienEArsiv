<script>

    var UpdateInvoiceButton = $('#UpdateInvoiceButton');
    var NewInvoiceProductButton = $('#NewInvoiceProductButton');

    var editInvoice = null;
    var editInvoiceProducts = [];
    var allEditInvoiceProducts = [];

    var products = [];
    var units = [];
    var productsForSelect = `<option value="" selected hidden></option>`;
    var unitsForSelect = `<option value="" selected hidden></option>`;
    var invoiceProducts = $('#invoiceProducts');

    var edit_invoice_company_id = $('#edit_invoice_company_id');
    var edit_invoice_type_id = $('#edit_invoice_type_id');
    var edit_invoice_transaction_safebox_id = $('#edit_invoice_transaction_safebox_id');
    var edit_invoice_transaction = $('#edit_invoice_transaction');

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
                edit_invoice_company_id.empty().append(`<option value="" selected hidden></option>`);
                $.each(response.response, function (i, company) {
                    edit_invoice_company_id.append(`<option value="${company.id}">${company.title}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Cari Listesi Alınırken Serviste Bir Sorun Oluştu!');
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
                edit_invoice_transaction_safebox_id.append(`<option value="" selected disabled></option>`);
                $.each(response.response, function (i, safebox) {
                    edit_invoice_transaction_safebox_id.append(`<option value="${safebox.id}">${safebox.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kasa & Banka Listesi Alınırken Serviste Bir Hata Oluştu.');
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
                edit_invoice_type_id.empty().append(`<option value="" selected hidden></option>`);
                $.each(response.response, function (i, transactionType) {
                    edit_invoice_type_id.append(`<option value="${transactionType.id}" data-parent-id="${transactionType.parent_id}" data-direction="${transactionType.direction}">${transactionType.name}</option>`);
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
                    products.push(product);
                    productsForSelect += `<option value="${product.id}">${product.name}</option>`;
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
                    units.push(unit);
                    unitsForSelect += `<option value="${unit.id}">${unit.name}</option>`;
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getInvoice() {
        var id = '{{ $id }}';
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.invoice.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function (response) {
                $('#edit_invoice_tax_number').val(response.response.tax_number);
                $('#edit_invoice_company_id').val(response.response.company_id).select2();
                $('#edit_invoice_type_id').val(response.response.type_id).select2();
                $('#edit_invoice_company_statement_description').val(response.response.company_statement_description);
                $('#edit_invoice_datetime').val(reformatDateForCalendar(response.response.datetime));
                $('#edit_invoice_number').val(response.response.number);
                $('#edit_invoice_vat_included').val(response.response.vat_included).select2();
                $('#edit_invoice_waybill_number').val(response.response.waybill_number);
                $('#edit_invoice_waybill_datetime').val(response.response.waybill_datetime ? reformatDateForCalendar(response.response.waybill_datetime) : '');
                $('#edit_invoice_order_number').val(response.response.order_number);
                $('#edit_invoice_order_datetime').val(response.response.waybill_datetime ? reformatDateForCalendar(response.response.order_datetime) : '');

                $.ajax({
                    type: 'get',
                    url: '{{ route('api.user.invoiceProduct.getByInvoiceId') }}',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token
                    },
                    data: {
                        invoiceId: response.response.id
                    },
                    success: function (response) {
                        $.each(response.response, function (i, invoiceProduct) {
                            invoiceProducts.append(`
                                <div class="row invoiceProductRow mb-5" data-invoice-product-id="${invoiceProduct.id}">
                                    <div class="col-xl-3 mb-5">
                                        <div class="form-group">
                                            <select id="invoiceProduct_${invoiceProduct.id}_ProductId" class="form-select form-select-sm form-select-solid invoiceProductProductId" data-control="select2" data-placeholder="Ürün Ara">${productsForSelect}</select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 mb-5">
                                        <div class="form-group">
                                            <input id="invoiceProduct_${invoiceProduct.id}_Quantity" type="text" class="form-control form-control-sm form-control-solid moneyMask invoiceProductQuantity invoiceProductInput" placeholder="Miktar">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 mb-5">
                                        <div class="form-group">
                                            <select id="invoiceProduct_${invoiceProduct.id}_UnitId" class="form-select form-select-sm form-select-solid invoiceProductUnitId invoiceProductInput" data-control="select2" data-placeholder="Birim">${unitsForSelect}</select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 mb-5">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm input-group-solid">
                                                <input id="invoiceProduct_${invoiceProduct.id}_UnitPrice" type="text" class="form-control form-control-sm form-control-solid moneyMask invoiceProductUnitPrice invoiceProductInput" placeholder="Birim Fiyat">
                                                <span class="input-group-text">₺</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-1 mb-5">
                                        <div class="form-group">
                                            <select id="invoiceProduct_${invoiceProduct.id}_VatRate" class="form-select form-select-sm form-select-solid invoiceProductVatRate invoiceProductInput" data-control="select2" data-placeholder="KDV" data-hide-search="true">
                                                <option value="0">0 %</option>
                                                <option value="1">1 %</option>
                                                <option value="8">8 %</option>
                                                <option value="18">18 %</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 mb-5">
                                        <div class="row">
                                            <div class="col-xl-10">
                                                <div class="form-group">
                                                    <div class="input-group input-group-sm input-group-solid mb-3">
                                                        <input id="invoiceProduct_${invoiceProduct.id}_Total" type="text" class="form-control form-control-sm form-control-solid text-end invoiceProductTotal" placeholder="Tutar" disabled>
                                                        <span class="input-group-text">₺</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 mb-5 text-end">
                                                <i class="fas fa-trash-alt text-danger cursor-pointer mt-3 deleteInvoiceProductRow" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="dropdown"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $('#invoiceProduct_' + invoiceProduct.id + '_ProductId').val(invoiceProduct.product_id);
                            $('#invoiceProduct_' + invoiceProduct.id + '_Quantity').val(parseFloat(invoiceProduct.quantity));
                            $('#invoiceProduct_' + invoiceProduct.id + '_UnitId').val(invoiceProduct.unit_id);
                            $('#invoiceProduct_' + invoiceProduct.id + '_UnitPrice').val(parseFloat(invoiceProduct.unit_price));
                            $('#invoiceProduct_' + invoiceProduct.id + '_VatRate').val(parseInt(invoiceProduct.vat_rate));
                            $('#invoiceProduct_' + invoiceProduct.id + '_Total').val(invoiceProduct.quantity * invoiceProduct.unit_price);
                        });
                        $('.invoiceProductProductId').select2();
                        $('.invoiceProductUnitId').select2();
                        $('.invoiceProductVatRate').select2({
                            minimumResultsForSearch: Infinity
                        });
                        initializeMoneyInputMask();
                        calculateTotals();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function initializePage() {
        getCompanies();
        getSafeboxes();
        getTransactionTypes();
        getProducts();
        getUnits();
        getInvoice();
        calculateTotals();
    }

    function newInvoiceProduct() {
        invoiceProducts.append(`
            <div class="row invoiceProductRow mb-5">
                <div class="col-xl-3 mb-5">
                    <div class="form-group">
                        <select class="form-select form-select-sm form-select-solid invoiceProductProductId" data-control="select2" data-placeholder="Ürün Ara">${productsForSelect}</select>
                    </div>
                </div>
                <div class="col-xl-2 mb-5">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm form-control-solid moneyMask invoiceProductQuantity invoiceProductInput" placeholder="Miktar">
                    </div>
                </div>
                <div class="col-xl-2 mb-5">
                    <div class="form-group">
                        <select class="form-select form-select-sm form-select-solid invoiceProductUnitId invoiceProductInput" data-control="select2" data-placeholder="Birim">${unitsForSelect}</select>
                    </div>
                </div>
                <div class="col-xl-2 mb-5">
                    <div class="form-group">
                        <div class="input-group input-group-sm input-group-solid">
                            <input type="text" class="form-control form-control-sm form-control-solid moneyMask invoiceProductUnitPrice invoiceProductInput" placeholder="Birim Fiyat">
                            <span class="input-group-text">₺</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-1 mb-5">
                    <div class="form-group">
                        <select class="form-select form-select-sm form-select-solid invoiceProductVatRate invoiceProductInput" data-control="select2" data-placeholder="KDV" data-hide-search="true">
                            <option value="0">0 %</option>
                            <option value="1">1 %</option>
                            <option value="8">8 %</option>
                            <option value="18" selected>18 %</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-2 mb-5">
                    <div class="row">
                        <div class="col-xl-10">
                            <div class="form-group">
                                <div class="input-group input-group-sm input-group-solid mb-3">
                                    <input type="text" class="form-control form-control-sm form-control-solid text-end invoiceProductTotal" placeholder="Tutar" disabled>
                                    <span class="input-group-text">₺</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 mb-5 text-end">
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
        initializeMoneyInputMask();
        calculateTotals();
    }

    function calculateTotals() {
        var invoiceProductRows = $('.invoiceProductRow');
        var subtotal = 0;
        var vatTotal = 0;
        var generalTotal = 0;

        var invoiceProducts = [];
        var allInvoiceProducts = [];

        $.each(invoiceProductRows, function (i, invoiceProductRow) {
            var id = $(this).data('invoice-product-id') || null;
            var productId = $(this).find('.invoiceProductProductId').val();
            var quantity = $(this).find('.invoiceProductQuantity').val();
            var unitId = $(this).find('.invoiceProductUnitId').val();
            var unitPrice = $(this).find('.invoiceProductUnitPrice').val();
            var vatRate = $(this).find('.invoiceProductVatRate').val();

            if (productId && quantity && unitId && unitPrice && vatRate) {
                invoiceProducts.push({
                    id: id,
                    productId: productId,
                    quantity: quantity,
                    unitId: unitId,
                    unitPrice: unitPrice,
                    vatRate: vatRate
                });

                subtotal += parseFloat(quantity) * parseFloat(unitPrice);
                vatTotal += parseFloat(quantity) * parseFloat(unitPrice) * parseFloat(vatRate) / 100;
            }

            allInvoiceProducts.push({
                id: id,
                productId: productId,
                quantity: quantity,
                unitId: unitId,
                unitPrice: unitPrice,
                vatRate: vatRate
            });
        });

        generalTotal = subtotal + vatTotal;

        $('#subtotalSpan').val(reformatNumberToMoney(subtotal));
        $('#vatTotalSpan').val(reformatNumberToMoney(vatTotal));
        $('#generalTotalSpan').val(reformatNumberToMoney(generalTotal));

        editInvoiceProducts = invoiceProducts;
        allEditInvoiceProducts = allInvoiceProducts;
    }

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

        calculateTotals();
    });

    UpdateInvoiceButton.click(function () {
        var edit = 1;
        var taxNumber = $('#edit_invoice_tax_number').val();
        var companyId = edit_invoice_company_id.val();
        var typeId = edit_invoice_type_id.val();
        var parentTypeId = edit_invoice_type_id.find(':selected').data('parent-id');
        var direction = edit_invoice_type_id.find(':selected').data('direction');
        var companyStatementDescription = $('#edit_invoice_company_statement_description').val();
        var datetime = $('#edit_invoice_datetime').val();
        var number = $('#edit_invoice_number').val();
        var vatIncluded = $('#edit_invoice_vat_included').is(':checked') ? 1 : 0;
        var waybillNumber = $('#edit_invoice_waybill_number').val();
        var waybillDatetime = $('#edit_invoice_waybill_datetime').val();
        var orderNumber = $('#edit_invoice_order_number').val();
        var orderDatetime = $('#edit_invoice_order_datetime').val();

        if (!companyId) {
            toastr.warning('Cari Seçmediniz!');
        } else if (!typeId) {
            toastr.warning('Fatura Türü Seçmediniz!');
        } else if (!datetime) {
            toastr.warning('Fatura Tarihi Seçmediniz!');
        } else {
            if (allEditInvoiceProducts.length === 0) {
                toastr.warning('Lütfen tüm alanları doldurunuz!');
            } else {
                $.each(allEditInvoiceProducts, function (i, product) {
                    if (!product.productId || !product.quantity || !product.unitId || !product.unitPrice || !product.vatRate) {
                        toastr.warning('Lütfen tüm alanları doldurunuz.');
                        edit = 0;
                        return false;
                    }
                });

                if (edit === 1) {

                    $('#loader').fadeIn(250);

                    editInvoice = {
                        id: '{{ $id }}',
                        taxNumber: taxNumber,
                        companyId: companyId,
                        typeId: typeId,
                        parentTypeId: parentTypeId,
                        direction: direction,
                        companyStatementDescription: companyStatementDescription,
                        datetime: reformatDatetime(datetime),
                        number: number,
                        vatIncluded: vatIncluded,
                        waybillNumber: waybillNumber,
                        waybillDatetime: waybillDatetime,
                        orderNumber: orderNumber,
                        orderDatetime: orderDatetime
                    };

                    $.ajax({
                        type: 'put',
                        url: '{{ route('api.user.invoice.update') }}',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': token
                        },
                        data: editInvoice,
                        success: function () {
                            $.each(allEditInvoiceProducts, function (i, invoiceProduct) {
                                invoiceProduct.invoiceId = '{{ $id }}';
                                $.ajax({
                                    type: invoiceProduct.id != null ? 'put' : 'post',
                                    url: invoiceProduct.id != null ? '{{ route('api.user.invoiceProduct.update') }}' : '{{ route('api.user.invoiceProduct.create') }}',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Authorization': token
                                    },
                                    data: invoiceProduct,
                                    error: function (error) {
                                        console.log(error);
                                    }
                                });
                            });
                            toastr.success('Faturanız Güncellendi');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        },
                        error: function (error) {
                            console.log(error);
                            toastr.error('Fatura Güncellenirken Serviste Bir Hata Oluştu.');
                        }
                    });
                }
            }
        }
    });

    $(document).delegate('.invoiceProductProductId', 'change', function () {
        var id = $(this).val();
        product = products.find(product => parseInt(product.id) === parseInt(id));
        var quantity = $(this).closest('.invoiceProductRow').find('.invoiceProductQuantity').val();
        if (!quantity) {
            $(this).closest('.invoiceProductRow').find('.invoiceProductQuantity').val(1);
        }
        $(this).closest('.invoiceProductRow').find('.invoiceProductUnitId').val(product.unit_id).select2();
        $(this).closest('.invoiceProductRow').find('.invoiceProductUnitPrice').val(product.price);

        calculateRowTotal(this);
    });

    function calculateRowTotal(item) {
        var quantity = $(item).closest('.invoiceProductRow').find('.invoiceProductQuantity').val();
        var unitPrice = $(item).closest('.invoiceProductRow').find('.invoiceProductUnitPrice').val();
        var vatRate = $(item).closest('.invoiceProductRow').find('.invoiceProductVatRate').val();
        var total = quantity * unitPrice;
        $(item).closest('.invoiceProductRow').find('.invoiceProductTotal').val(total);
        calculateTotals();
    }

    $(document).delegate('.invoiceProductInput', 'change', function () {
        calculateRowTotal(this);
    });

    edit_invoice_transaction.change(function () {
        if (parseInt($(this).val()) === 0) {
            $('#edit_invoice_transaction_inputs').hide();
        } else {
            $('#edit_invoice_transaction_inputs').show();
        }
    });

</script>
