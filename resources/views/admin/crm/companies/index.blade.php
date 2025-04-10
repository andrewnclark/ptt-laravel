@extends('layouts.admin')

@section('title', 'Companies')
@section('header', 'Companies')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <livewire:admin.crm.company-manager />
    </div>
@endsection 