@extends('user.layouts.master')
@section('title', 'Cariler | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Cariler</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    @include('user.modules.company.index.components.contextMenu')

    @include('user.modules.company.index.modals.createCompany')

    <div class="">
        <div class="row">
            <div class="col-xl-12 text-end">
                <button class="btn btn-primary" onclick="createCompany()">Yeni Cari Oluştur</button>
            </div>
        </div>
        <br>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group mb-2">
                                <label for="filter_company_keyword" class="d-flex align-items-center fs-6 fw-bold">
                                    <span>Ara</span>
                                </label>
                                <input id="filter_company_keyword" type="text" class="form-control form-control-solid filterInput" placeholder="Anahtar Kelime...">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-6 mb-5">
                            <div class="form-group mb-5">
                                <label for="filter_company_account_type" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Hesap Türü</span>
                                </label>
                                <select id="filter_company_account_type" class="form-select form-select-solid filterInput" data-control="select2" data-hide-search="true" tabindex="-1" aria-hidden="true">
                                    <optgroup label="">
                                        <option value="0">Tümü</option>
                                    </optgroup>
                                    <option value="1">Müşteri</option>
                                    <option value="2">Tedarikçi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group mb-5">
                                <label for="filter_company_balance_type" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Bakiye Durumu</span>
                                </label>
                                <select id="filter_company_balance_type" class="form-select form-select-solid filterInput" data-control="select2" data-hide-search="true" tabindex="-1" aria-hidden="true">
                                    <optgroup label="">
                                        <option value="0">Tümü</option>
                                    </optgroup>
                                    <option value="1">Borçlarımız</option>
                                    <option value="2">Alacaklarımız</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body pt-0">
                    <br>
                    <div class="row">
                        <div class="col-xl-1">
                            <div class="form-group">
                                <label>
                                    <select data-control="select2" id="pageSize" data-hide-search="true" class="form-select border-0">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-11 text-end">
                            <button class="btn btn-sm btn-icon bg-transparent bg-hover-opacity-0 text-dark" id="pageDown" disabled>
                                <i class="fas fa-angle-left"></i>
                            </button>
                            <button class="btn btn-sm btn-icon bg-transparent bg-hover-opacity-0 text-dark cursor-default" disabled>
                                <span class="text-muted" id="page">1</span>
                            </button>
                            <button class="btn btn-sm btn-icon bg-transparent bg-hover-opacity-0 text-dark" id="pageUp">
                                <i class="fas fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                    <hr class="text-muted">
                    <table class="table table-responsive align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                        <tr class="text-start text-dark fw-bolder fs-7 gs-0">
                            <th>Cari Bilgisi</th>
                            <th class="hideIfMobile">E-posta</th>
                            <th class="hideIfMobile">Telefon</th>
                            <th class="text-end">Bakiye</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="companies"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customStyles')
    @include('user.modules.company.index.components.style')
@endsection

@section('customScripts')
    @include('user.modules.company.index.components.script')
@endsection
