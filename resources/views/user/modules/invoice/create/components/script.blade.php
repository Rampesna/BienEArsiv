<script>

    var products = `<option value="" selected hidden></option>`;
    var units = `<option value="" selected hidden></option>`;
    var invoiceProducts = $('#invoiceProducts');

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
                            <div class="form-group">
                                <i class="fas fa-ellipsis-v fa-lg cursor-pointer mt-3 invoiceProductEditor"></i>
                            </div>
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

</script>
