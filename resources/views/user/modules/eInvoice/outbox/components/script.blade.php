<script>

    var FilterButton = $('#FilterButton');
    var ApproveEInvoiceButton = $('#ApproveEInvoiceButton');
    var CancelEInvoiceButton = $('#CancelEInvoiceButton');

    var allEInvoices = [];
    var eInvoices = $('#eInvoices');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var smsCountdown = $('#smsCountdown');

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
                    // console.log(response);
                    allEInvoices = response.response;

                    $.each(allEInvoices, function (i, invoice) {
                        if (invoice.talepDurum && invoice.talepDurum === "1") {
                            if (invoice.iptalItiraz && invoice.iptalItiraz === "1") {
                                invoice.lastStatusColor = "danger";
                                invoice.lastStatus = "İptal Edildi";
                            } else {
                                invoice.lastStatusColor = "warning";
                                invoice.lastStatus = "İptal Bekleniyor";
                            }
                        } else {
                            if (invoice.onayDurumu === "Onaylandı") {
                                invoice.lastStatusColor = "success";
                                invoice.lastStatus = "Onaylandı";
                            } else if (invoice.onayDurumu === "Silinmiş") {
                                invoice.lastStatusColor = "danger";
                                invoice.lastStatus = "Silinmiş";
                            } else {
                                invoice.lastStatusColor = "info";
                                invoice.lastStatus = "Onaylanmadı";
                            }
                        }
                    });

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
        window.open(`{{ route('web.user.eInvoice.invoice') }}/${uuid}`, '_blank');
        {{--$('#loader').fadeIn(250);--}}
        {{--$.ajax({--}}
        {{--    type: 'get',--}}
        {{--    url: '{{ route('api.user.eInvoice.getInvoiceHTML') }}',--}}
        {{--    headers: {--}}
        {{--        'Accept': 'application/json',--}}
        {{--        'Authorization': token--}}
        {{--    },--}}
        {{--    data: {--}}
        {{--        uuid: uuid,--}}
        {{--    },--}}
        {{--    success: function (response) {--}}
        {{--        $('#eInvoiceHtml').html(response.response);--}}
        {{--        $('#EInvoiceHtmlModal').modal('show');--}}
        {{--        $('#loader').fadeOut(250);--}}
        {{--    },--}}
        {{--    error: function (error) {--}}
        {{--        console.log(error);--}}
        {{--        toastr.error('Fatura Görüntülenirken Serviste Bir Hata Oluştu.');--}}
        {{--        $('#loader').fadeOut(250);--}}
        {{--    }--}}
        {{--});--}}
    }

    function sendSmsVerification(uuid) {
        $('#loader').show();
        $('#ApproveEInvoiceModal').modal('show');
        $.ajax({
            type: 'post',
            url: '{{ route('api.user.eInvoice.sendSmsVerification') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                uuid: uuid,
            },
            success: function (oid) {

                console.log(oid);

                smsCountdown.addClass('text-success').html('SMS Gönderildi. 3 Dakika İçerisinde Onaylanması Gerekmektedir!');
                $('#ApproveEInvoiceModal').modal('show');
                $('#approve_e_invoice_oid').val(oid);
                $('#approve_e_invoice_uuid').val(uuid);
                $('#loader').hide();
            },
            error: function (error) {
                console.log(error);
                toastr.error('SMS Doğrulaması Gönderilirken Serviste Bir Hata Oluştu.');
                $('#loader').hide();
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
                var path = '{{ asset('documents/eInvoices/') }}';
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
                        <td class="hideIfMobile text-${eInvoice.lastStatusColor}">${eInvoice.lastStatus}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-icon btn-sm" type="button" id="EInvoice_${eInvoice.id}_Dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-th"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="EInvoice_${eInvoice.id}_Dropdown" style="width: 175px">
                                    ${eInvoice.lastStatus === 'Onaylandı' ? `
                                    <a class="dropdown-item cursor-pointer mb-2 py-3 ps-6" onclick="cancelEInvoice('${eInvoice.ettn}')" title="Faturayı İptal Et"><i class="fas fa-times-circle me-2 text-danger"></i> <span class="text-dark">Faturayı İptal Et</span></a>
                                    <hr>
                                    ` : ``}
                                    ${eInvoice.lastStatus === 'Onaylanmadı' ? `
                                    <a class="dropdown-item cursor-pointer mb-2 py-3 ps-6" onclick="sendSmsVerification('${eInvoice.ettn}')" title="Faturayı Onayla"><i class="fas fa-check-circle me-2 text-success"></i> <span class="text-dark">Faturayı Onayla</span></a>
                                    <hr>
                                    ` : ``}
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

    ApproveEInvoiceButton.click(function () {
        var smsCode = $('#approve_e_invoice_sms_code').val();
        var oid = $('#approve_e_invoice_oid').val();
        var uuid = $('#approve_e_invoice_uuid').val();

        if (!smsCode) {
            toastr.warning('Lütfen SMS Doğrulama Kodunu Giriniz.');
        } else {
            $('#loader').show();
            $('#ApproveEInvoiceModal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.eInvoice.verifySmsCode') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    smsCode: smsCode,
                    oid: oid,
                    uuid: uuid,
                },
                success: function (response) {
                    toastr.success('Fatura Başarıyla Onaylandı.');
                    $('#loader').hide();
                    getEInvoices();
                    changePage(parseInt(page.html()));
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Fatura Onaylanırken Serviste Bir Hata Oluştu.');
                    $('#loader').hide();
                }
            });
        }
    });

    function cancelEInvoice(uuid) {
        $('#cancel_e_invoice_uuid').val(uuid);
        $('#CancelEInvoiceModal').modal('show');
    }

    CancelEInvoiceButton.click(function () {
        $('#loader').show();
        var uuid = $('#cancel_e_invoice_uuid').val();
        $.ajax({
            type: 'post',
            url: '{{ route('api.user.eInvoice.cancelEInvoice') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                uuid: uuid,
            },
            success: function (response) {

                console.log(response);

                toastr.success('Fatura İptal Süreci Başlatıldı.');
                $('#loader').hide();
                getEInvoices();
                changePage(parseInt(page.html()));
            },
            error: function (error) {
                console.log(error);
                toastr.error('Fatura İptal Edilirken Serviste Bir Hata Oluştu.');
                $('#loader').hide();
            }
        });
    });

</script>
