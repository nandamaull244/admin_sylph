@extends('layout.app')

@section('title', 'Home Page')

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
            <h6 class="card-title">Storage usage</h6>
            <h4 class="card-text" style="color: #0d6efd">{{ $mediaStorageMb }}</h4>
            <p class="card-text">Jumlah storage yang digunakan</p>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card">
        <div class="card-body">
            <h6 class="card-title">Pendapatan</h6>
            <h4 class="card-text" style="color: #0d6efd">Rp. 1.000.000</h4>
            <p class="card-text">Berdasarkan jumlah user</p>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View by page title and screen class</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title d-flex align-items-center gap-2 mb-5 pb-3">Sessions by
                    device<span>
                        <iconify-icon icon="solar:question-circle-bold" class="fs-7 d-flex text-muted"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success"
                            data-bs-title="Locations"></iconify-icon>
                    </span>
                </h5>
                <div class="row">
                    <div class="col-4">
                        <iconify-icon icon="solar:laptop-minimalistic-line-duotone" class="fs-7 d-flex text-primary">
                        </iconify-icon>
                        <span class="fs-11 mt-2 d-block text-nowrap">Computers</span>
                        <h4 class="mb-0 mt-1">87%</h4>
                    </div>
                    <div class="col-4">
                        <iconify-icon icon="solar:smartphone-line-duotone" class="fs-7 d-flex text-secondary">
                        </iconify-icon>
                        <span class="fs-11 mt-2 d-block text-nowrap">Smartphone</span>
                        <h4 class="mb-0 mt-1">9.2%</h4>
                    </div>
                    <div class="col-4">
                        <iconify-icon icon="solar:tablet-line-duotone" class="fs-7 d-flex text-success"></iconify-icon>
                        <span class="fs-11 mt-2 d-block text-nowrap">Tablets</span>
                        <h4 class="mb-0 mt-1">3.1%</h4>
                    </div>
                </div>

                <div class="vstack gap-4 mt-7 pt-2">
                    <div>
                        <div class="hstack justify-content-between">
                            <span class="fs-3 fw-medium">Computers</span>
                            <h6 class="fs-3 fw-medium text-dark lh-base mb-0">87%</h6>
                        </div>
                        <div class="progress mt-6" role="progressbar" aria-label="Warning example" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="hstack justify-content-between">
                            <span class="fs-3 fw-medium">Smartphones</span>
                            <h6 class="fs-3 fw-medium text-dark lh-base mb-0">9.2%</h6>
                        </div>
                        <div class="progress mt-6" role="progressbar" aria-label="Warning example" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-secondary" style="width: 50%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="hstack justify-content-between">
                            <span class="fs-3 fw-medium">Tablets</span>
                            <h6 class="fs-3 fw-medium text-dark lh-base mb-0">3.1%</h6>
                        </div>
                        <div class="progress mt-6" role="progressbar" aria-label="Warning example" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-success" style="width: 35%"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    @endsection
