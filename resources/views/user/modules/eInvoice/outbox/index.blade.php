@extends('user.layouts.master')
@section('title', 'GİB e-Arşiv Faturalar | ')

@section('subheader')
    @include('user.modules.eInvoice.components.subheader')
@endsection

@section('content')

    @include('user.modules.eInvoice.outbox.modals.eInvoiceHtml')

    <a id="eInvoiceDownloadLink" class="d-none" download=""></a>
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
                            <th class="hideIfMobile">Alıcı VKN/TCKN</th>
                            <th class="hideIfMobile">Alıcı Ünvan</th>
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
    @include('user.modules.eInvoice.outbox.components.style')
@endsection

@section('customScripts')
    @include('user.modules.eInvoice.outbox.components.script')
@endsection
