@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="rootForm" value="users">
            <span class="msgError" style="display: none;"></span>
            <div class="row mb-5">
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Genre : <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select requiredField">
						<option value="" selected>Sélectionner</option>
						@foreach($gender as $key => $sex)
							<option value="{{ $key }}">{{ $sex }}</option>
						@endforeach
					</select>
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Nom : <span class="text-danger">*</span></label>
                    <input type="text" name="lastname" class="form-control requiredField" placeholder="Saisir nom" />
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Prénoms : <span class="text-danger">*</span></label>
                    <input type="text" name="firstname" class="form-control requiredField" placeholder="Saisir prénoms" />
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Numéro de téléphone : <span class="text-danger">*</span></label>
                    <input type="text" name="number" class="form-control requiredField number" placeholder="Saisir numéro de téléphone" onKeyUp="verif_int(this)">
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Email : <span class="text-danger">*</span></label>
                    <input type="text" name="email" class="form-control requiredField email" placeholder="Saisir email">
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Profil : <span class="text-danger">*</span></label>
                    <select name="profile_id" class="form-select requiredField">
						<option value="" selected>Sélectionner</option>
						@foreach($profile as $data)
							<option value="{{ $data->id }}">{{ $data->libelle }}</option>
						@endforeach
					</select>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection