<script>

    var transactions = $('#transactions');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var NewEarnButton = $('#NewEarnButton');
    var NewExpenseButton = $('#NewExpenseButton');
    var UpdateSafeboxButton = $('#UpdateSafeboxButton');
    var DeleteSafeboxButton = $('#DeleteSafeboxButton');

    var new_earn_category_id = $('#new_earn_category_id');
    var new_expense_category_id = $('#new_expense_category_id');

    var update_safebox_type_id = $('#update_safebox_type_id');

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getTransactions();
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
                console.log(response);
                new_earn_category_id.empty();
                new_expense_category_id.empty();
                $.each(response.response, function (i, transactionCategory) {
                    new_earn_category_id.append(`<option value="${transactionCategory.id}">${transactionCategory.name}</option>`);
                    new_expense_category_id.append(`<option value="${transactionCategory.id}">${transactionCategory.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Gelir & Gider Kategorileri Alınırken Serviste Bir Hata Oluştu!');
            }
        });
    }

    function getSafeboxTypes() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safeboxType.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                update_safebox_type_id.empty();
                $.each(response.response, function (i, safeboxType) {
                    update_safebox_type_id.append(`<option value="${safeboxType.id}">${safeboxType.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getSafeboxById() {
        var id = '{{ $id }}';
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id
            },
            success: function (response) {
                $('#subheaderSafeboxNameSpan').html(`${response.response.name}`);
                $('#safeboxName').html(`${response.response.name}`);
                $('#balanceSpan').html(`${response.response.balance ? reformatNumberToMoney(response.response.balance) : '0.00'} ₺`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getTransactions() {
        var safeboxId = '{{ $id }}';
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transaction.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                pageIndex: pageIndex,
                pageSize: pageSize,
                safeboxId: safeboxId,
            },
            success: function (response) {
                transactions.empty();
                $.each(response.response.transactions, function (i, transaction) {
                    var icon = transaction.direction === 0 ?
                        `
                        <span class="svg-icon svg-icon-success svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M13.4 10L5.3 18.1C4.9 18.5 4.9 19.1 5.3 19.5C5.7 19.9 6.29999 19.9 6.69999 19.5L14.8 11.4L13.4 10Z" fill="black"/>
                                <path opacity="0.3" d="M19.8 16.3L8.5 5H18.8C19.4 5 19.8 5.4 19.8 6V16.3Z" fill="black"/>
                            </svg>
                        </span>
                        ` :
                        `
                        <span class="svg-icon svg-icon-danger svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.4 14.8L19.5 6.69999C19.9 6.29999 19.9 5.7 19.5 5.3C19.1 4.9 18.5 4.9 18.1 5.3L10 13.4L11.4 14.8Z" fill="black"/>
                                <path opacity="0.3" d="M5 8.5L16.3 19.8H6C5.4 19.8 5 19.4 5 18.8V8.5Z" fill="black"/>
                            </svg>
                        </span>
                        `;
                    transactions.append(`
                    <tr>
                        <td>
                            ${icon}
                            ${transaction.datetime}
                            <br>
                            ${transaction.type ? `<span class="badge badge-light-${transaction.type.class} ms-9">${transaction.type.name}</span>` : ``}
                        </td>
                        <td>
                            ${transaction.receipt_number ?? ``}
                        </td>
                        <td>
                            ${transaction.description ?? ``}
                        </td>
                        <td>
                            ${transaction.amount} ₺
                        </td>
                        <td class="text-end">
                            ${parseInt(transaction.locked) === 0 ? `
                            <button class="btn btn-icon btn-sm btn-primary" title="Düzenle"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-icon btn-sm btn-danger" title="Mutabakat"><i class="fas fa-trash-alt"></i></button>
                            ` : ``}
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
                toastr.error('İşlemler Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function newEarn() {
        $('#new_credit_date').val('');
        $('#new_credit_amount').val('');
        $('#new_credit_description').val('');
        $('#NewEarnModal').modal('show');
    }

    function newExpense() {
        $('#new_debit_date').val('');
        $('#new_debit_amount').val('');
        $('#new_debit_description').val('');
        $('#NewExpenseModal').modal('show');
    }

    function updateSafebox() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: '{{ $id }}'
            },
            success: function (response) {
                update_safebox_type_id.val(response.response.type_id).select2();
                $('#update_safebox_name').val(response.response.name);
                $('#update_safebox_account_number').val(response.response.account_number);
                $('#update_safebox_branch').val(response.response.branch);
                $('#update_safebox_iban').val(response.response.iban);
                $('#UpdateSafeboxModal').modal('show');
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kasa & Banka Bilgileri Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function deleteSafebox() {
        $('#DeleteSafeboxModal').modal('show');
    }

    getSafeboxById();
    getTransactions();
    getTransactionCategories();
    getSafeboxTypes();

    pageUpButton.click(function () {
        changePage(parseInt(page.html()) + 1);
    });

    pageDownButton.click(function () {
        changePage(parseInt(page.html()) - 1);
    });

    pageSizeSelector.change(function () {
        changePage(1);
    });

    NewEarnButton.click(function () {
        var safeboxId = '{{ $id }}';
        var datetime = $('#new_earn_date').val();
        var amount = $('#new_earn_amount').val();
        var categoryId = new_earn_category_id.val();
        var description = $('#new_earn_description').val();

        if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!categoryId) {
            toastr.warning('Kategori Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: null,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 3,
                    categoryId: categoryId,
                    receiptNumber: '',
                    description: description,
                    safeboxId: safeboxId,
                    direction: 0,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#NewEarnModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getSafeboxById();
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Sistemsel Bir Hata Oluştu!');
                }
            });
        }
    });

    NewExpenseButton.click(function () {
        var safeboxId = '{{ $id }}';
        var datetime = $('#new_expense_date').val();
        var amount = $('#new_expense_amount').val();
        var categoryId = new_expense_category_id.val();
        var description = $('#new_expense_description').val();

        if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!categoryId) {
            toastr.warning('Kategori Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: null,
                    invoiceId: null,
                    datetime: datetime,
                    typeId: 4,
                    categoryId: categoryId,
                    receiptNumber: '',
                    description: description,
                    safeboxId: safeboxId,
                    direction: 1,
                    amount: amount,
                    locked: 0,
                },
                success: function () {
                    $('#NewExpenseModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getSafeboxById();
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Sistemsel Bir Hata Oluştu!');
                }
            });
        }
    });

    UpdateSafeboxButton.click(function () {
        var id = '{{ $id }}';
        var typeId = create_safebox_type_id.val();
        var name = $('#create_safebox_name').val();
        var accountNumber = $('#create_safebox_account_number').val();
        var branch = $('#create_safebox_branch').val();
        var iban = $('#create_safebox_iban').val();

        if (!typeId) {
            toastr.warning('Hesap Türü Seçmediniz!');
        } else if (!name) {
            toastr.warning('Hesap Tanımı Girmediniz!');
        } else {
            $.ajax({
                type: 'put',
                url: '{{ route('api.user.safebox.update') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    id: id,
                    typeId: typeId,
                    name: name,
                    accountNumber: accountNumber,
                    branch: branch,
                    iban: iban
                },
                success: function () {
                    $('#UpdateSafeboxModal').modal('hide');
                    toastr.success('Başarıyla Güncellendi!');
                    getSafeboxById();
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Hesap Güncellenirken Serviste Bir Hata Oluştu!');
                }
            });
        }
    });

    DeleteSafeboxButton.click(function () {
        var safeboxId = '{{ $id }}';
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transaction.count') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                safeboxId: safeboxId
            },
            success: function (response) {
                if (response.response > 0) {
                    toastr.warning('İşlem Görmüş Hesap Silinemez.');
                } else {
                    $('#loader').fadeIn(250);
                    $('#DeleteSafeboxModal').modal('hide');
                    $.ajax({
                        type: 'delete',
                        url: '{{ route('api.user.safebox.delete') }}',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': token
                        },
                        data: {
                            id: safeboxId
                        },
                        success: function () {
                            toastr.success('Başarıyla Silindi');
                            window.location.href = '{{ route('web.user.safebox.index') }}';
                        },
                        error: function (error) {
                            console.log(error);
                            toastr.error('Hesap Silinirken Serviste Bir Hata Oluştu.');
                            $('#loader').fadeOut(250);
                        }
                    });
                }
            },
            error: function (error) {
                console.log(error);
                toastr.error('Hesap Silinirken Serviste Bir Hata Oluştu.');
            }
        });
    });

</script>
