@extends('user.layouts.master')
@section('title', 'GİB e-Arşiv Faturalar | ')

@section('subheader')
    @include('user.modules.eInvoice.components.subheader')
@endsection

@section('content')

    @include('user.modules.eInvoice.inbox.modals.eInvoiceHtml')

    <div class="row mb-5">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-5">
                        <h5>Filtreleme Seçenekleri</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 mb-10">
                            <div class="form-group">
                                <label for="filter_datetime_start" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Başlangıç Tarihi</span>
                                </label>
                                <input id="filter_datetime_start" type="date" class="form-control form-control-solid filterInput" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-xl-4 mb-10">
                            <div class="form-group">
                                <label for="filter_datetime_end" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Bitiş Tarihi</span>
                                </label>
                                <input id="filter_datetime_end" type="date" class="form-control form-control-solid filterInput" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-xl-4 mt-8 mb-10">
                            <div class="form-group d-grid">
                                <button class="btn btn-primary" id="FilterButton">Filtrele</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                        <tr class="text-start text-dark fw-bolder fs-7 gs-0">
                            <th>Belge Numarası</th>
                            <th class="hideIfMobile">Satıcı VKN/TCKN</th>
                            <th class="hideIfMobile">Satıcı Ünvan</th>
                            <th class="hideIfMobile">Tarih</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="eInvoices"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customStyles')
    @include('user.modules.eInvoice.inbox.components.style')
@endsection

@section('customScripts')
    @include('user.modules.eInvoice.inbox.components.script')
@endsection
