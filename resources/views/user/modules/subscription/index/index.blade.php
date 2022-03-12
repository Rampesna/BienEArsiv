@extends('user.layouts.master')
@section('title', 'Paketler | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1" id="subheaderTitle"></h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')

    @include('user.modules.subscription.index.modals.subscriptionPayment')

    <div class="row" id="subscription"></div>
    <div class="row g-10" id="subscriptions"></div>

@endsection

@section('customStyles')
    @include('user.modules.subscription.index.components.style')
@endsection

@section('customScripts')
    @include('user.modules.subscription.index.components.script')
@endsection
