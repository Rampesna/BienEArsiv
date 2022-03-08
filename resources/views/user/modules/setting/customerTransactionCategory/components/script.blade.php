<script>

    var customerTransactionCategories = $('#customerTransactionCategories');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var CreateCustomerTransactionCategoryButton = $('#CreateCustomerTransactionCategoryButton');
    var UpdateCustomerTransactionCategoryButton = $('#UpdateCustomerTransactionCategoryButton');
    var DeleteCustomerTransactionCategoryButton = $('#DeleteCustomerTransactionCategoryButton');

    function createCustomerTransactionCategory() {
        $('#create_customer_transaction_category_code').val('');
        $('#create_customer_transaction_category_name').val('');
        $('#CreateCustomerTransactionCategoryModal').modal('show');
    }

    function updateCustomerTransactionCategory(id) {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customerTransactionCategory.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function (response) {
                $('#update_customer_transaction_category_id').val(response.response.id);
                $('#update_customer_transaction_category_name').val(response.response.name);
                $('#UpdateCustomerTransactionCategoryModal').modal('show');
            },
            error: function (error) {
                console.log(error);
                toastr.error('Veri Alınırken Serviste Sistemsel Bir Hata Oluştu!');
            }
        });
    }

    function deleteCustomerTransactionCategory(id) {
        $('#delete_customer_transaction_category_id').val(id);
        $('#DeleteCustomerTransactionCategoryModal').modal('show');
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getCustomerTransactionCategories();
    }

    function getCustomerTransactionCategories() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();
        var keyword = $('#filter_customer_transaction_category_keyword').val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customerTransactionCategory.index') }}',
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
                customerTransactionCategories.empty();
                $.each(response.response.customerTransactionCategories, function (i, customerTransactionCategory) {
                    customerTransactionCategories.append(`
                    <tr>
                        <td>
                            ${customerTransactionCategory.name}
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-primary" onclick="updateCustomerTransactionCategory(${customerTransactionCategory.id})" title="Düzenle">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-danger ms-2" onclick="deleteCustomerTransactionCategory(${customerTransactionCategory.id})" title="Sil">
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

    getCustomerTransactionCategories();

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
        getCustomerTransactionCategories();
    });

    CreateCustomerTransactionCategoryButton.click(function () {
        var name = $('#create_customer_transaction_category_name').val();

        if (!name) {
            toastr.warning('Kategori Adı Zorunludur!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.customerTransactionCategory.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    name: name,
                },
                success: function () {
                    $('#CreateCustomerTransactionCategoryModal').modal('hide');
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

    UpdateCustomerTransactionCategoryButton.click(function () {
        var id = $('#update_customer_transaction_category_id').val();
        var name = $('#update_customer_transaction_category_name').val();

         if (!name) {
            toastr.warning('Kategori Adı Zorunludur!');
        } else {
            $.ajax({
                type: 'put',
                url: '{{ route('api.user.customerTransactionCategory.update') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    id: id,
                    name: name,
                },
                success: function () {
                    $('#UpdateCustomerTransactionCategoryModal').modal('hide');
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

    DeleteCustomerTransactionCategoryButton.click(function () {
        var id = $('#delete_customer_transaction_category_id').val();
        $.ajax({
            type: 'delete',
            url: '{{ route('api.user.customerTransactionCategory.delete') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function () {
                $('#DeleteCustomerTransactionCategoryModal').modal('hide');
                toastr.success('Kategori Silindi!');
                changePage(1);
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kategori Silinirken Serviste Bir Hata Oluştu!');
            }
        });
    });

</script>
