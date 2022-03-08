<script>

    var customerUnits = $('#customerUnits');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var CreateCustomerUnitButton = $('#CreateCustomerUnitButton');
    var UpdateCustomerUnitButton = $('#UpdateCustomerUnitButton');
    var DeleteCustomerUnitButton = $('#DeleteCustomerUnitButton');

    function createCustomerUnit() {
        $('#create_customer_unit_code').val('');
        $('#create_customer_unit_name').val('');
        $('#CreateCustomerUnitModal').modal('show');
    }

    function updateCustomerUnit(id) {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customerUnit.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function (response) {
                $('#update_customer_unit_id').val(response.response.id);
                $('#update_customer_unit_code').val(response.response.code);
                $('#update_customer_unit_name').val(response.response.name);
                $('#UpdateCustomerUnitModal').modal('show');
            },
            error: function (error) {
                console.log(error);
                toastr.error('Veri Alınırken Serviste Sistemsel Bir Hata Oluştu!');
            }
        });
    }

    function deleteCustomerUnit(id) {
        $('#delete_customer_unit_id').val(id);
        $('#DeleteCustomerUnitModal').modal('show');
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getCustomerUnits();
    }

    function getCustomerUnits() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();
        var keyword = $('#filter_customer_unit_keyword').val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customerUnit.index') }}',
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
                customerUnits.empty();
                $.each(response.response.customerUnits, function (i, customerUnit) {
                    customerUnits.append(`
                    <tr>
                        <td>
                            ${customerUnit.code}
                        </td>
                        <td>
                            ${customerUnit.name}
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-primary" onclick="updateCustomerUnit(${customerUnit.id})" title="Düzenle">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-danger ms-2" onclick="deleteCustomerUnit(${customerUnit.id})" title="Sil">
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

    getCustomerUnits();

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
        getCustomerUnits();
    });

    CreateCustomerUnitButton.click(function () {
        var code = $('#create_customer_unit_code').val();
        var name = $('#create_customer_unit_name').val();

        if (!code) {
            toastr.warning('Birim Kodu Zorunludur!');
        } else if (!name) {
            toastr.warning('Birim Adı Zorunludur!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.customerUnit.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    code: code,
                    name: name,
                },
                success: function () {
                    $('#CreateCustomerUnitModal').modal('hide');
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

    UpdateCustomerUnitButton.click(function () {
        var id = $('#update_customer_unit_id').val();
        var code = $('#update_customer_unit_code').val();
        var name = $('#update_customer_unit_name').val();

        if (!code) {
            toastr.warning('Birim Kodu Zorunludur!');
        } else if (!name) {
            toastr.warning('Birim Adı Zorunludur!');
        } else {
            $.ajax({
                type: 'put',
                url: '{{ route('api.user.customerUnit.update') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    id: id,
                    code: code,
                    name: name,
                },
                success: function () {
                    $('#UpdateCustomerUnitModal').modal('hide');
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

    DeleteCustomerUnitButton.click(function () {
        var id = $('#delete_customer_unit_id').val();
        $.ajax({
            type: 'delete',
            url: '{{ route('api.user.customerUnit.delete') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function () {
                $('#DeleteCustomerUnitModal').modal('hide');
                toastr.success('Birim Silindi!');
                changePage(1);
            },
            error: function (error) {
                console.log(error);
                toastr.error('Birim Silinirken Serviste Bir Hata Oluştu!');
            }
        });
    });

</script>
