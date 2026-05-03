@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="rootForm" value="documents">
            <div class="row mb-5">
                <div class="col-md-6 col-12">
                    <label class="fw-bolder text-dark fs-5">Libellé : <span class="text-danger">*</span></label>
                    <input type="text" name="libelle" class="form-control requiredField" placeholder="Saisir le libellé" />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Montant : <span class="text-danger">*</span></label>
                    <input type="text" name="amount" class="form-control requiredField" placeholder="Saisir le montant" onKeyUp="verif_int(this)" />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Nombre de jours : <span class="text-danger">*</span></label>
                    <input type="text" name="day" class="form-control requiredField" placeholder="Saisir le nombre" onKeyUp="verif_int(this)" />
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