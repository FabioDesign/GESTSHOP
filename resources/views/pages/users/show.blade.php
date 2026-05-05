@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <div class="row mb-5">
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5 code">Genre :</label>
                    <input type="text" value="{{ $query->gender == 'M' ? 'Masculin' : 'Feminin' }}" class="form-control" />
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Nom :</label>
                    <input type="text" value="{{ $query->lastname }}" class="form-control" />
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Prénoms :</label>
                    <input type="text" value="{{ $query->firstname }}" class="form-control" />
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Numéro de téléphone :</label>
                    <input type="text" id="number" value="{{ $query->number }}" class="form-control">
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Email :</label>
                    <input type="text" value="{{ $query->email }}" class="form-control">
                </div>
                <div class="col-md-4 col-12">
                    <label class="fw-bolder text-dark fs-5">Profil :</label>
                    <input type="text" value="{{ $query->profile->libelle }}" class="form-control">
                </div>
        </form>
    </div>
</div>
@endsection