@extends('admin.layouts.master')
@section('title', 'Kullanıcılar | ')

@section('subheader')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <a href="{{ route('web.admin.dashboard.index') }}" class="fas fa-lg fa-backward cursor-pointer me-5"></a>
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kullanıcılar</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
@endsection

@section('content')
    @include('admin.modules.user.index.modals.transactions')
    @include('admin.modules.user.index.modals.editUser')
    @include('admin.modules.user.index.modals.createSubscription')
    <br>
    <div class="row">
        <div class="col-xl-12">
            <div id="usersList">

            </div>
        </div>
    </div>


@endsection

@section('customStyles')
    @include('admin.modules.user.index.components.style')
@endsection

@section('customScripts')
    @include('admin.modules.user.index.components.script')
@endsection
