<script>

    var CreateButton = $('#CreateButton');
    var CreateInvoiceButton = $('#CreateInvoiceButton');
    var NewInvoiceProductButton = $('#NewInvoiceProductButton');

    var allCompanies = [];
    var newInvoice = null;
    var newInvoiceProducts = [];
    var allNewInvoiceProducts = [];

    var products = [];
    var units = [];
    var productsForSelect = `<option value="" selected hidden></option>`;
    var unitsForSelect = `<option value="" selected hidden></option>`;
    var invoiceProducts = $('#invoiceProducts');

    var create_invoice_company_id = $('#create_invoice_company_id');
    var create_invoice_type_id = $('#create_invoice_type_id');
    var create_invoice_transaction_safebox_id = $('#create_invoice_transaction_safebox_id');
    var create_invoice_transaction = $('#create_invoice_transaction');

    function getCompanies() {
        $.ajax({
            async: false,
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
                allCompanies = response.response;
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
                create_invoice_transaction_safebox_id.append(`<option value="" selected disabled></option>`);
                $.each(response.response, function (i, safebox) {
                    create_invoice_transaction_safebox_id.append(`<option value="${safebox.id}">${safebox.name}</option>`);
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
                create_invoice_type_id.empty().append(`<option value="" selected hidden></option>`);
                $.each(response.response, function (i, transactionType) {
                    create_invoice_type_id.append(`<option value="${transactionType.id}" data-parent-id="${transactionType.parent_id}" data-direction="${transactionType.direction}">${transactionType.name}</option>`);
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
            url: '{{ route('api.user.customerUnit.all') }}',
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

    function initializePage() {
        getCompanies();
        getSafeboxes();
        getTransactionTypes();
        getProducts();
        getUnits();
        newInvoiceProduct();
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
            var productId = $(this).find('.invoiceProductProductId').val();
            var quantity = $(this).find('.invoiceProductQuantity').val();
            var unitId = $(this).find('.invoiceProductUnitId').val();
            var unitPrice = $(this).find('.invoiceProductUnitPrice').val();
            var vatRate = $(this).find('.invoiceProductVatRate').val();

            if (productId && quantity && unitId && unitPrice && vatRate) {
                invoiceProducts.push({
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

        newInvoiceProducts = invoiceProducts;
        allNewInvoiceProducts = allInvoiceProducts;
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

    CreateButton.click(function () {
        var create = 1;
        var taxNumber = $('#create_invoice_tax_number').val();
        var companyId = create_invoice_company_id.val();
        var typeId = create_invoice_type_id.val();
        var parentTypeId = create_invoice_type_id.find(':selected').data('parent-id');
        var direction = create_invoice_type_id.find(':selected').data('direction');
        var companyStatementDescription = $('#create_invoice_company_statement_description').val();
        var datetime = $('#create_invoice_datetime').val();
        var number = $('#create_invoice_number').val();
        var vatIncluded = $('#create_invoice_vat_included').is(':checked') ? 1 : 0;
        var waybillNumber = $('#create_invoice_waybill_number').val();
        var waybillDatetime = $('#create_invoice_waybill_datetime').val();
        var orderNumber = $('#create_invoice_order_number').val();
        var orderDatetime = $('#create_invoice_order_datetime').val();

        if (!companyId) {
            toastr.warning('Cari Seçmediniz!');
        } else if (!typeId) {
            toastr.warning('Fatura Türü Seçmediniz!');
        } else if (!datetime) {
            toastr.warning('Fatura Tarihi Seçmediniz!');
        } else {
            console.log(allNewInvoiceProducts);
            if (allNewInvoiceProducts.length === 0) {
                toastr.warning('Lütfen tüm alanları doldurunuz!');
            } else {
                $.each(allNewInvoiceProducts, function (i, product) {
                    if (!product.productId || !product.quantity || !product.unitId || !product.unitPrice || !product.vatRate) {
                        toastr.warning('Lütfen tüm alanları doldurunuz.');
                        create = 0;
                        return false;
                    }
                });

                if (create === 1) {
                    newInvoice = {
                        taxNumber: taxNumber,
                        companyId: companyId,
                        typeId: typeId,
                        parentTypeId: parentTypeId,
                        direction: direction,
                        companyStatementDescription: companyStatementDescription,
                        datetime: datetime,
                        number: number,
                        vatIncluded: vatIncluded,
                        waybillNumber: waybillNumber,
                        waybillDatetime: waybillDatetime,
                        orderNumber: orderNumber,
                        orderDatetime: orderDatetime
                    };
                    $('#CreateInvoiceModal').modal('show');
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
        var vat = total * vatRate / 100;
        var totalWithVat = total + vat;
        $(item).closest('.invoiceProductRow').find('.invoiceProductTotal').val(total);
        calculateTotals();
    }

    $(document).delegate('.invoiceProductInput', 'change', function () {
        calculateRowTotal(this);
    });

    create_invoice_transaction.change(function () {
        if (parseInt($(this).val()) === 0) {
            $('#create_invoice_transaction_inputs').hide();
        } else {
            $('#create_invoice_transaction_inputs').show();
        }
    });

    create_invoice_company_id.change(function () {
        var companyId = $(this).val();
        $('#create_invoice_tax_number').val(allCompanies.find(company => parseInt(company.id) === parseInt(companyId)).tax_number);
    });

    CreateInvoiceButton.click(function () {
        $('#loader').fadeIn(250);
        var createTransaction = 0;
        if (parseInt(create_invoice_transaction.val()) === 1) {
            createTransaction = 1;
            var transactionSafeboxId = $('#create_invoice_transaction_safebox_id').val();
            var transactionDatetime = $('#create_invoice_transaction_datetime').val();

            if (!transactionSafeboxId) {
                toastr.warning('Kasa & Banka Seçmediniz!');
                return false;
            } else if (!transactionDatetime) {
                toastr.warning('Ödeme Tarihi Seçmediniz!');
                return false;
            }
        }

        $('#CreateInvoiceModal').modal('hide');

        $.ajax({
            type: 'post',
            url: '{{ route('api.user.invoice.create') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                taxNumber: newInvoice.taxNumber,
                companyId: newInvoice.companyId,
                typeId: newInvoice.typeId,
                parentTypeId: newInvoice.typeId,
                companyStatementDescription: newInvoice.companyStatementDescription,
                datetime: reformatDatetime(newInvoice.datetime),
                number: newInvoice.number,
                vatIncluded: newInvoice.vatIncluded === true ? 1 : 0,
                waybillNumber: newInvoice.waybillNumber,
                waybillDatetime: newInvoice.waybillDatetime,
                orderNumber: newInvoice.orderNumber,
                orderDatetime: newInvoice.orderDatetime,
                price: $.sum($.map(newInvoiceProducts, function (product) {
                    return (product.quantity * product.unitPrice) + (product.quantity * product.unitPrice * product.vatRate / 100);
                })),
            },
            success: function (response) {
                var completed = 1;
                $.each(newInvoiceProducts, function (i, newInvoiceProduct) {
                    $.ajax({
                        async: false,
                        type: 'post',
                        url: '{{ route('api.user.invoiceProduct.create') }}',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': token
                        },
                        data: {
                            invoiceId: response.response.id,
                            productId: newInvoiceProduct.productId,
                            quantity: newInvoiceProduct.quantity,
                            unitId: newInvoiceProduct.unitId,
                            unitPrice: newInvoiceProduct.unitPrice,
                            vatRate: newInvoiceProduct.vatRate,
                        },
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (error) {
                            completed = 0;
                        }
                    });
                });

                $.ajax({
                    type: 'post',
                    url: '{{ route('api.user.transaction.create') }}',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token
                    },
                    data: {
                        companyId: newInvoice.companyId,
                        invoiceId: response.response.id,
                        datetime: reformatDatetime(newInvoice.datetime),
                        typeId: newInvoice.typeId,
                        direction: newInvoice.direction === 1 ? 0 : 1,
                        description: `${response.response.id} Numaralı Faturaya Ait Tahsilat Makbuzu`,
                        amount: $.sum($.map(newInvoiceProducts, function (product) {
                            return (product.quantity * product.unitPrice) + (product.quantity * product.unitPrice * product.vatRate / 100);
                        })),
                        safeboxId: null,
                        locked: 1,
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error('Tahsilat Makbuzu Oluşturulurken Serviste Bir Hata Oluştu!');
                        return false;
                    }
                });

                if (createTransaction === 1) {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('api.user.transaction.create') }}',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': token
                        },
                        data: {
                            companyId: newInvoice.companyId,
                            invoiceId: response.response.id,
                            datetime: reformatDatetime(transactionDatetime),
                            typeId: newInvoice.parentTypeId,
                            direction: newInvoice.direction,
                            description: `${response.response.id} Numaralı Faturaya Ait Tahsilat Makbuzu`,
                            amount: $.sum($.map(newInvoiceProducts, function (product) {
                                return (product.quantity * product.unitPrice) + (product.quantity * product.unitPrice * product.vatRate / 100);
                            })),
                            safeboxId: transactionSafeboxId,
                            locked: 1,
                        },
                        error: function (error) {
                            console.log(error);
                            toastr.error('Tahsilat Makbuzu Oluşturulurken Serviste Bir Hata Oluştu!');
                            return false;
                        }
                    });
                }

                if (completed === 1) {
                    toastr.success('Faturanız Başarıyla Oluşturuldu!');
                    setTimeout(function () {
                        window.location.href = '{{ route('web.user.invoice.index') }}';
                    }, 1000);
                } else {
                    toastr.error('Faturaya Ürünler Eklenirken Serviste Sistemsel Bir Sorun Oluştu!');
                }
            },
            error: function (error) {
                console.log(error);
                toastr.error('Fatura Oluşturulurken Serviste Bir Sorun Oluştu!');
                $('#loader').fadeOut(250);
            }
        });
    });

</script>
