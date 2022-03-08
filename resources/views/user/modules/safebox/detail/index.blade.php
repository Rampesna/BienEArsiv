@extends('user.layouts.master')
@section('title', 'Cariler | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1" id="subheaderSafeboxNameSpan"></h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    @include('user.modules.safebox.detail.modals.newEarn')
    @include('user.modules.safebox.detail.modals.newExpense')
    @include('user.modules.safebox.detail.modals.updateSafebox')
    @include('user.modules.safebox.detail.modals.deleteSafebox')

    <div class="row">
        <div class="col-xl-7 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <h4>Kasa Bilgileri</h4>
                        </div>
                        <div class="col-xl-6 text-end">
                            <button class="btn btn-success py-2 px-3">
                                <i class="fa fa-long-arrow-alt-right"></i>
                                <span class="fs-7">Hareketler</span>
                            </button>
                        </div>
                    </div>
                    <hr class="text-muted">
                    <div class="row mt-10">
                        <div class="col-xl-12">
                            <h1 class="text-muted" id="safeboxName"></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <h4>Kasa Detayı</h4>
                        </div>
                        <div class="col-xl-6 text-end">
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-icon btn-active-primary btn-active-color- border-0 me-n3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <span class="svg-icon svg-icon-primary svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="5" y="5" width="5" height="5" rx="1" fill="#000000"></rect>
                                                <rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3"></rect>
                                                <rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3"></rect>
                                                <rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3"></rect>
                                            </g>
                                        </svg>
                                    </span>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true" style="">
                                    <div class="menu-item px-3">
                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Yeni İşlem</div>
                                    </div>
                                    <div class="separator border-gray-200 mb-2"></div>
                                    <div class="menu-item px-3">
                                        <a onclick="newEarn()" class="menu-link px-3">
                                            <i class="far fa-arrow-alt-circle-right text-success"></i>
                                            <span class="ms-5">Yeni Gelir</span>
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a onclick="newExpense()" class="menu-link px-3">
                                            <i class="far fa-arrow-alt-circle-left text-danger"></i>
                                            <span class="ms-5">Yeni Gider</span>
                                        </a>
                                    </div>
                                    <div class="separator border-gray-200 mb-2"></div>
                                    <div class="menu-item px-3">
                                        <a onclick="updateSafebox()" class="menu-link px-3">
                                            <i class="fas fa-edit text-primary"></i>
                                            <span class="ms-5">Düzenle</span>
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a onclick="deleteSafebox()" class="menu-link px-3">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                            <span class="ms-5">Sil</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr class="text-muted">
                    <div class="row mt-11 text-end">
                        <div class="col-xl-6">

                        </div>
                        <div class="col-xl-6">
                            <div class="row">
                                <span class="fs-5 fw-bolder">Bakiye</span>
                                <span class="fs-3x fw-bolder mt-3" id="balanceSpan">0.00 ₺</span>
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
                            <th class="">Açıklama</th>
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
    @include('user.modules.safebox.detail.components.style')
@endsection

@section('customScripts')
    @include('user.modules.safebox.detail.components.script')
@endsection
