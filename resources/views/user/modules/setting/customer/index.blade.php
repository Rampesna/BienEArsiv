@extends('user.layouts.master')
@section('title', 'Ayarlar / Genel | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-7 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Ayarlar / Genel</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 mb-7">
                            <div class="form-group">
                                <label for="customer_title" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Firma Ünvanı</span>
                                </label>
                                <input id="customer_title" type="text" class="form-control form-control-solid" placeholder="Firma Ünvanı" aria-hidden="true">
                            </div>
                        </div>
                        <div class="col-xl-4 mb-7">
                            <div class="form-group">
                                <label for="customer_tax_office" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Vergi Dairesi</span>
                                </label>
                                <input id="customer_tax_office" type="text" class="form-control form-control-solid" placeholder="Vergi Dairesi" aria-hidden="true">
                            </div>
                        </div>
                        <div class="col-xl-4 mb-7">
                            <div class="form-group">
                                <label for="customer_tax_number" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Vergi Numarası</span>
                                </label>
                                <input id="customer_tax_number" type="text" class="form-control form-control-solid" placeholder="Vergi Numarası" aria-hidden="true">
                            </div>
                        </div>
                        <div class="col-xl-4 mb-7">
                            <div class="form-group">
                                <label for="customer_gib_password" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">GİB Portal Şifresi</span>
                                </label>
                                <input id="customer_gib_password" type="text" class="form-control form-control-solid" placeholder="GİB Portal Şifresi" aria-hidden="true">
                            </div>
                        </div>
                        <div class="col-xl-6 mb-7">
                            <div class="form-group">
                                <label for="customer_phone" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Telefon Numarası</span>
                                </label>
                                <input id="customer_phone" type="text" class="form-control form-control-solid phoneMask" placeholder="Telefon Numarası" aria-hidden="true">
                            </div>
                        </div>
                        <div class="col-xl-6 mb-7">
                            <div class="form-group">
                                <label for="customer_email" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">E-posta Adresi</span>
                                </label>
                                <input id="customer_email" type="text" class="form-control form-control-solid" placeholder="E-posta Adresi" aria-hidden="true">
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
                                    <span class="required">Şehir</span>
                                </label>
                                <select id="customer_province_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="Şehir" aria-hidden="true"></select>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-7">
                            <div class="form-group">
                                <label for="customer_district_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">İlçe</span>
                                </label>
                                <select id="customer_district_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="İlçe" aria-hidden="true"></select>
                            </div>
                        </div>
                    </div>
                    <hr class="text-muted">
                    <div class="row">
                        <div class="col-xl-12 text-end">
                            <button class="btn btn-success" id="UpdateCustomerButton">Güncelle</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customStyles')
    @include('user.modules.setting.customer.components.style')
@endsection

@section('customScripts')
    @include('user.modules.setting.customer.components.script')
@endsection
