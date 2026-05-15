@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="rootForm" value="products">
            <span class="msgError" style="display: none;"></span>
			<!--begin::Input group-->
			<div class="row mb-6">
				<!--begin::Col-->
				<div class="col-md-12 col-12 text-center">
					<!--begin::Image input-->
					<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('/storage/products/default.jpg')">
						<!--begin::Preview existing photo-->
						<div class="image-input-wrapper w-200px h-200px" style="background-image: url(/storage/products/default.jpg);background-position: center;"></div>
						<!--end::Preview existing photo-->
						<!--begin::Label-->
						<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Changer photo">
							<i class="bi bi-pencil-fill fs-7"></i>
							<!--begin::Inputs-->
							<input type="file" name="photo" class="requiredField" accept=".png, .jpg, .jpeg, .svg, .webp" />
							<!--end::Inputs-->
						</label>
						<!--end::Label-->
						<!--begin::Cancel-->
						<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Supprimer photo">
							<i class="bi bi-x fs-2"></i>
						</span>
						<!--end::Cancel-->
						<!--begin::Remove-->
						<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Supprimer photo">
							<i class="bi bi-x fs-2"></i>
						</span>
						<!--end::Remove-->
					</div>
					<!--end::Image input-->
					<!--begin::Hint-->
					<div class="form-text text-danger">Format accepté: png, jpg, jpeg, svg, webp.</div>
					<!--end::Hint-->
				</div>
				<!--end::Col-->
			</div>
            <div class="row mb-5">
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Libellé : <span class="text-danger">*</span></label>
                    <input type="text" name="libelle" class="form-control requiredField" placeholder="Saisir le libellé" />
                </div>
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Seuil : <span class="text-danger">*</span></label>
                    <input type="text" name="seuil" class="form-control requiredField" placeholder="Saisir le seuil" oninput="verif_int(this)" />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Prix d'achat : <span class="text-danger">*</span></label>
                    <input type="text" name="prix_achat" class="form-control requiredField" placeholder="Saisir le prix d'achat" oninput="verif_int(this)" />
                </div>
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Prix de vente : <span class="text-danger">*</span></label>
                    <input type="text" name="prix_vente" class="form-control requiredField" placeholder="Saisir le prix de vente" oninput="verif_int(this)" />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12 col-12">
                    <label class="fw-bolder text-dark fs-5">Description : <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control requiredField" placeholder="Saisir la description"></textarea>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection