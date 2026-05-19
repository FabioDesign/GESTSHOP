@extends('layouts.master')

@section('content')
<div class="row mb-5">
    <div class="col-md-3 col-12">
    <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5 mb-xl-10 h-175px" style="background-color: #7239EA;background-image:url('assets/img/bg-purple.svg')">
            <!--begin::Header-->
            <div class="card-header py-4 fs-3hx fw-bold text-white m-auto px-0">1000</div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0" style="border-top: 1px solid rgba(255, 255, 255, 0.3);background: rgba(0, 0, 0, 0.15);">
                <!--begin::Progress-->
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                        <span>Totaux</span>
                        <span id="total">100%</span>
                    </div>
                    <div class="h-10px mx-3 w-100 bg-white bg-opacity-50 rounded">
                        <div class="bg-white rounded h-10px total" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!--end::Progress-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
    <div class="col-md-3 col-12">
    <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-cover bgi-position-x-end mb-5 mb-xl-10 h-175px" style="background-image:url('/assets/img/bg-green.png')">
            <!--begin::Header-->
            <div class="card-header py-4 fs-3hx fw-bold text-white m-auto px-0">1000</div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0" style="border-top: 1px solid rgba(255, 255, 255, 0.3);background: rgba(0, 0, 0, 0.15);">
                <!--begin::Progress-->
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                        <span>Entrées</span>
                        <span id="cash_in">80%</span>
                    </div>
                    <div class="h-10px mx-3 w-100 bg-white bg-opacity-50 rounded">
                        <div class="bg-white rounded h-10px cash_in" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!--end::Progress-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
    <div class="col-md-3 col-12">
    <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5 mb-xl-10 h-175px" style="background-color: #F1416C;background-image:url('/assets/img/bg-red.png')">
            <!--begin::Header-->
            <div class="card-header py-4 fs-3hx fw-bold text-white m-auto px-0">1000</div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0" style="border-top: 1px solid rgba(255, 255, 255, 0.3);background: rgba(0, 0, 0, 0.15);">
                <!--begin::Progress-->
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                        <span>Sorties</span>
                        <span id="cash_out">60%</span>
                    </div>
                    <div class="h-10px mx-3 w-100 bg-white bg-opacity-50 rounded">
                        <div class="bg-white rounded h-10px cash_out" role="progressbar" style="width: 60%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!--end::Progress-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
    <div class="col-md-3 col-12">
    <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5 mb-xl-10 h-175px" style="background: linear-gradient(112.14deg, #FF8A00 0%, #E96922 100%)">
            <!--begin::Header-->
            <div class="card-header py-4 fs-3hx fw-bold text-white m-auto px-0">1000</div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0" style="border-top: 1px solid rgba(255, 255, 255, 0.3);background: rgba(0, 0, 0, 0.15);">
                <!--begin::Progress-->
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                        <span>Solde</span>
                        <span id="balance">50%</span>
                    </div>
                    <div class="h-10px mx-3 w-100 bg-white bg-opacity-50 rounded">
                        <div class="bg-white rounded h-10px balance" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!--end::Progress-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
</div>
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="rootForm" value="users">
            <span class="msgError" style="display: none;"></span>
            <div class="row mb-5">
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Années : <span class="text-danger">*</span></label>
                    <select id="years" class="form-select requiredField">
						<option value="" selected>Toutes les années</option>
						@foreach($query as $data)
							<option value="{{ $data->years }}">{{ $data->years }}</option>
						@endforeach
					</select>
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Mois : <span class="text-danger">*</span></label>
                    <select id="months" class="form-select requiredField">
						<option value="" selected>Tous les mois</option>
                    </select>
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Jours : <span class="text-danger">*</span></label>
                    <select id="days" class="form-select requiredField">
						<option value="" selected>Tous les jours</option>
                    </select>
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Entrées/Sorties : <span class="text-danger">*</span></label>
                    <select id="type" class="form-select requiredField">
						<option value="" selected>Toutes les Entrées/Sorties</option>
					</select>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
  	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const statsData = async () => {
            try {
                const response = await axios.post('/dashboard', {
                    motif: motif,
                    uid: $('#uid').val()
                });
                return response.data.data || [];
            } catch (e) {
                console.error(e);
                return [];
            }
        }
        $('.total').attr('style', 'width: 100%');
        $('.cash_in').attr('style', 'width: 80%');
        $('.cash_out').attr('style', 'width: 40%');
        $('.balance').attr('style', 'width: 60%');
        $('#total').html('100%');
        $('#cash_in').html('80%');
        $('#cash_out').html('40%');
        $('#balance').html('60%');
    </script>
@endsection