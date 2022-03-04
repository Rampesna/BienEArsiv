<script>

    var editRoute = '{{ route('web.user.invoice.edit') }}';

    var invoices = $('#invoices');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var filter_transaction_type_id = $('#filter_transaction_type_id');

    function getTransactionTypes() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transactionType.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                invoice: 1,
            },
            success: function (response) {
                filter_transaction_type_id.empty();
                filter_transaction_type_id.append(`<option value="">Tümü</option>`);
                $.each(response.response, function (i, transactionType) {
                    filter_transaction_type_id.append(`<option value="${transactionType.id}">${transactionType.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
                toastr.error('İşlem Türleri Alınırken Serviste Bir Sorun Oluştu!');
            }
        });
    }

    function getInvoices() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();
        var datetimeStart = $('#filter_datetime_start').val();
        var datetimeEnd = $('#filter_datetime_end').val();
        var typeId = filter_transaction_type_id.val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.invoice.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                pageIndex: pageIndex,
                pageSize: pageSize,
                datetimeStart: datetimeStart,
                datetimeEnd: datetimeEnd,
                typeId: typeId,
            },
            success: function (response) {
                invoices.empty();
                $.each(response.response.invoices, function (i, invoice) {
                    var icon = invoice.type.direction === 0 ?
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
                    invoices.append(`
                    <tr>
                        <td>
                            ${icon}
                            <span class="ms-1">${reformatInvoiceNumber(invoice.datetime, invoice.number)}</span>
                            ${invoice.type ? `<br><span class="badge badge-light-${invoice.type.class} ms-9 mb-3">${invoice.type.name}</span>` : ``}
                            ${invoice.status ? `<br><span class="badge badge-light-${invoice.status.class} ms-9">${invoice.status.name}</span>` : ``}
                        </td>
                        <td class="hideIfMobile">
                            ${invoice.datetime ?? ``}
                        </td>
                        <td>
                            ${reformatNumberToMoney(invoice.price ?? 0)} ₺
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-icon btn-sm" type="button" id="Invoice_${invoice.id}_Dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-th"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="Invoice_${invoice.id}_Dropdown" style="width: 175px">
                                    <a class="dropdown-item cursor-pointer mb-2 py-3 ps-6" href="${editRoute}/${invoice.id}" title="Düzenle"><i class="fas fa-edit me-2 text-primary"></i> <span class="text-dark">Düzenle</span></a>
                                    <a class="dropdown-item cursor-pointer py-3 ps-6" onclick="deleteInvoice(${invoice.id})" title="Düzenle"><i class="fas fa-trash-alt me-3 text-danger"></i> <span class="text-dark">Sil</span></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    `);
                });

                if (response.response.totalCount <= (pageIndex + 1) * pageSize) {
                    pageUpButton.attr('disabled', true);
                } else {
                    pageUpButton.attr('disabled', false);
                }

                checkScreen();
            },
            error: function (error) {
                console.log(error);
                toastr.error('İşlemler Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function createInvoice() {
        window.location.href = '{{ route('web.user.invoice.create') }}';
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getInvoices();
    }

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

    getTransactionTypes();
    getInvoices();

    pageUpButton.click(function () {
        changePage(parseInt(page.html()) + 1);
    });

    pageDownButton.click(function () {
        changePage(parseInt(page.html()) - 1);
    });

    pageSizeSelector.change(function () {
        changePage(1);
    });

    $(document).delegate('.filterInput', 'change', function () {
        changePage(1);
    });

</script>
