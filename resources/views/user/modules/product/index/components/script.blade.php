<script>

    var products = $('#products');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var create_product_unit_id = $('#create_product_unit_id');
    var update_product_unit_id = $('#update_product_unit_id');

    var CreateProductButton = $('#CreateProductButton');
    var UpdateProductButton = $('#UpdateProductButton');
    var DeleteProductButton = $('#DeleteProductButton');

    $(document).delegate('.vatRate', 'keypress', function (e) {
        if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    $(document).delegate('.vatRate', 'keyup', function (e) {
        if (this.value > 100) {
            this.value = 100;
            return false;
        } else if (this.value < 0) {
            this.value = 0;
            return false;
        }
    });

    function createProduct() {
        create_product_unit_id.val('').select2();
        $('#create_product_code').val('');
        $('#create_product_name').val('');
        $('#create_product_price').val('');
        $('#create_product_vat_rate').val('18');
        $('#CreateProductModal').modal('show');
    }

    function updateProduct(id) {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.product.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function (response) {
                console.log(response);
                $('#update_product_id').val(response.response.id);
                $('#update_product_code').val(response.response.code);
                $('#update_product_name').val(response.response.name);
                update_product_unit_id.val(response.response.unit_id);
                $('#update_product_price').val(parseFloat(response.response.price));
                $('#update_product_vat_rate').val(response.response.vat_rate);
                $('#update_product_description').val(response.response.description);
                $('#UpdateProductModal').modal('show');
            },
            error: function (error) {
                console.log(error);
                toastr.error('Ürün Bilgileri Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function deleteProduct(id) {
        $('#delete_product_id').val(id);
        $('#DeleteProductModal').modal('show');
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getProducts();
    }

    function getUnits() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customerUnit.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                create_product_unit_id.empty();
                update_product_unit_id.empty();
                $.each(response.response, function (i, unit) {
                    create_product_unit_id.append(`<option value="${unit.id}">${unit.name}</option>`);
                    update_product_unit_id.append(`<option value="${unit.id}">${unit.name}</option>`);
                });
                create_product_unit_id.val('').select2();
                update_product_unit_id.val('').select2();
            },
            error: function (error) {
                console.log(error);
                toastr.error('Birim Listesi Alınırken Serviste Bir Sorun Oluştu!');
            }
        });
    }

    function getProducts() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();
        var keyword = $('#filter_product_keyword').val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.product.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                pageIndex: pageIndex,
                pageSize: pageSize,
                keyword: keyword,
            },
            success: function (response) {
                products.empty();
                $.each(response.response.products, function (i, product) {
                    products.append(`
                    <tr>
                        <td>
                            ${product.code}
                        </td>
                        <td>
                            ${product.name}
                        </td>
                        <td>
                            ${product.unit ? product.unit.name : ''}
                        </td>
                        <td>
                            ${reformatNumberToMoney(product.price)} ₺
                        </td>
                        <td>
                            ${product.vat_rate} %
                        </td>
                        <td class="text-end">
                            <button onclick="updateProduct(${product.id})" class="btn btn-sm btn-icon btn-primary" title="Düzenle">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-icon btn-danger ms-2" title="Sil">
                                <i class="fa fa-trash-alt"></i>
                            </button>
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
            }
        });
    }

    getProducts();
    getUnits();

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
        getProducts();
    });

    CreateProductButton.click(function () {
        var code = $('#create_product_code').val();
        var name = $('#create_product_name').val();
        var unitId = create_product_unit_id.val();
        var price = $('#create_product_price').val();
        var vatRate = $('#create_product_vat_rate').val();

        if (!name) {
            toastr.warning('Ürün Adı Girmediniz');
        } else if (!unitId) {
            toastr.warning('Birim Seçmediniz!');
        } else if (!price) {
            toastr.warning('Fiyat Girmediniz!');
        } else if (!vatRate) {
            toastr.warning('KDV Oranı Seçmediniz!');
        } else {
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
                success: function () {
                    $('#CreateProductModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('İşlem Yapılırken Serviste Bir Hata Oluştu!');
                }
            });
        }
    });

    UpdateProductButton.click(function () {
        var id = $('#update_product_id').val();
        var code = $('#update_product_code').val();
        var name = $('#update_product_name').val();
        var unitId = update_product_unit_id.val();
        var price = $('#update_product_price').val();
        var vatRate = $('#update_product_vat_rate').val();

        if (!id) {
            toastr.warning('Ürün Seçiminde Hata Oluştu!');
        }
        if (!name) {
            toastr.warning('Ürün Adı Girmediniz');
        } else if (!unitId) {
            toastr.warning('Birim Seçmediniz!');
        } else if (!price) {
            toastr.warning('Fiyat Girmediniz!');
        } else if (!vatRate) {
            toastr.warning('KDV Oranı Seçmediniz!');
        } else {
            $.ajax({
                type: 'put',
                url: '{{ route('api.user.product.update') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    id: id,
                    code: code,
                    name: name,
                    unitId: unitId,
                    price: price,
                    vatRate: vatRate,
                },
                success: function () {
                    $('#UpdateProductModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('İşlem Yapılırken Serviste Bir Hata Oluştu!');
                }
            });
        }
    });

    DeleteProductButton.click(function () {
        var id = $('#delete_product_id').val();

        if (!id) {
            toastr.warning('Ürün Seçiminde Hata Oluştu!');
        } else {
            $.ajax({
                type: 'delete',
                url: '{{ route('api.user.product.delete') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    id: id
                },
                success: function () {
                    $('#DeleteProductModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('İşlem Yapılırken Serviste Bir Hata Oluştu!');
                }
            });
        }
    });

</script>
