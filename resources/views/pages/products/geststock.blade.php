@extends('layouts.master')

@section('content')
  <!--begin::Card-->
  <div class="card">
    <!--begin::Card body-->
    <div class="card-body py-4">
      <!--begin::Table-->
      <table id="kt_datatable" class="table table-striped table-row-bordered gs-7 border rounded">
        <thead>
          <tr class="fw-bolder fs-6 text-gray-800 px-7">
            <th class="w-10px">#</th>
            <th class="min-w-300px">Libellé</th>
            <th class="min-w-50px text-center">Seuil</th>
            <th class="min-w-50px text-center">Stock</th>
            <th class="min-w-250px text-center">Statut</th>
          </tr>
        </thead>
        <tbody>
          @php
            $i = 1;
            foreach ($query as $data) :
              $value = ($data->stock * 100) / $data->seuil;
              if ($value < 50) {
                $width = $value > 25 ? $value : 25;
                $color = 'bg-danger';
              } elseif ($value < 100) {
                $width = $value > 50 ? $value : 50;
                $color = 'bg-warning';
              } else {
                $width = 100;
                $color = 'bg-success';
              }
          @endphp
          <tr>
            <td class="align-middle">{{ $i++ }}</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-5">
                  <img src="/storage/{{ $data->photo }}" alt="{{ $data->libelle }}" />
                </div>
                <div class="d-flex justify-content-start flex-column">
                  <a href="#" class="text-dark fw-bold text-hover-primary fs-6">{{ $data->libelle }}</a>
                  <span class="text-muted fw-semibold text-muted d-block fs-7">Prix achat : {{ $data->prix_achat }} | Prix vente : {{ $data->prix_vente }}</span>
                </div>
              </div>
            </td>
            <td class="text-center align-middle">{{ $data->seuil }}</td>
            <td class="text-center align-middle">{{ $data->stock ?? 0 }}</td>
            <td class="text-center align-middle">
              <div class="d-flex flex-column w-100 me-2">
                <div class="progress h-30px w-100">
                  <div class="progress-bar progress-bar-striped progress-bar-animated fw-bold {{ $color }}" role="progressbar" style="font-size: 14px;width: {{ $width }}%;" aria-valuenow="{{ $width }}" aria-valuemin="0" aria-valuemax="100">{{ $value }}%</div>
                </div>
              </div>
            </td>
          </tr>
          @php endforeach; @endphp
        </tbody>
      </table>
      <!--end::Table-->
    </div>
    <!--end::Card body-->
  </div>
  <!--end::Card-->
@endsection