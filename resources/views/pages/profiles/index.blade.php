@extends('layouts.master')

@section('content')
  <!--begin::Card-->
  <div class="card">
    <!--begin::Card body-->
    <div class="card-body py-4">
      <!--begin::Table-->
      <table id="kt_datatable" class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
        <thead>
          <tr class="fw-bolder fs-6 text-gray-800 px-7">
            <th>#</th>
            <th>Libellé</th>
            <th>Description</th>
            <th class="text-center">Date</th>
            <th class="text-center">Statut</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @php
            $i = 1;
            $href_edit = $class_status = $class_delete = '';
            $color_edit = $color_status = $color_delete = 'text-muted';
            if (in_array(4, $actionIds)) {
              $class_status = "status";
              $color_status = 'text-info';
            }
            if (in_array(5, $actionIds)) {
              $class_delete = "status";
              $color_delete = 'text-danger';
            }
            foreach ($query as $data) :
            if (in_array(3, $actionIds)) {
              $href_edit = "/profiles/{$data->uid}/edit";
              $color_edit = 'text-warning';
            }
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
            <td>{{ $i++ }}</td>
            <td>{{ $data->libelle }}</td>
            <td>{{ $data->description }}</td>
            <td class="text-center">{{ $data->created_at->format('d-m-Y H:i') }}</td>
            <td class="text-center"><span data-kt-element="status" class="badge {{ $badge }} fw-bold px-4 py-3">{{ $status }}</span></td>
            <td class="text-end align-middle">
              <a href="/profiles/{{ $data->uid }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Voir détail du profil" class="btn btn-icon btn-bg-light btn-sm me-1">
                <i class="ki-duotone ki-switch text-primary fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </a>
              <a href="{{ $href_edit }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier le profil" class="btn btn-icon btn-bg-light btn-sm me-1">
                <i class="ki-duotone ki-pencil {{ $color_edit }} fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </a>
              <a href="#" data-url="/profiles/status/{{ $data->uid }}" data-type="PATCH" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $action }} le profil" class="btn btn-icon btn-bg-light btn-sm me-1 {{ $class_status }}">
                <i class="ki-duotone ki-filter {{ $color_status }} fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </a>
              <a href="#" data-url="/profiles/{{ $data->uid }}" data-type="DELETE" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimé le profil" class="btn btn-icon btn-bg-light btn-sm {{ $class_delete }}">
                <i class="ki-duotone ki-trash {{ $color_delete }} fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                  <span class="path4"></span>
                  <span class="path5"></span>
                </i>
              </a>
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