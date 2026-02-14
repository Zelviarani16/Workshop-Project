@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center w-100">

        <div>
            <h3 class="page-title mb-0">Dashboard</h3>
            <small class="text-muted">Ringkasan sistem manajemen perpustakaan</small>
        </div>

    </div>

</div>



<div class="row">

    {{-- TOTAL BUKU --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card bg-gradient-primary text-white">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-2">Total Buku</h5>

                        <h2 class="mb-0 font-weight-bold">
                            {{ \App\Models\Buku::count() }}
                        </h2>

                        <small>Jumlah seluruh buku</small>
                    </div>

                    <i class="mdi mdi-book-open-page-variant"
                       style="font-size: 50px; opacity: 0.7;"></i>

                </div>

            </div>

        </div>
    </div>



    {{-- TOTAL KATEGORI --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card bg-gradient-success text-white">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-2">Total Kategori</h5>

                        <h2 class="mb-0 font-weight-bold">
                            {{ \App\Models\Kategori::count() }}
                        </h2>

                        <small>Jumlah seluruh kategori</small>
                    </div>

                    <i class="mdi mdi-tag-multiple"
                       style="font-size: 50px; opacity: 0.7;"></i>

                </div>

            </div>

        </div>
    </div>

</div>



{{-- QUICK ACCESS --}}
<div class="row">

    <div class="col-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-body">

                <h4 class="card-title mb-4">Quick Access</h4>

                <div class="row">

                    <div class="col-md-6">

                        <a href="{{ route('buku.index') }}"
                           class="btn btn-gradient-primary btn-rounded btn-lg w-100 mb-3">

                            <i class="mdi mdi-book-open"></i>
                            Kelola Buku

                        </a>

                    </div>


                    <div class="col-md-6">

                        <a href="{{ route('kategori.index') }}"
                           class="btn btn-gradient-success btn-rounded btn-lg w-100 mb-3">

                            <i class="mdi mdi-tag"></i>
                            Kelola Kategori

                        </a>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>



{{-- STATUS --}}
@if (session('status'))

<div class="row">
    <div class="col-12">
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    </div>
</div>

@endif


@endsection
