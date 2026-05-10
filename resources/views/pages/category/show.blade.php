@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
      <form class="formField">
        <div class="row mb-5">
            <div class="col-md-6 col-12">
                <label class="fw-bolder text-dark fs-5">Type :</label>
                <input type="text" value="{{ $query->category->libelle }}" class="form-control" />
            </div>
            <div class="col-md-6 col-12">
                <label class="fw-bolder text-dark fs-5">Libellé :</label>
                <input type="text" value="{{ $query->libelle }}" class="form-control" />
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12 col-12">
                <label class="fw-bolder text-dark fs-5">Description :</label>
                <textarea class="form-control">{{ $query->description }}</textarea>
            </div>
        </div>
      </form>
    </div>
  </div>
@endsection