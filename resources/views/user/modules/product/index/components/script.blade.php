<script>

    var products = $('#products');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var create_product_unit_id = $('#create_product_unit_id');

    var CreateProductButton = $('#CreateProductButton');

    function createProduct() {
        create_product_unit_id.val('').select2();
        $('#create_product_code').val('');
        $('#create_product_name').val('');
        $('#create_product_price').val('');
        $('#create_product_vat_rate').val('18').select2();
        $('#CreateProductModal').modal('show');
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
                $.each(response.response, function (i, unit) {
                    create_product_unit_id.append(`<option value="${unit.id}">${unit.name}</option>`);
                });
                create_product_unit_id.val('').select2();
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
                            <button class="btn btn-sm btn-icon btn-primary" title="Düzenle">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-danger ms-2" title="Sil">
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

</script>
