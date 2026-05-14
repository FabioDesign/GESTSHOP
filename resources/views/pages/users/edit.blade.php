@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            @method('PUT')
            <input type="hidden" id="rootForm" value="users/{{ $query->uid }}">
            <span class="msgError" style="display: none;"></span>
            <div class="row mb-5">
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5 code">Genre : <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select requiredField">
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
                    <select id="profile_id" name="profile_id" class="form-select">
						<option value="" selected>Sélectionner</option>
						@foreach($profile as $data)
							<option value="{{ $data->id }}" @php echo $data->id == $query->profile_id ? 'selected':'' @endphp>{{ $data->libelle }}</option>
						@endforeach
					</select>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection