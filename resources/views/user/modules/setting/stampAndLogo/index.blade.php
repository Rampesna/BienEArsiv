@extends('user.layouts.master')
@section('title', 'Ayarlar / Kaşe & İmza | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Ayarlar / Kaşe & İmza</h1>
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
                    <div class="row text-center">
                        <div class="col-6 border-right pb-4 pt-4">
                            <h1 class="mb-10">Firma Kaşesi</h1>
                            <img id="customerStamp" alt="" class="img-fluid" style="width: 200px; height: auto">
                            <input type="file" id="customerStampInput" class="d-none">
                        </div>
                        <div class="col-6 border-right pb-4 pt-4">
                            <h1 class="mb-10">Firma Logosu</h1>
                            <img id="customerLogo" alt="" class="img-fluid" style="width: 200px; height: auto">
                            <input type="file" id="customerLogoInput" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customStyles')
    @include('user.modules.setting.stampAndLogo.components.style')
@endsection

@section('customScripts')
    @include('user.modules.setting.stampAndLogo.components.script')
@endsection
