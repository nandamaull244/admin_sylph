@extends('layout.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Jumlah akun</h4>
                <h3 class="card-text" style="color: #0d6efd">{{ $userCount }}</h3>
                <p class="card-text">Jumlah akun yang terdaftar</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Jumlah artwork</h6>
                <h4 class="card-text" style="color: #0d6efd">{{ $artworkCount }}</h4>
                <p class="card-text">Jumlah artwork yang terdaftar</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Jumlah Image</h6>
                <h4 class="card-text" style="color: #0d6efd">{{ $imageTargetCount }}</h4>
                <p class="card-text">Jumlah image target yang terdaftar</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Pendapatan</h6>
                <h4 class="card-text" style="color: #0d6efd">Rp.
                    {{ number_format($totalRevenue, 0, ',', '.') }}
                </h4>
                <p class="card-text">Berdasarkan jumlah artwork</p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                    User Traffic Overview 
                    <span>
                        <iconify-icon icon="solar:question-circle-bold" class="fs-7 d-flex text-muted"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success"
                            data-bs-title="Traffic Overview"></iconify-icon>
                    </span>
                </h5>
                <div id="traffic-overview">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                    Jumlah download

                </h5>
                <div class="row text-center mb-4">
                    <div class="col-6">
                        <iconify-icon icon="mdi:android" class="fs-7 text-secondary" style="font-size: 3rem;">
                        </iconify-icon>
                        <div class="fs-6 mt-2 text-nowrap">Android</div>
                        <h4 class="mb-0 mt-1">9.2%</h4>
                    </div>
                    <div class="col-6">
                        <iconify-icon icon="mdi:apple" class="fs-7 text-success" style="font-size: 3rem;">
                        </iconify-icon>
                        <div class="fs-6 mt-2 text-nowrap">iOS</div>
                        <h4 class="mb-0 mt-1">3.1%</h4>
                    </div>
                </div>
                <div class="vstack gap-3">
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-6 fw-medium">Android</span>
                            <span class="fs-6 fw-medium text-dark">9.2%</span>
                        </div>
                        <div class="progress mt-2" role="progressbar" aria-label="Android" aria-valuenow="9"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-secondary" style="width: 50%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-6 fw-medium">iOS</span>
                            <span class="fs-6 fw-medium text-dark">3.1%</span>
                        </div>
                        <div class="progress mt-2" role="progressbar" aria-label="iOS" aria-valuenow="3"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-success" style="width: 35%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @endsection
