@extends('user.layouts.master')
@section('title', 'GİB e-Arşiv Faturalar | ')

@section('subheader')
    @include('user.modules.eInvoice.components.subheader')
@endsection

@section('content')



@endsection

@section('customStyles')
    @include('user.modules.eInvoice.inbox.components.style')
@endsection

@section('customScripts')
    @include('user.modules.eInvoice.inbox.components.script')
@endsection
