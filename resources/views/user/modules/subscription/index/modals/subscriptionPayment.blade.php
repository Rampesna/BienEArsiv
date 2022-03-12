<div class="modal fade show" id="SubscriptionPaymentModal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15">
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Ödeme Yap</h1>
                </div>
                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                    <label for="subscriptionPaymentCreditCardHolderName" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span class="required">Kartın Üzerindeki İsim</span>
                    </label>
                    <input id="subscriptionPaymentCreditCardHolderName" type="text" class="form-control form-control-solid" placeholder="Kartın Üzerindeki İsim">
                </div>
                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
                    <label for="subscriptionPaymentCreditCardNumber" class="required fs-6 fw-bold form-label mb-2">Kart Numarası</label>
                    <div class="position-relative">
                        <input id="subscriptionPaymentCreditCardNumber" type="text" class="form-control form-control-solid creditCardMask" placeholder="Kart Numarası">
                        <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                            <img src="{{ asset('assets/media/svg/card-logos/visa.svg') }}" alt="" class="h-25px">
                            <img src="{{ asset('assets/media/svg/card-logos/mastercard.svg') }}" alt="" class="h-25px">
                        </div>
                    </div>
                </div>
                <div class="row mb-10">
                    <div class="col-md-8 fv-row">
                        <div class="row fv-row fv-plugins-icon-container">
                            <div class="col-6">
                                <label for="subscriptionPaymentCreditCardMonth"></label>
                                <select id="subscriptionPaymentCreditCardMonth" class="form-select form-select-solid select2-hidden-accessible" data-control="select2" data-hide-search="true" data-placeholder="Ay" aria-hidden="true">
                                    <option selected hidden disabled></option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="subscriptionPaymentCreditCardYear"></label>
                                <select id="subscriptionPaymentCreditCardYear" class="form-select form-select-solid select2-hidden-accessible" data-control="select2" data-hide-search="true" data-placeholder="Yıl" aria-hidden="true">
                                    <option selected hidden disabled></option>
                                    @for($yearCounter = intval(date('Y')); $yearCounter <= intval(date('Y')) + 10; $yearCounter++)
                                        <option value="{{ $yearCounter }}">{{ $yearCounter }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 fv-row fv-plugins-icon-container">
                        <div class="position-relative">
                            <label for="subscriptionPaymentCreditCardCvc"></label>
                            <input id="subscriptionPaymentCreditCardCvc" type="text" class="form-control form-control-solid" maxlength="3" placeholder="CVV" name="card_cvv">
                            <div class="position-absolute translate-middle-y end-0 me-3" style="top: 65%">
                                <span class="svg-icon svg-icon-2hx">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M22 7H2V11H22V7Z" fill="black"></path>
                                        <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19ZM14 14C14 13.4 13.6 13 13 13H5C4.4 13 4 13.4 4 14C4 14.6 4.4 15 5 15H13C13.6 15 14 14.6 14 14ZM16 15.5C16 16.3 16.7 17 17.5 17H18.5C19.3 17 20 16.3 20 15.5C20 14.7 19.3 14 18.5 14H17.5C16.7 14 16 14.7 16 15.5Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3">İptal</button>
                    <button type="button" class="btn btn-primary" id="SubscriptionPaymentButton">Satın Al</button>
                </div>
            </div>
        </div>
    </div>
</div>
