<script>

    var FilterButton = $('#FilterButton');

    var allEInvoices = [];
    var eInvoices = $('#eInvoices');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    function getEInvoices() {
        $('#loader').fadeIn(250);
        setTimeout(function () {
            var dateStart = $('#filter_datetime_start').val();
            var dateEnd = $('#filter_datetime_end').val();

            $.ajax({
                async: false,
                type: 'get',
                url: '{{ route('api.user.eInvoice.outbox') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    dateStart: dateStart,
                    dateEnd: dateEnd,
                },
                success: function (response) {
                    allEInvoices = response.response;
                    changePage(1);
                    $('#loader').fadeOut(250);
                },
                error: function (error) {
                    console.log(error);
                    $('#loader').fadeOut(250);
                }
            });
        }, 500);
    }

    function showEInvoice(uuid) {
        $('#loader').fadeIn(250);
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.eInvoice.getInvoiceHTML') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                uuid: uuid,
            },
            success: function (response) {
                $('#eInvoiceHtml').html(response.response);
                $('#EInvoiceHtmlModal').modal('show');
                $('#loader').fadeOut(250);
            },
            error: function (error) {
                console.log(error);
                toastr.error('Fatura Görüntülenirken Serviste Bir Hata Oluştu.');
                $('#loader').fadeOut(250);
            }
        });
    }

    function downloadEInvoice(uuid) {
        $('#loader').fadeIn(250);
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.eInvoice.getInvoicePDF') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                uuid: uuid,
            },
            success: function () {
                var path = '{{ asset('eInvoices/') }}';
                window.open(path + '/' + uuid + '.pdf', '_blank');
                $('#loader').fadeOut(250);
            },
            error: function (error) {
                console.log(error);
                toastr.error('Fatura İndirilirken Serviste Bir Hata Oluştu.');
                $('#loader').fadeOut(250);
            }
        });
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        paginateEInvoices();
    }

    function paginateEInvoices() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = parseInt(pageSizeSelector.val());

        if ((pageIndex * pageSize) + pageSize >= allEInvoices.length) {
            pageUpButton.attr('disabled', true);
        } else {
            pageUpButton.attr('disabled', false);
        }

        eInvoices.empty();
        for (let page = pageIndex * pageSize; page < (pageIndex * pageSize) + pageSize; page++) {
            var eInvoice = allEInvoices[page];
            if (eInvoice) {
                eInvoices.append(`
                    <tr>
                        <td>${eInvoice.belgeNumarasi}</td>
                        <td class="hideIfMobile">${eInvoice.aliciVknTckn}</td>
                        <td class="hideIfMobile">${eInvoice.aliciUnvanAdSoyad}</td>
                        <td class="hideIfMobile">${eInvoice.belgeTarihi}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-icon btn-sm" type="button" id="EInvoice_${eInvoice.id}_Dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-th"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="EInvoice_${eInvoice.id}_Dropdown" style="width: 175px">
                                    <a class="dropdown-item cursor-pointer mb-2 py-3 ps-6" onclick="showEInvoice('${eInvoice.ettn}')" title="Faturayı Görüntüle"><i class="fas fa-eye me-2 text-info"></i> <span class="text-dark">Faturayı Görüntüle</span></a>
                                    <a class="dropdown-item cursor-pointer mb-2 py-3 ps-6" onclick="downloadEInvoice('${eInvoice.ettn}')" title="Faturayı PDF İndir"><i class="fas fa-file-pdf me-2 text-primary"></i> <span class="text-dark">Faturayı PDF İndir</span></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    `);
            }
        }
    }

    getEInvoices();

    pageUpButton.click(function () {
        changePage(parseInt(page.html()) + 1);
    });

    pageDownButton.click(function () {
        changePage(parseInt(page.html()) - 1);
    });

    pageSizeSelector.change(function () {
        changePage(1);
    });

    FilterButton.click(function () {
        getEInvoices();
    });

</script>
