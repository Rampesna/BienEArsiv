<script>

    var subscription = $('#subscription');
    var subscriptions = $('#subscriptions');
    var subheaderTitle = $('#subheaderTitle');

    var allSubscriptions = [];
    var selectedSubscription = null;

    var SubscriptionPaymentButton = $('#SubscriptionPaymentButton');

    var subscriptionPaymentCreditCardHolderName = $('#subscriptionPaymentCreditCardHolderName');
    var subscriptionPaymentCreditCardNumber = $('#subscriptionPaymentCreditCardNumber');
    var subscriptionPaymentCreditCardMonth = $('#subscriptionPaymentCreditCardMonth');
    var subscriptionPaymentCreditCardYear = $('#subscriptionPaymentCreditCardYear');
    var subscriptionPaymentCreditCardCvc = $('#subscriptionPaymentCreditCardCvc');

    function getSubscriptions() {
        var successIcon = `
            <span class="svg-icon svg-icon-1 svg-icon-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                    <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black"></path>
                </svg>
            </span>
        `;
        var errorIcon = `
            <span class="svg-icon svg-icon-danger svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                    <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="black"></rect>
                    <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="black"></rect>
                </svg>
            </span>
        `;
        $.ajax({
            async: false,
            type: 'get',
            url: '{{ route('api.user.subscription.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                allSubscriptions = response.response;
                subscriptions.empty();
                $.each(response.response, function (i, subscription) {
                    subscriptions.append(`
                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex h-100 align-items-center">
                                    <div class="w-100 d-flex flex-column flex-center rounded-3 px-10">
                                        <div class="mb-7 text-center">
                                            <h1 class="text-dark mb-5 fw-boldest">${subscription.name}</h1>
                                            <div class="text-center">
                                                <span class="mb-2 text-primary">₺</span>
                                                <span class="fs-3x fw-bolder text-primary">${reformatNumberToMoney(subscription.price)}</span>
                                                <span class="fs-7 fw-bold opacity-50">/
                                                    <span data-kt-element="period">${subscription.duration_of_days} Gün</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="w-100 mb-10">
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3 ms-5" id="safeboxLimitSpan">${subscription.company_limit === -1 ? `Sınırsız` : subscription.company_limit} Cari</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3 ms-5" id="userLimitSpan">${subscription.user_limit === -1 ? `Sınırsız` : subscription.user_limit} Kullanıcı</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3 ms-5" id="invoiceLimitSpan">${subscription.invoice_limit === -1 ? `Sınırsız` : subscription.invoice_limit} Fatura</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3 ms-5" id="transactionLimitSpan">${subscription.transaction_limit === -1 ? `Sınırsız` : subscription.transaction_limit} Gelir & Gider</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-5">
                                                ${subscription.order_management === 1 ? successIcon : errorIcon}
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3 ms-5" id="orderManagementSpan">Sipariş Yönetimi</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-5">
                                                ${subscription.product_management === 1 ? successIcon : errorIcon}
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3 ms-5" id="productManagementSpan">Ürün Yönetimi</span>
                                            </div>
                                        </div>
                                        <a href="#" class="btn btn-sm btn-primary" onclick="buySubscription(${subscription.id})">Satın Al</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `);
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getSubscription() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.customerSubscription.check') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                if (!response.response) {
                    subheaderTitle.html('Paketler');
                    getSubscriptions();
                } else {
                    subheaderTitle.html('Hesabım');
                    subscription.append(`
                    <div class="col-xl-4">
                        <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="subscription-summary" data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Aktif Hesap</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0 fs-6">
                                <div class="separator separator-dashed mb-7"></div>
                                <div class="mb-7">
                                    <h5 class="mb-4">Paket Detayları</h5>
                                    <div class="mb-0">
                                        <span class="badge badge-light-info me-2" id="subscriptionNameSpan">${response.response.subscription.name}</span>
                                        <span class="fw-bold text-gray-600" id="subscriptionPriceSpan">₺ ${response.response.subscription.price} / ${response.response.subscription.duration_of_days} Gün</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-7"></div>
                                <div class="mb-10">
                                    <h5 class="mb-4">Ödeme Detayları</h5>
                                    <table class="table fs-6 fw-bold gs-0 gy-2 gx-2">
                                        <tbody><tr class="">
                                            <td class="text-gray-400">Sipariş Numarası:</td>
                                            <td class="text-gray-800" id="subscriptionPaymentOrderId">${String(response.response.payment.order_id).replaceAll('order_', '')}</td>
                                        </tr>
                                        <tr class="">
                                            <td class="text-gray-400">Aktivasyon Başlangıcı:</td>
                                            <td class="text-gray-800" id="subscriptionStartDate">${reformatDatetimeTo_DD_MM_YYYY_WithDot(response.response.subscription_start_date)}</td>
                                        </tr>
                                        <tr class="">
                                            <td class="text-gray-400">Durum:</td>
                                            <td>
                                                <span class="badge badge-light-success">Aktif</span>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td class="text-gray-400">Aktivasyon Bitişi:</td>
                                            <td class="text-gray-800" id="subscriptionExpiryDate">${reformatDatetimeTo_DD_MM_YYYY_WithDot(response.response.subscription_expiry_date)}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    `);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function buySubscription(id) {
        selectedSubscription = allSubscriptions.find(subscription => subscription.id === id);
        $('#SubscriptionPaymentModal').modal('show');
    }

    getSubscription();

    SubscriptionPaymentButton.click(function () {
        var subscriptionId = selectedSubscription.id;
        var creditCardHolderName = subscriptionPaymentCreditCardHolderName.val();
        var creditCardNumber = subscriptionPaymentCreditCardNumber.val().replaceAll(' ', '').replaceAll('_', '');
        var creditCardMonth = subscriptionPaymentCreditCardMonth.val();
        var creditCardYear = subscriptionPaymentCreditCardYear.val();
        var creditCardCvc = subscriptionPaymentCreditCardCvc.val();

        if (!subscriptionId) {
            toastr.warning('Paket Seçiminde Hata Var. Lütfen Sayfayı Yenileyip Tekrar Deneyin.');
        } else if (!creditCardHolderName) {
            subscriptionPaymentCreditCardHolderName.addClass('is-invalid');
            subscriptionPaymentCreditCardHolderName.focus();
        } else if (!creditCardNumber || creditCardNumber.length < 16) {
            subscriptionPaymentCreditCardNumber.focus();
        } else if (!creditCardMonth) {
            subscriptionPaymentCreditCardMonth.focus();
        } else if (!creditCardYear) {
            subscriptionPaymentCreditCardYear.focus();
        } else if (!creditCardCvc) {
            subscriptionPaymentCreditCardCvc.focus();
        } else {
            $('#loader').fadeIn(250);
            $('#SubscriptionPaymentModal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.subscriptionPayment.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    subscriptionId: subscriptionId,
                    creditCardHolderName: creditCardHolderName,
                    creditCardNumber: creditCardNumber,
                    creditCardMonth: creditCardMonth,
                    creditCardYear: creditCardYear,
                    creditCardCvc: creditCardCvc
                },
                success: function (response) {
                    window.location.href = response.response.paramServicePosPaymentResponse.Pos_OdemeResult.UCD_URL;
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Bir Hata Oluştu. Lütfen Sayfaı Yenileyip Tekrar Deneyin.');
                }
            });
        }
    });

    subscriptionPaymentCreditCardHolderName.keydown(function () {
        subscriptionPaymentCreditCardHolderName.removeClass('is-invalid');
    });

</script>

<script>

    @if(isset($type) && $type === 'success' && isset($message))
    toastr.{{ $type }}("{{ $message }}");
    @endif

</script>
