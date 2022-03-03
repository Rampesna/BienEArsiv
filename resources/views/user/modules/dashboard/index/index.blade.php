@extends('user.layouts.master')
@section('title', 'Anasayfa | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Anasayfa</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-9 col-lg-12">
            <div class="row">
                <div class="col-xl-5 col-md-12">
                    <div class="row">
                        <div class="col-xl-12 mb-5">
                            <div class="card">
                                <div class="card-header pt-7">
                                    <h4>Finansal Durum</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 border-right pb-4 pt-4">
                                            <h6>Kasa Hesabı</h6>
                                            <span class="svg-icon svg-icon-primary svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M6 20C6 20.6 5.6 21 5 21C4.4 21 4 20.6 4 20H6ZM18 20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20H18Z" fill="black"/>
                                                    <path opacity="0.3" d="M21 20H3C2.4 20 2 19.6 2 19V3C2 2.4 2.4 2 3 2H21C21.6 2 22 2.4 22 3V19C22 19.6 21.6 20 21 20ZM12 10H10.7C10.5 9.7 10.3 9.50005 10 9.30005V8C10 7.4 9.6 7 9 7C8.4 7 8 7.4 8 8V9.30005C7.7 9.50005 7.5 9.7 7.3 10H6C5.4 10 5 10.4 5 11C5 11.6 5.4 12 6 12H7.3C7.5 12.3 7.7 12.5 8 12.7V14C8 14.6 8.4 15 9 15C9.6 15 10 14.6 10 14V12.7C10.3 12.5 10.5 12.3 10.7 12H12C12.6 12 13 11.6 13 11C13 10.4 12.6 10 12 10Z" fill="black"/>
                                                    <path d="M18.5 11C18.5 10.2 17.8 9.5 17 9.5C16.2 9.5 15.5 10.2 15.5 11C15.5 11.4 15.7 11.8 16 12.1V13C16 13.6 16.4 14 17 14C17.6 14 18 13.6 18 13V12.1C18.3 11.8 18.5 11.4 18.5 11Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-dark mt-4" id="totalBalanceForCase" style="font-size: 30px">--</h4>
                                        </div>
                                        <div class="col-6 border-right pb-4 pt-4">
                                            <h6>Banka Hesabı</h6>
                                            <span class="svg-icon svg-icon-success svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M20 19.725V18.725C20 18.125 19.6 17.725 19 17.725H5C4.4 17.725 4 18.125 4 18.725V19.725H3C2.4 19.725 2 20.125 2 20.725V21.725H22V20.725C22 20.125 21.6 19.725 21 19.725H20Z" fill="black"/>
                                                    <path opacity="0.3" d="M22 6.725V7.725C22 8.325 21.6 8.725 21 8.725H18C18.6 8.725 19 9.125 19 9.725C19 10.325 18.6 10.725 18 10.725V15.725C18.6 15.725 19 16.125 19 16.725V17.725H15V16.725C15 16.125 15.4 15.725 16 15.725V10.725C15.4 10.725 15 10.325 15 9.725C15 9.125 15.4 8.725 16 8.725H13C13.6 8.725 14 9.125 14 9.725C14 10.325 13.6 10.725 13 10.725V15.725C13.6 15.725 14 16.125 14 16.725V17.725H10V16.725C10 16.125 10.4 15.725 11 15.725V10.725C10.4 10.725 10 10.325 10 9.725C10 9.125 10.4 8.725 11 8.725H8C8.6 8.725 9 9.125 9 9.725C9 10.325 8.6 10.725 8 10.725V15.725C8.6 15.725 9 16.125 9 16.725V17.725H5V16.725C5 16.125 5.4 15.725 6 15.725V10.725C5.4 10.725 5 10.325 5 9.725C5 9.125 5.4 8.725 6 8.725H3C2.4 8.725 2 8.325 2 7.725V6.725L11 2.225C11.6 1.925 12.4 1.925 13.1 2.225L22 6.725ZM12 3.725C11.2 3.725 10.5 4.425 10.5 5.225C10.5 6.025 11.2 6.725 12 6.725C12.8 6.725 13.5 6.025 13.5 5.225C13.5 4.425 12.8 3.725 12 3.725Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-dark mt-4" id="totalBalanceForBank" style="font-size: 30px">--</h4>
                                        </div>
                                    </div>
                                    <hr class="text-muted">
                                    <h4 class="mt-10">Gelir & Gider (Aylık)</h4>
                                    <div class="row text-center">
                                        <div class="col-6 border-right pb-4 pt-4">
                                            <h6>Gelir</h6>
                                            <span class="svg-icon svg-icon-info svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M11.4 14.8L19.5 6.69999C19.9 6.29999 19.9 5.7 19.5 5.3C19.1 4.9 18.5 4.9 18.1 5.3L10 13.4L11.4 14.8Z" fill="black"/>
                                                    <path opacity="0.3" d="M5 8.5L16.3 19.8H6C5.4 19.8 5 19.4 5 18.8V8.5Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-dark mt-4" id="totalEarnings" style="font-size: 30px">--</h4>
                                        </div>
                                        <div class="col-6 border-right pb-4 pt-4">
                                            <h6>Gider</h6>
                                            <span class="svg-icon svg-icon-danger svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M13.4 10L5.3 18.1C4.9 18.5 4.9 19.1 5.3 19.5C5.7 19.9 6.29999 19.9 6.69999 19.5L14.8 11.4L13.4 10Z" fill="black"/>
                                                    <path opacity="0.3" d="M19.8 16.3L8.5 5H18.8C19.4 5 19.8 5.4 19.8 6V16.3Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-dark mt-4" id="totalExpenses" style="font-size: 30px">--</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 mb-5">
                            <div class="card">
                                <div class="card-header pt-7">
                                    <h4>Cari Durum</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 border-right pb-4 pt-4">
                                            <h6>Toplam Alacak</h6>
                                            <span class="svg-icon svg-icon-success svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M17.1 15.8C16.6 15.7 16 16 15.9 16.5C15.7 17.4 14.9 18 14 18H6C4.9 18 4 17.1 4 16V8C4 6.9 4.9 6 6 6H14C15.1 6 16 6.9 16 8V9.4H18V8C18 5.8 16.2 4 14 4H6C3.8 4 2 5.8 2 8V16C2 18.2 3.8 20 6 20H14C15.8 20 17.4 18.8 17.9 17.1C17.9 16.5 17.6 16 17.1 15.8Z" fill="black"/>
                                                    <path opacity="0.3" d="M11.9 9.39999H21.9L17.6 13.7C17.2 14.1 16.6 14.1 16.2 13.7L11.9 9.39999Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-success mt-2" id="totalCredit" style="font-size: 30px">--</h4>
                                        </div>
                                        <div class="col-6 border-right pb-4 pt-4">
                                            <h6>Toplam Borç</h6>
                                            <span class="svg-icon svg-icon-danger svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M6.8 15.8C7.3 15.7 7.9 16 8 16.5C8.2 17.4 8.99999 18 9.89999 18H17.9C19 18 19.9 17.1 19.9 16V8C19.9 6.9 19 6 17.9 6H9.89999C8.79999 6 7.89999 6.9 7.89999 8V9.4H5.89999V8C5.89999 5.8 7.69999 4 9.89999 4H17.9C20.1 4 21.9 5.8 21.9 8V16C21.9 18.2 20.1 20 17.9 20H9.89999C8.09999 20 6.5 18.8 6 17.1C6 16.5 6.3 16 6.8 15.8Z" fill="black"/>
                                                    <path opacity="0.3" d="M12 9.39999H2L6.3 13.7C6.7 14.1 7.3 14.1 7.7 13.7L12 9.39999Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-danger mt-2" id="totalDebit" style="font-size: 30px">--</h4>
                                        </div>
                                    </div>
                                    <div class="mt-20"></div>
                                    <div class="row text-center">
                                        <div class="col-6 border-right pb-4 pt-4 mt-3">
                                            <h6>Cari Bakiye</h6>
                                            <span class="svg-icon svg-icon-success svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z" fill="black"/>
                                                    <path opacity="0.3" d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z" fill="black"/>
                                                    <path d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-dark mt-4" id="totalBalance" style="font-size: 30px">--</h4>
                                        </div>
                                        <div class="col-6 border-right pb-4 pt-4">
                                            <h6>Cari Durum</h6>
                                            <span class="svg-icon svg-icon-dark svg-icon-3x">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M21 10H13V11C13 11.6 12.6 12 12 12C11.4 12 11 11.6 11 11V10H3C2.4 10 2 10.4 2 11V13H22V11C22 10.4 21.6 10 21 10Z" fill="black"/>
                                                    <path opacity="0.3" d="M12 12C11.4 12 11 11.6 11 11V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V11C13 11.6 12.6 12 12 12Z" fill="black"/>
                                                    <path opacity="0.3" d="M18.1 21H5.9C5.4 21 4.9 20.6 4.8 20.1L3 13H21L19.2 20.1C19.1 20.6 18.6 21 18.1 21ZM13 18V15C13 14.4 12.6 14 12 14C11.4 14 11 14.4 11 15V18C11 18.6 11.4 19 12 19C12.6 19 13 18.6 13 18ZM17 18V15C17 14.4 16.6 14 16 14C15.4 14 15 14.4 15 15V18C15 18.6 15.4 19 16 19C16.6 19 17 18.6 17 18ZM9 18V15C9 14.4 8.6 14 8 14C7.4 14 7 14.4 7 15V18C7 18.6 7.4 19 8 19C8.6 19 9 18.6 9 18Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <h4 class="fs-3 font-weight-bold text-dark mt-8" id="balanceStatus" style="font-size: 30px">--</h4>
                                        </div>
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
    @include('user.modules.dashboard.index.components.style')
@endsection

@section('customScripts')
    @include('user.modules.dashboard.index.components.script')
@endsection
