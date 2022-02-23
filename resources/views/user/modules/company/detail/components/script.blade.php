<script>

    var transactions = $('#transactions');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var new_collection_safebox_id = $('#new_collection_safebox_id');
    var new_payment_safebox_id = $('#new_payment_safebox_id');

    var NewCreditButton = $('#NewCreditButton');
    var NewDebitButton = $('#NewDebitButton');
    var NewCollectionButton = $('#NewCollectionButton');
    var NewPaymentButton = $('#NewPaymentButton');
    var UpdateCompanyButton = $('#UpdateCompanyButton');
    var DeleteCompanyButton = $('#DeleteCompanyButton');

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getTransactions();
    }

    function getCompanyById() {
        var id = '{{ $id }}';
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id
            },
            success: function (response) {
                $('#subheaderCompanyTitleSpan').html(`${response.response.tax_number ? `#${response.response.tax_number} - ` : ``}${response.response.title}`);
                $('#companyTitle').html(`${response.response.title}`);
                $('#companyTaxNumber').html(`${response.response.tax_number ?? '--'}`);
                $('#companyTaxOffice').html(`${response.response.tax_office ?? '--'}`);
                $('#companyEmail').html(`${response.response.email ?? '--'}`);
                $('#companyPhone').html(`${response.response.phone ?? '--'}`);
                $('#companyAddress').html(`${response.response.address ?? ''}`);
                $('#balanceSpan').html(`${response.response.balance ? reformatNumberToMoney(response.response.balance) : '0.00'} ₺`);
            },
            error: function (error) {
                console.log(error);
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
                new_collection_safebox_id.empty();
                new_payment_safebox_id.empty();
                new_collection_safebox_id.append(`<option value="" selected disabled></option>`);
                new_payment_safebox_id.append(`<option value="" selected disabled></option>`);
                $.each(response.response, function (i, safebox) {
                    new_collection_safebox_id.append(`<option value="${safebox.id}">${safebox.name}</option>`);
                    new_payment_safebox_id.append(`<option value="${safebox.id}">${safebox.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kasa & Banka Listesi Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function getTransactions() {
        var companyId = '{{ $id }}';
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
                companyId: companyId,
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
                            <button class="btn btn-icon btn-sm btn-primary" title="Düzenle"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-icon btn-sm btn-danger" title="Mutabakat"><i class="fas fa-trash-alt"></i></button>
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

    function newCredit() {
        $('#new_credit_date').val('');
        $('#new_credit_amount').val('');
        $('#new_credit_description').val('');
        $('#NewCreditModal').modal('show');
    }

    function newDebit() {
        $('#new_debit_date').val('');
        $('#new_debit_amount').val('');
        $('#new_debit_description').val('');
        $('#NewDebitModal').modal('show');
    }

    function newCollection() {
        getSafeboxes();
        $('#new_collection_datetime').val('');
        $('#new_collection_amount').val('');
        $('#new_collection_description').val('');
        $('#NewCollectionModal').modal('show');
    }

    function newPayment() {
        getSafeboxes();
        $('#new_payment_datetime').val('');
        $('#new_payment_amount').val('');
        $('#new_payment_description').val('');
        $('#NewPaymentModal').modal('show');
    }

    function editCompany() {
        $('#EditCompanyModal').modal('show');
    }

    function deleteCompany() {
        $('#DeleteCompanyModal').modal('show');
    }

    getCompanyById();
    getTransactions();

    pageUpButton.click(function () {
        changePage(parseInt(page.html()) + 1);
    });

    pageDownButton.click(function () {
        changePage(parseInt(page.html()) - 1);
    });

    pageSizeSelector.change(function () {
        changePage(1);
    });

    NewCreditButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_credit_date').val();
        var amount = $('#new_credit_amount').val();
        var description = $('#new_credit_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.createCredit') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    datetime: datetime,
                    amount: amount,
                    description: description,
                },
                success: function () {
                    $('#NewCreditModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

    NewDebitButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_debit_date').val();
        var amount = $('#new_debit_amount').val();
        var description = $('#new_debit_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.createDebit') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    datetime: datetime,
                    amount: amount,
                    description: description,
                },
                success: function () {
                    $('#NewDebitModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

    NewCollectionButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_collection_datetime').val();
        var amount = $('#new_collection_amount').val();
        var safeboxId = new_collection_safebox_id.val();
        var description = $('#new_collection_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.createCollection') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    datetime: datetime,
                    amount: amount,
                    safeboxId: safeboxId,
                    description: description,
                },
                success: function () {
                    $('#NewCollectionModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

    NewPaymentButton.click(function () {
        var companyId = '{{ $id }}';
        var datetime = $('#new_payment_datetime').val();
        var amount = $('#new_payment_amount').val();
        var safeboxId = new_payment_safebox_id.val();
        var description = $('#new_payment_description').val();

        if (!companyId) {
            toastr.warning('Firma Seçimi Yapılmadı');
        } else if (!datetime) {
            toastr.warning('Tarih Seçmediniz!');
        } else if (!amount) {
            toastr.warning('Tutar Girmediniz!');
        } else if (!safeboxId) {
            toastr.warning('Kasa & Banka Seçmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.transaction.createPayment') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    companyId: companyId,
                    datetime: datetime,
                    amount: amount,
                    safeboxId: safeboxId,
                    description: description,
                },
                success: function () {
                    $('#NewPaymentModal').modal('hide');
                    toastr.success('İşlem Başarılı');
                    changePage(1);
                    getCompanyById();
                },
                error: function (error) {
                    console.log(error);
                    if (error.status === 404 || error.status === 403) {
                        toastr.error('Cari Bulunamadı');
                    } else {
                        toastr.error('Sistemsel Bir Hata Oluştu!');
                    }
                }
            });
        }
    });

</script>
