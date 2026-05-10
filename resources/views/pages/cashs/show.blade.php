@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
			<!--begin::Input group-->
			<div class="row mb-6">
				<!--begin::Col-->
				<div class="col-md-12 col-12 text-center">
					<!--begin::Image input-->
					<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('storage/' . $query->photo) }}')">
						<!--begin::Preview existing photo-->
						<div class="image-input-wrapper w-200px h-200px" style="background-image: url({{ asset('storage/' . $query->photo) }});background-position: center;"></div>
						<!--end::Preview existing photo-->
					</div>
					<!--end::Image input-->
				</div>
				<!--end::Col-->
			</div>
            <div class="row mb-5">
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Libellé :</label>
                    <input type="text" value="{{ $query->libelle }}" class="form-control" readonly />
                </div>
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Seuil :</label>
                    <input type="text" value="{{ $query->seuil }}" class="form-control" readonly />
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Prix d'achat :</label>
                    <input type="text" value="{{ $query->prix_achat }}" class="form-control" readonly />
                </div>
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Prix de vente :</label>
                    <input type="text" value="{{ $query->prix_vente }}" class="form-control" readonly />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12 col-12">
                    <label class="fw-bolder text-dark fs-5">Description :</label>
                    <textarea class="form-control" readonly>{{ $query->description }}</textarea>
                </div>
            </div>
        </form>
    </div>
  </div>
@endsection