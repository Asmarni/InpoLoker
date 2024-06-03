@extends('layouts.app')

@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5 pb-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('savedJobs') }}">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Kembali
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow border-0 p-3 position-relative">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="company_logo mb-3">
                                        @if (!empty($savedJob->job->company->company_logo))
                                            <img src="{{ asset('storage/' . $savedJob->job->company->company_logo) }}" class="img-fluid" alt="Company Logo">
                                        @else
                                            <img src="{{ asset('images/default-logo.png') }}" class="img-fluid" alt="Company Logo">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h2>{{ $savedJob->job->job_title }}</h2>
                                    <div class="d-flex mb-5">
                                        <div class="location me-3">
                                            <i class="fa fa-map-marker"></i>
                                            <span>{{ $savedJob->job->job_location }}</span>
                                        </div>
                                        <div class="location">
                                            <i class="fa fa-clock"></i>
                                            <span>{{ $savedJob->job->jobType->job_type_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow border-0 ">
                                <div class="row mb-3">
                                    <h4 class="mb-1">Nama Instansi/Perusahaan</h4>
                                    <p>{!! nl2br($savedJob->job->company->company_name) !!}</p>
                                </div>

                                <div class="row mb-3">
                                    <h4 class="mb-1">Deskripsi Pekerjaan</h4>
                                    <p>{!! nl2br($savedJob->job->job_description) !!}</p>
                                </div>

                                @if (!empty($savedJob->job->job_salary))
                                    <div class="row mb-3">
                                        <h4 class="mb-1">Gaji</h4>
                                        <p>{{ $savedJob->job->job_salary }}</p>
                                    </div>
                                @endif

                                @if (!empty($savedJob->job->job_skills))
                                    <div class="row mb-3">
                                        <h4 class="mb-1">Keterampilan yang dibutuhkan</h4>
                                        <p>{{ $savedJob->job->job_skills }}</p>
                                    </div>
                                @endif
                            </div>

                            @auth
                                <div class="card shadow border-0 p-3 mt-4">
                                    <div class="apply_job_area">
                                        <h3>Lamar Pekerjaan Ini</h3>
                                        @if(!isset($alreadyApplied) || !$alreadyApplied) <!-- Periksa jika pengguna belum melamar -->
                                            <form action="{{ route('applyJob', $savedJob->job->id) }}" method="POST" enctype="multipart/form-data" id="applyJobForm">
                                                @csrf

                                                <div class="mb-3 form-check">
                                                    <input type="checkbox" class="form-check-input" id="useExistingFiles" name="use_existing_files">
                                                    <label class="form-check-label" for="useExistingFiles">Gunakan File yang Sudah Ada</label>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="cv" class="form-label">CV (Hanya PDF)</label>
                                                    <input type="file" class="form-control" id="cv" name="cv" @if(old('use_existing_files')) disabled @endif>
                                                    @error('cv')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="certificate" class="form-label">Sertifikat (Hanya PDF)</label>
                                                    <input type="file" class="form-control" id="certificate" name="certificate" @if(old('use_existing_files')) disabled @endif>
                                                    @error('certificate')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-paper-plane"></i>&nbsp; Lamar Sekarang
                                                </button>
                                            </form>
                                        @else
                                            <p>Anda sudah melamar pekerjaan ini.</p>
                                        @endif
                                    </div>
                                </div>

                      
                            @else
                                <div class="alert alert-info mt-4" role="alert">
                                    Silakan <a href="{{ route('loginJobSeeker') }}">login</a> untuk melamar pekerjaan.
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow border-0 p-3">
                        <div class="job_summary">
                            <h3>Ringkasan Pekerjaan</h3>
                            <ul class="list-unstyled">
                                <li>Kategori: <span>{{ $savedJob->job->category->category_name }}</span></li>
                                <li>Tipe Pekerjaan: <span>{{ $savedJob->job->jobType->job_type_name }}</span></li>
                                <li>Lokasi: <span>{{ $savedJob->job->job_location }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const useExistingFiles = document.getElementById('useExistingFiles');
            const cvInput = document.getElementById('cv');
            const certificateInput = document.getElementById('certificate');

            useExistingFiles.addEventListener('change', function () {
                const isChecked = useExistingFiles.checked;

                cvInput.disabled = isChecked;
                certificateInput.disabled = isChecked;
            });

            // Set initial state based on existing file selection
            const initialChecked = useExistingFiles.checked;
            cvInput.disabled = initialChecked;
            certificateInput.disabled = initialChecked;
        });
    </script>
@endpush