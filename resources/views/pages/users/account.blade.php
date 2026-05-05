@extends('layouts.master')

@section('content')
<div class="card">
	<!--begin::Card body-->
    <div class="card-body py-4">
		<!--begin::Form-->
		<form class="formField">
			@method('PUT')
			<input type="hidden" name="account">
			<input type="hidden" id="rootForm" value="users/{{ $query->uid }}">
			<span class="msgError" style="display: none;"></span>
			<!--begin::Input group-->
			<div class="row mb-6">
				<!--begin::Col-->
				<div class="col-md-12 col-12 text-center">
					<!--begin::Image input-->
					<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('storage/' . $query->avatar) }}')">
						<!--begin::Preview existing avatar-->
						<div class="image-input-wrapper w-200px h-200px" style="background-image: url({{ asset('storage/' . $query->avatar) }});background-position: center;"></div>
						<!--end::Preview existing avatar-->
						<!--begin::Label-->
						<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Changer avatar">
							<i class="bi bi-pencil-fill fs-7"></i>
							<!--begin::Inputs-->
							<input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
							<!--end::Inputs-->
						</label>
						<!--end::Label-->
						<!--begin::Cancel-->
						<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Supprimer avatar">
							<i class="bi bi-x fs-2"></i>
						</span>
						<!--end::Cancel-->
						<!--begin::Remove-->
						<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Supprimer avatar">
							<i class="bi bi-x fs-2"></i>
						</span>
						<!--end::Remove-->
					</div>
					<!--end::Image input-->
					<!--begin::Hint-->
					<div class="form-text text-danger">Format accepté: png, jpg, jpeg.</div>
					<!--end::Hint-->
				</div>
				<!--end::Col-->
			</div>
			<!--begin::Input group-->
			<div class="row mb-5">
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5 code">Genre : <span class="text-danger">*</span></label>
                    <select name="gender" class="form-control requiredField">
						<option value="" selected>Sélectionner</option>
						@foreach($gender as $key => $sex)
							<option value="{{ $key }}" @php echo $key == $query->gender ? 'selected':'' @endphp>{{ $sex }}</option>
						@endforeach
					</select>
                </div>
				<div class="col-md-4 col-12">
					<label class="fw-bolder text-dark fs-5">Nom : <span class="text-danger">*</span></label>
					<input type="text" name="lastname" value="{{ old('lastname', $query->lastname) }}" class="form-control requiredField" placeholder="Saisir nom" />
				</div>
				<div class="col-md-4 col-12">
					<label class="fw-bolder text-dark fs-5">Prénoms : <span class="text-danger">*</span></label>
					<input type="text" name="firstname" value="{{ old('firstname', $query->firstname) }}" class="form-control requiredField" placeholder="Saisir prénoms" />
				</div>
			</div>
			<div class="row mb-5">
				<div class="col-md-4 col-12">
					<label class="fw-bolder text-dark fs-5">Numéro de téléphone : <span class="text-danger">*</span></label>
					<input type="text" id="number" name="number" value="{{ old('number', $query->number) }}" class="form-control requiredField number" onKeyUp="verif_int(this)">
				</div>
				<div class="col-md-4 col-12">
					<label class="fw-bolder text-dark fs-5">Email : <span class="text-danger">*</span></label>
					<input type="text" name="email" value="{{ old('email', $query->email) }}" class="form-control requiredField email" placeholder="Saisir email">
				</div>
				<div class="col-md-4 col-12">
					<label class="fw-bolder text-dark fs-5">Profil : <span class="text-danger">*</span></label>
					<select id="profile_id" name="profile_id" class="form-control" disabled>
						<option value="" selected>Sélectionner</option>
						@foreach($profile as $data)
							<option value="{{ $data->id }}" @php echo $data->id == $query->profile_id ? 'selected':'' @endphp>{{ $data->libelle }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</form>
		<!--end::Form-->
	</div>
</div>
<!--end::details View-->
@endsection