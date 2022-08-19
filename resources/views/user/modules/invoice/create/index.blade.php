@extends('user.layouts.master')
@section('title', 'Yeni Fatura Oluştur | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Yeni Fatura Oluştur</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    @include('user.modules.invoice.create.modals.createInvoice')
    @include('user.modules.invoice.create.modals.createNewCompany')
    @include('user.modules.invoice.create.modals.createNewProduct')

    <div class="row">
        <div class="col-xl-4 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 d-grid">
                            <button class="btn btn-sm btn-success" id="CreateButton">Kaydet</button>
                        </div>
                    </div>
                    <hr class="text-muted">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="create_invoice_tax_number" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>VKN/TCKN</span>
                                </label>
                                <input id="create_invoice_tax_number" type="text" class="form-control form-control-sm form-control-solid onlyNumber" maxlength="11" placeholder="VKN/TCKN">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="create_invoice_company_id" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span class="required">Cari</span>
                                </label>
                                <div class="input-group flex-nowrap">
                                    <button class="btn btn-sm btn-icon btn-success" type="button" id="CreateNewCompany">+</button>
                                    <select id="create_invoice_company_id" class="form-control form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Cari Seçin"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="create_invoice_type_id" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span class="required">Fatura Türü</span>
                                </label>
                                <select id="create_invoice_type_id" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Fatura Türü" data-hide-search="true"></select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="create_invoice_company_statement_description" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>Cari Ekstre Açıklama</span>
                                </label>
                                <input id="create_invoice_company_statement_description" type="text" class="form-control form-control-sm form-control-solid" placeholder="Cari Ekstre Açıklama">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="create_invoice_datetime" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span class="required">Fatura Tarihi</span>
                                </label>
                                <input id="create_invoice_datetime" type="datetime-local" class="form-control form-control-sm form-control-solid">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-6 mb-8">
                            <div class="form-group">
                                <label for="create_invoice_currency_id" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span class="required">Döviz Türü</span>
                                </label>
                                <select id="create_invoice_currency_id" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Döviz Türü"></select>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-8">
                            <div class="form-group">
                                <label for="create_invoice_currency" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span class="required">Döviz Kuru</span>
                                </label>
                                <input id="create_invoice_currency" type="text" class="form-control form-control-sm form-control-solid currencyMask" placeholder="Döviz Kuru" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 mb-8">
                            <div class="form-group">
                                <label for="create_invoice_vat_discount_id" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span class="required">Tevkifat</span>
                                </label>
                                <select id="create_invoice_vat_discount_id" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Tevkifat"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-8">
                            <div class="form-group">
                                <label for="create_invoice_waybill_number" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>İrsaliye Numarası</span>
                                </label>
                                <input id="create_invoice_waybill_number" type="text" class="form-control form-control-sm form-control-solid" data-placeholder="Fatura Numarası">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="create_invoice_waybill_datetime" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>İrsaliye Tarihi</span>
                                </label>
                                <input id="create_invoice_waybill_datetime" type="datetime-local" class="form-control form-control-sm form-control-solid">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-6 mb-8">
                            <div class="form-group">
                                <label for="create_invoice_order_number" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>Sipariş Numarası</span>
                                </label>
                                <input id="create_invoice_order_number" type="text" class="form-control form-control-sm form-control-solid" data-placeholder="Fatura Numarası">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="create_invoice_order_datetime" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>Sipariş Tarihi</span>
                                </label>
                                <input id="create_invoice_order_datetime" type="datetime-local" class="form-control form-control-sm form-control-solid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 mb-5">
            <div class="row mb-5">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12" id="invoiceProducts">

                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xl-12">
                                    <button id="NewInvoiceProductButton" class="btn btn-sm btn-success">Yeni Ürün</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-7"></div>
                <div class="col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-10">
                                <div class="col-xl-5">
                                    <label for="subtotalSpan" class="fw-bolder">Ara Toplam: </label>
                                </div>
                                <div class="col-xl-7">
                                    <div class="input-group input-group-sm input-group-solid">
                                        <input id="subtotalSpan" type="text" class="form-control form-control-sm form-control-solid text-end" value="0.00" disabled>
                                        <span class="input-group-text">₺</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-xl-5">
                                    <label for="vatTotalSpan" class="fw-bolder">KDV Toplam: </label>
                                </div>
                                <div class="col-xl-7">
                                    <div class="input-group input-group-sm input-group-solid">
                                        <input id="vatTotalSpan" type="text" class="form-control form-control-sm form-control-solid text-end" value="0.00" disabled>
                                        <span class="input-group-text">₺</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-10" id="vatDiscountDiv">
                                <div class="col-xl-5">
                                    <label for="vatDiscountTotalInput" class="fw-bolder">KDV Tevk.(<span id="vatDiscountRateSpan"></span>): </label>
                                </div>
                                <div class="col-xl-7">
                                    <div class="input-group input-group-sm input-group-solid">
                                        <input id="vatDiscountTotalInput" type="text" class="form-control form-control-sm form-control-solid text-end" value="0.00" disabled>
                                        <span class="input-group-text">₺</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-10 text-muted">
                            <div class="row">
                                <div class="col-xl-5">
                                    <label for="generalTotalSpan" class="fw-bolder">Genel Toplam: </label>
                                </div>
                                <div class="col-xl-7">
                                    <div class="input-group input-group-sm input-group-solid">
                                        <input id="generalTotalSpan" type="text" class="form-control form-control-sm form-control-solid text-end" value="0.00" disabled>
                                        <span class="input-group-text">₺</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customStyles')
    @include('user.modules.invoice.create.components.style')
@endsection

@section('customScripts')
    @include('user.modules.invoice.create.components.script')
@endsection
