@extends('user.layouts.masterBlank')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kurulum Sihirbazı</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid first" id="WizardStepper" data-kt-stepper="true">
                <div class="card d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px w-xxl-400px me-9">
                    <div class="card-body px-6 px-lg-10 px-xxl-15 py-20">
                        <div class="stepper-nav">
                            <div class="stepper-item current" data-kt-stepper-element="nav">
                                <div class="stepper-line w-40px"></div>
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">1</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">Genel Bilgiler</h3>
                                    <div class="stepper-desc fw-bold">Firmanıza Ait Genel Bilgiler</div>
                                </div>
                            </div>
                            <div class="stepper-item pending" data-kt-stepper-element="nav">
                                <div class="stepper-line w-40px"></div>
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">2</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">Gib Bilgileri</h3>
                                    <div class="stepper-desc fw-bold">Vergi Numaranız ve Gib Şifreniz</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card d-flex flex-row-fluid flex-center">
                    <div class="card-body py-20 w-xl-700px px-9 fv-plugins-bootstrap5 fv-plugins-framework" id="WizardStepperForm">
                        <div class="current" data-kt-stepper-element="content">
                            <div class="row mb-10">
                                <div class="col-xl-12 mb-7">
                                    <div class="form-group">
                                        <label for="customer_title" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Firma Ünvanı</span>
                                        </label>
                                        <input id="customer_title" type="text" class="form-control form-control-solid" placeholder="Firma Ünvanı" aria-hidden="true">
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-7">
                                    <div class="form-group">
                                        <label for="customer_email" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Firma E-posta Adresi</span>
                                        </label>
                                        <input id="customer_email" type="text" class="form-control form-control-solid emailMask" placeholder="Firma E-posta Adresi" aria-hidden="true">
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-7">
                                    <div class="form-group">
                                        <label for="customer_phone" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Firma Telefon Numarası</span>
                                        </label>
                                        <input id="customer_phone" type="text" class="form-control form-control-solid phoneMask" placeholder="Firma Telefon Numarası" aria-hidden="true">
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-7">
                                    <div class="form-group">
                                        <label for="customer_address" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Adres</span>
                                        </label>
                                        <textarea id="customer_address" rows="4" type="text" class="form-control form-control-solid" placeholder="Adres" aria-hidden="true"></textarea>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-7">
                                    <div class="form-group">
                                        <label for="customer_province_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span>Şehir</span>
                                        </label>
                                        <select id="customer_province_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="Şehir" aria-hidden="true"></select>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-7">
                                    <div class="form-group">
                                        <label for="customer_district_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span>İlçe</span>
                                        </label>
                                        <select id="customer_district_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="İlçe" aria-hidden="true"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-kt-stepper-element="content" class="pending">
                            <div class="row mb-10">
                                <div class="col-xl-12 mb-7">
                                    <div class="form-group">
                                        <label for="customer_tax_number" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Vergi Numaranız</span>
                                        </label>
                                        <input id="customer_tax_number" type="text" class="form-control form-control-solid" placeholder="Vergi Numaranız" aria-hidden="true">
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-7">
                                    <div class="form-group">
                                        <label for="customer_tax_office" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Vergi Daireniz</span>
                                        </label>
                                        <input id="customer_tax_office" type="text" class="form-control form-control-solid" placeholder="Vergi Daireniz" aria-hidden="true">
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-7">
                                    <div class="form-group">
                                        <label for="customer_gib_password" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Gib Portal Şifreniz</span>
                                        </label>
                                        <input id="customer_gib_password" type="text" class="form-control form-control-solid" placeholder="Gib Portal Şifreniz" aria-hidden="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-stack pt-10">
                            <div class="mr-2">
                                <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="black"></rect>
                                            <path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    Geri
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-lg btn-primary me-3" data-kt-stepper-action="submit" id="CompleteWizardButton">
                                    <span class="indicator-label">Tamamla
                                        <span class="svg-icon svg-icon-3 ms-2 me-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black"></rect>
                                                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">İleri
                                    <span class="svg-icon svg-icon-4 ms-1 me-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black"></rect>
                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customStyles')
    @include('user.modules.wizard.index.components.style')
@endsection

@section('customScripts')
    @include('user.modules.wizard.index.components.script')
@endsection
