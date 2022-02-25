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

    <div class="row">
        <div class="col-xl-3 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 d-grid">
                            <button class="btn btn-sm btn-success">Kaydet</button>
                        </div>
                    </div>
                    <hr class="text-muted">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="create_invoice_tax_number" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>VKN/TCKN</span>
                                </label>
                                <input id="create_invoice_tax_number" type="text" class="form-control form-control-sm form-control-solid" placeholder="VKN/TCKN">
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
                                <select id="create_invoice_company_id" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Cari Seçin"></select>
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
                                <select id="create_invoice_type_id" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Fatura Türü"></select>
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
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="create_invoice_number" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>Fatura Numarası</span>
                                </label>
                                <input id="create_invoice_number" type="text" class="form-control form-control-sm form-control-solid" data-placeholder="Fatura Numarası">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="create_invoice_vat_included" class="d-flex align-items-center fs-7 fw-bold mb-2">
                                    <span>Fiyatlara KDV Dahil</span>
                                </label>
                                <select id="create_invoice_vat_included" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Fiyatlara KDV Dahil">
                                    <option value="0">Hayır</option>
                                    <option value="1">Evet</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-6">
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
                        <div class="col-xl-6">
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
        <div class="col-xl-9 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="d-flex align-items-center fs-7 fw-bold mb-2">
                                            <span>Ürün Adı</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <label class="d-flex align-items-center fs-7 fw-bold mb-2">
                                            <span>Miktar</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <label class="d-flex align-items-center fs-7 fw-bold mb-2">
                                            <span>Birim</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <label class="d-flex align-items-center fs-7 fw-bold mb-2">
                                            <span>Birim Fiyat</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-1">
                                    <div class="form-group">
                                        <label class="d-flex align-items-center fs-7 fw-bold mb-2">
                                            <span>KDV</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="row">
                                        <div class="col-xl-10">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center fs-7 fw-bold mb-2">
                                                    <span>Tutar</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

@endsection

@section('customStyles')
    @include('user.modules.invoice.create.components.style')
@endsection

@section('customScripts')
    @include('user.modules.invoice.create.components.script')
@endsection
