@extends('layouts.master')

@section('content')
<div class="card">
  <div class="card-body py-4">
    <form class="formField">
      @method('PUT')
      <input type="hidden" id="rootForm" value="category/{{ $query->uid }}">
        <div class="row mb-2">
          <div class="col-md-6 col-12">
            <label class="fw-bolder text-dark fs-5">Type : <span class="text-danger">*</span></label>
            <select name="category_id" class="form-control requiredField">
              <option value="" selected>Sélectionner</option>
              @foreach($list as $data)
                <option value="{{ $data->id }}" @php echo $data->id == $query->category_id ? 'selected':'' @endphp>{{ $data->libelle }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 col-12">
            <label class="fw-bolder text-dark fs-5">Categorie : <span class="text-danger">*</span></label>
            <input type="text" name="libelle" value="{{ old('libelle', $query->libelle) }}" class="form-control requiredField" placeholder="Saisir la categorie" />
          </div>
      </div>
      <span class="msgError" style="display: none;"></span>
    </form>
  </div>
</div>
@endsection