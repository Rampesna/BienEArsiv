<script>

    var eInvoices = $('#eInvoices');

    function getEInvoices() {
        var dateStart = '{{ date('Y-m-d') }}';
        var dateEnd = '{{ date('Y-m-d') }}';

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.eInvoice.getInvoices') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                dateStart: dateStart,
                dateEnd: dateEnd,
            },
            success: function (response) {
                eInvoices.empty();
                $.each(response.response, function (i, eInvoice) {
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
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
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

    getEInvoices();

</script>
