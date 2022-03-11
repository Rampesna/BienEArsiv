<script>

    var subscriptions = $('#subscriptions');

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
            type: 'get',
            url: '{{ route('api.user.subscription.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                subscriptions.empty();
                $.each(response.response, function (i, subscription) {
                    subscriptions.append(`
                    <div class="col-xl-3">
                        <div class="d-flex h-100 align-items-center">
                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
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
                    `);
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function buySubscription(id) {
        $();
    }

    getSubscriptions();

</script>
