@extends('user.layouts.master')
@section('title', 'Gelir & Gider | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Gelir & Gider</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    @include('user.modules.transaction.index.modals.newEarn')
    @include('user.modules.transaction.index.modals.newExpense')

    <div class="row mb-5">
        <div class="col-xl-4 mb-5">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-xl-12 d-grid">
                            <button onclick="newEarn()" class="btn btn-info btn-sm">
                                <span class="svg-icon svg-icon-muted svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M9.60001 11H21C21.6 11 22 11.4 22 12C22 12.6 21.6 13 21 13H9.60001V11Z" fill="black"/>
                                        <path d="M6.2238 13.2561C5.54282 12.5572 5.54281 11.4429 6.22379 10.7439L10.377 6.48107C10.8779 5.96697 11.75 6.32158 11.75 7.03934V16.9607C11.75 17.6785 10.8779 18.0331 10.377 17.519L6.2238 13.2561Z" fill="black"/>
                                        <rect opacity="0.3" x="2" y="4" width="2" height="16" rx="1" fill="black"/>
                                    </svg>
                                </span>
                                <span>Yeni Gelir</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-xl-12 d-grid">
                            <button onclick="newExpense()" class="btn btn-info btn-sm">
                                <span class="svg-icon svg-icon-muted svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M14.4 11H2.99999C2.39999 11 1.99999 11.4 1.99999 12C1.99999 12.6 2.39999 13 2.99999 13H14.4V11Z" fill="black"/>
                                        <path d="M17.7762 13.2561C18.4572 12.5572 18.4572 11.4429 17.7762 10.7439L13.623 6.48107C13.1221 5.96697 12.25 6.32158 12.25 7.03934V16.9607C12.25 17.6785 13.1221 18.0331 13.623 17.519L17.7762 13.2561Z" fill="black"/>
                                        <rect opacity="0.5" width="2" height="16" rx="1" transform="matrix(-1 0 0 1 22 4)" fill="black"/>
                                    </svg>
                                </span>
                                <span>Yeni Gider</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-5">
                        <h5>Filtreleme Seçenekleri</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label for="filter_transaction_type_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">İşlem Türü</span>
                                </label>
                                <select id="filter_transaction_type_id" class="form-select form-select-solid filterInput" data-control="select2" data-hide-search="true"></select>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label for="filter_datetime_start" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Başlangıç Tarihi</span>
                                </label>
                                <input id="filter_datetime_start" type="datetime-local" class="form-control form-control-solid filterInput">
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label for="filter_datetime_end" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Bitiş Tarihi</span>
                                </label>
                                <input id="filter_datetime_end" type="datetime-local" class="form-control form-control-solid filterInput">
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
                            <th class="">İşlem Tarihi</th>
                            <th class="">Dekont Numarası</th>
                            <th class="">Tutar</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="transactions"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customStyles')
    @include('user.modules.transaction.index.components.style')
@endsection

@section('customScripts')
    @include('user.modules.transaction.index.components.script')
@endsection
