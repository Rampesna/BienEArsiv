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
    var create_invoice_currency_id = $('#create_invoice_currency_id');
    var create_invoice_vat_discount_id = $('#create_invoice_vat_discount_id');
    var create_invoice_transaction_safebox_id = $('#create_invoice_transaction_safebox_id');
    var create_invoice_transaction = $('#create_invoice_transaction');

    var CreateNewCompany = $('#CreateNewCompany');
    var CreateNewCompanyButton = $('#CreateNewCompanyButton');
    var CreateNewProductButton = $('#CreateNewProductButton');

    $(document).delegate('.invoiceProductVatRate', 'keypress', function (e) {
        if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    $(document).delegate('.invoiceProductVatRate', 'keyup', function (e) {
        if (this.value > 100) {
            this.value = 100;
            return false;
        } else if (this.value < 0) {
            this.value = 0;
            return false;
        }
    });

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

    function getCurrencies() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.currency.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                create_invoice_currency_id.empty().append(`<option value="" selected hidden></option>`);
                $.each(response.response, function (i, currency) {
                    create_invoice_currency_id.append(`<option ${currency.code === 'TRY' ? 'selected' : ''} value="${currency.id}" data-code="${currency.code}">${currency.code} - ${currency.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Fatura Türleri Alınırken Serviste Bir Sorun Oluştu!');
            }
        });
    }

    function getVatDiscounts() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.vatDiscount.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                create_invoice_vat_discount_id.empty().append(`<option value="0" data-code="0" data-percent="0" selected> Yok</option>`);
                $.each(response.response, function (i, vatDiscount) {
                    create_invoice_vat_discount_id.append(`<option value="${vatDiscount.id}" data-code="${vatDiscount.code}" data-percent="${vatDiscount.percent}">${vatDiscount.name}</option>`);
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
        getCurrencies();
        getVatDiscounts();
        getProducts();
        getUnits();
        newInvoiceProduct();
        calculateTotals();
    }

    function newInvoiceProduct() {
        invoiceProducts.append(`
            <div class="row invoiceProductRow mb-5">
                <div class="col-xl-6 mb-5">
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <button class="btn btn-sm btn-icon btn-success CreateNewProduct" type="button">+</button>
                            <select class="form-select form-select-sm form-select-solid invoiceProductProductId" data-control="select2" data-placeholder="Ürün Ara">${productsForSelect}</select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-5">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm form-control-solid decimal invoiceProductQuantity invoiceProductInput" placeholder="Miktar">
                    </div>
                </div>
                <div class="col-xl-3 mb-5">
                    <div class="form-group">
                        <select class="form-select form-select-sm form-select-solid invoiceProductUnitId invoiceProductInput" data-control="select2" data-placeholder="Birim">${unitsForSelect}</select>
                    </div>
                </div>
                <div class="col-xl-3 mb-5">
                    <div class="form-group">
                        <div class="input-group input-group-sm input-group-solid">
                            <input type="text" class="form-control form-control-sm form-control-solid decimal invoiceProductUnitPrice invoiceProductInput" placeholder="Birim Fiyat">
                            <span class="input-group-text">₺</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-5">
                    <div class="form-group input-group input-group-sm input-group-solid">
                        <input type="text" class="form-control form-control-sm form-control-solid onlyNumber invoiceProductVatRate invoiceProductInput" placeholder="KDV">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
                <div class="col-xl-3 mb-5">
                    <div class="form-group">
                        <div class="input-group input-group-sm input-group-solid">
                            <input type="text" class="form-control form-control-sm form-control-solid decimal invoiceProductDiscountRate invoiceProductInput" placeholder="İskonto">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-5">
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
                <hr class="text-muted">
            </div>
        `);
        $('.invoiceProductProductId').select2();
        $('.invoiceProductUnitId').select2();
        initializeDecimals();
        calculateTotals();
    }

    function calculateTotals() {
        var invoiceProductRows = $('.invoiceProductRow');
        var vatDiscountId = create_invoice_vat_discount_id.val();
        var vatDiscountRate = create_invoice_vat_discount_id.find('option:selected').data('percent');
        var subtotal = 0;
        var vatTotal = 0;
        var vatDiscountTotal = 0;
        var generalTotal = 0;

        var invoiceProducts = [];
        var allInvoiceProducts = [];

        $.each(invoiceProductRows, function (i, invoiceProductRow) {
            var productId = $(this).find('.invoiceProductProductId').val();
            var quantity = $(this).find('.invoiceProductQuantity').val();
            var unitId = $(this).find('.invoiceProductUnitId').val();
            var unitPrice = $(this).find('.invoiceProductUnitPrice').val();
            var vatRate = $(this).find('.invoiceProductVatRate').val();
            var discountRate = $(this).find('.invoiceProductDiscountRate').val() || 0;

            if (productId && quantity && unitId && unitPrice && vatRate) {
                invoiceProducts.push({
                    productId: productId,
                    quantity: quantity,
                    unitId: unitId,
                    unitPrice: unitPrice,
                    vatRate: vatRate,
                    discountRate: discountRate,
                });

                var subtotalWithoutDiscount = parseFloat(quantity) * parseFloat(unitPrice);
                var discount = parseFloat(subtotalWithoutDiscount) * parseFloat(discountRate) / 100;
                var subtotalWithDiscount = parseFloat(subtotalWithoutDiscount) - parseFloat(discount);
                var vat = parseFloat(subtotalWithDiscount) * parseFloat(vatRate) / 100;
                var vatDiscount = parseFloat(vat) * parseFloat(vatDiscountRate) / 100;

                subtotal += subtotalWithDiscount;
                vatDiscountTotal += vatDiscount;
                vatTotal += vat - vatDiscount;
            }

            allInvoiceProducts.push({
                productId: productId,
                quantity: quantity,
                unitId: unitId,
                unitPrice: unitPrice,
                vatRate: vatRate,
                discountRate: discountRate,
            });
        });

        generalTotal = subtotal + vatTotal;

        $('#subtotalSpan').val(reformatNumberToMoney(subtotal));
        $('#vatTotalSpan').val(reformatNumberToMoney(vatTotal));
        $('#generalTotalSpan').val(reformatNumberToMoney(generalTotal));

        newInvoiceProducts = invoiceProducts;
        allNewInvoiceProducts = allInvoiceProducts;

        if (parseInt(vatDiscountId) > 0) {
            $('#vatDiscountTotalInput').val(reformatNumberToMoney(vatDiscountTotal));
            $('#vatDiscountRateSpan').html(`${vatDiscountRate}%`);
            $('#vatDiscountDiv').show();
        } else {
            $('#vatDiscountSpan').val(0);
            $('#vatDiscountDiv').hide();
        }
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
        var currencyId = create_invoice_currency_id.val();
        var currency = $('#create_invoice_currency').val();
        var vatDiscountId = create_invoice_vat_discount_id.val();
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
        var returnInvoiceNumber = $('#create_invoice_return_invoice_number').val();
        var description = $('#create_invoice_description').val();

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
                        currencyId: currencyId,
                        currency: currency,
                        vatDiscountId: vatDiscountId,
                        parentTypeId: parentTypeId,
                        direction: direction,
                        companyStatementDescription: companyStatementDescription,
                        datetime: datetime,
                        number: number,
                        vatIncluded: vatIncluded,
                        waybillNumber: waybillNumber,
                        waybillDatetime: waybillDatetime,
                        orderNumber: orderNumber,
                        orderDatetime: orderDatetime,
                        returnInvoiceNumber: returnInvoiceNumber,
                        description: description,
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
        $(this).closest('.invoiceProductRow').find('.invoiceProductVatRate').val(product.vat_rate).select2();
        $(this).closest('.invoiceProductRow').find('.invoiceProductUnitId').val(product.unit_id).select2();
        $(this).closest('.invoiceProductRow').find('.invoiceProductUnitPrice').val(product.price);

        calculateRowTotal(this);
    });

    function calculateRowTotal(item) {
        var quantity = $(item).closest('.invoiceProductRow').find('.invoiceProductQuantity').val();
        var unitPrice = $(item).closest('.invoiceProductRow').find('.invoiceProductUnitPrice').val();
        var total = quantity * unitPrice;
        var discountRate = $(item).closest('.invoiceProductRow').find('.invoiceProductDiscountRate').val();
        var discount = total * discountRate / 100;
        $(item).closest('.invoiceProductRow').find('.invoiceProductTotal').val(total - discount);
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
                currencyId: newInvoice.currencyId,
                currency: newInvoice.currency,
                vatDiscountId: newInvoice.vatDiscountId,
                parentTypeId: newInvoice.typeId,
                companyStatementDescription: newInvoice.companyStatementDescription,
                datetime: reformatDatetime(newInvoice.datetime),
                number: newInvoice.number,
                vatIncluded: newInvoice.vatIncluded === true ? 1 : 0,
                waybillNumber: newInvoice.waybillNumber,
                waybillDatetime: newInvoice.waybillDatetime,
                orderNumber: newInvoice.orderNumber,
                orderDatetime: newInvoice.orderDatetime,
                returnInvoiceNumber: newInvoice.returnInvoiceNumber,
                description: newInvoice.description,
                price: $('#generalTotalSpan').val().replace(',', '')
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
                            discountRate: newInvoiceProduct.discountRate,
                        },
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (error) {
                            console.log(error);
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

    $(document).delegate('.invoiceProductDiscountRate', 'keypress', function () {
        if ($(this).val() > 100) {
            $(this).val(100);
        }
    });

    create_invoice_vat_discount_id.change(function () {
        calculateTotals();
    });

    CreateNewCompany.click(function () {
        $('#create_new_company_title').val('');
        $('#create_new_company_tax_number').val('');
        $('#CreateNewCompanyModal').modal('show');
    });

    $(document).delegate('.CreateNewProduct', 'click', function () {
        $('.waitingNewProductId').removeClass('waitingNewProductId');
        $(this).next('.invoiceProductProductId').addClass('waitingNewProductId');
        $('#create_new_product_code').val('');
        $('#create_new_product_name').val('');
        unitsForSelect = ``;
        $.each(units, function (i, unit) {
            unitsForSelect += `<option value="${unit.id}">${unit.name}</option>`;
        });
        $('#create_new_product_unit_id').html(unitsForSelect).val('');
        $('#create_new_product_price').val('');
        $('#create_new_product_vat_rate').val('18');
        $('#create_new_product_description').val('');
        $('#CreateNewProductModal').modal('show');
    });

    CreateNewCompanyButton.click(function () {
        var taxNumber = $('#create_new_company_tax_number').val();
        var title = $('#create_new_company_title').val();

        if (!taxNumber) {
            toastr.warning('Vergi Numarası Boş Bırakılamaz!');
        } else if (!title) {
            toastr.warning('Firma Adı Boş Bırakılamaz!');
        } else {
            $('#loader').show();
            $('#CreateNewCompanyModal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.company.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    taxNumber: taxNumber,
                    title: title,
                    isCustomer: 1,
                    isSupplier: 0,
                },
                success: function (response) {
                    allCompanies.push(response.response);
                    create_invoice_company_id.append($('<option>', {
                        value: response.response.id,
                        text: response.response.title
                    }));
                    create_invoice_company_id.val(response.response.id).trigger('change');
                    $('#loader').hide();
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Firma Oluşturulurken Serviste Bir Sorun Oluştu!');
                    $('#loader').hide();
                }
            });
        }
    });

    CreateNewProductButton.click(function () {
        var code = $('#create_new_product_code').val();
        var name = $('#create_new_product_name').val();
        var unitId = $('#create_new_product_unit_id').val();
        var price = $('#create_new_product_price').val();
        var vatRate = $('#create_new_product_vat_rate').val();
        var description = $('#create_new_product_description').val();

        if (!name) {
            toastr.warning('Ürün Adı Girmediniz');
        } else if (!unitId) {
            toastr.warning('Birim Seçmediniz!');
        } else if (!price) {
            toastr.warning('Fiyat Girmediniz!');
        } else if (!vatRate) {
            toastr.warning('KDV Oranı Seçmediniz!');
        } else {
            $('#loader').show();
            $('#CreateNewProductModal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.product.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    code: code,
                    name: name,
                    unitId: unitId,
                    price: price,
                    vatRate: vatRate,
                },
                success: function (response) {
                    products.push({
                        id: response.response.id,
                        code: response.response.code,
                        name: response.response.name,
                        unit_id: response.response.unit_id,
                        price: response.response.price,
                        vat_rate: response.response.vat_Rate ? response.response.vat_Rate : (response.response.vat_rate ? response.response.vat_rate : 18),
                        description: response.response.description,
                        created_at: response.response.created_at,
                        updated_at: response.response.updated_at,
                        deleted_at: response.response.deleted_at,
                    });

                    console.log(products);

                    productsForSelect += `<option value="${response.response.id}">${response.response.name}</option>`;
                    var invoiceProductRows = $('.invoiceProductRow');
                    $.each(invoiceProductRows, function (i, invoiceProductRow) {
                        $(this).find('.invoiceProductProductId').append(`<option value="${response.response.id}">${response.response.name}</option>`);
                    });
                    $('.invoiceProductProductId').select2();
                    $('.invoiceProductUnitId').select2();
                    $('.invoiceProductVatRate').select2({
                        minimumResultsForSearch: Infinity
                    });
                    $('.waitingNewProductId').val(response.response.id).trigger('change').removeClass('waitingNewProductId');
                    initializeDecimals();
                    calculateTotals();
                    $('#loader').hide();
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Ürün Oluşturulurken Serviste Bir Sorun Oluştu!');
                    $('#loader').hide();
                }
            });
        }
    });

</script>
