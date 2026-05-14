@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="rootForm" value="category">
            <div class="row mb-2">
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Type : <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select requiredField">
						<option value="" selected>Sélectionner</option>
						@foreach($list as $data)
							<option value="{{ $data->id }}">{{ $data->libelle }}</option>
						@endforeach
					</select>
                </div>
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Categorie : <span class="text-danger">*</span></label>
                    <input type="text" name="libelle" class="form-control requiredField" placeholder="Saisir la categorie" />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12 col-12">
                    <label class="fw-bolder text-dark fs-5">Description : <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control requiredField" placeholder="Saisir la description"></textarea>
                </div>
            </div>
            <span class="msgError" style="display: none;"></span>
        </form>
    </div>
</div>
@endsection