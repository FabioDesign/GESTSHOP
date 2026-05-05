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
            <th>#</th>
            <th>Type</th>
            <th>Categorie</th>
            <th class="text-center">Date</th>
            <th class="text-center">Statut</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @php
            $i = 1;
            foreach ($query as $data) :
            if ($data->status == 1) {
              $status = 'Activé';
              $action = 'Désactivé';
              $badge = 'badge-light-success';
            } else {
              $status = 'Désactivé';
              $action = 'Activé';
              $badge = 'badge-light-danger';
            }
          @endphp
          <tr>
            <td class="align-middle">{{ $i++ }}</td>
            <td class="align-middle">{{ $data->category }}</td>
            <td class="align-middle">{{ $data->libelle }}</td>
            <td class="text-center align-middle">{{ $data->created_at->format('d-m-Y H:i') }}</td>
            <td class="text-center align-middle"><span data-kt-element="status" class="badge {{ $badge }} fw-bold px-4 py-3">{{ $status }}</span></td>
            <td class="text-center align-middle">
              @if(in_array(3, $actionIds))
              <a href="/category/{{ $data->uid }}/edit" data-bs-toggle="tooltip" data-bs-theme="dark" data-bs-placement="top" title="Modifier la categorie"><i class="fas fa-edit fa-size text-warning me-1"></i></a>
              @else
              <a href="#"><i class="fas fa-edit fa-size text-muted me-1"></i></a>
              @endif
              @if(in_array(4, $actionIds))
              <a href="#" class="status" data-url="/category/status/{{ $data->uid }}" data-type="PATCH" data-bs-toggle="tooltip" data-bs-theme="dark" data-bs-placement="top" title="{{ $action }} la categorie"><i class="fas fa-question fa-size text-info"></i></a>
              @else
              <a href="#"><i class="fas fa-question fa-size text-muted"></i></a>
              @endif
              @if(in_array(5, $actionIds))
              <a href="#" class="status" data-url="/category/{{ $data->uid }}" data-type="DELETE" data-bs-toggle="tooltip" data-bs-theme="dark" data-bs-placement="top" title="Supprimé la categorie"><i class="fas fa-trash-alt fa-size text-danger"></i></a>
              @else
              <a href="#"><i class="fas fa-trash-alt fa-size text-muted"></i></a>
              @endif
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