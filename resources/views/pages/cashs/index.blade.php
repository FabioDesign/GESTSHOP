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
            <th class="text-center">Entrées</th>
            <th class="text-center">Sorties</th>
            <th class="text-center">Solde</th>
            <th class="text-center">Date</th>
            <th class="text-center">Statut</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @php
            $i = 1;
            $href_edit = $class_delete = '';
            $color_edit = $color_delete = 'text-muted';
            foreach ($query as $data) :
            if (($data->status == 0) && (in_array(3, $actionIds))) {
              $href_edit = "/cashs/{$data->uid}/edit";
              $color_edit = 'text-warning';
            }
            if (($data->status == 0) && (in_array(4, $actionIds))) {
              $class_delete = "status";
              $color_delete = 'text-danger';
            }
            switch ($data->status) {
              case 1 :
                $status = 'Transmis';
                $badge = 'badge-light-warning';
                break;
              case 2 :
                $status = 'Validé';
                $badge = 'badge-light-success';
                break;
              case 3 :
                $status = 'Rejeté';
                $badge = 'badge-light-danger';
                break;
              default :
                $status = 'Brouillon';
                $badge = 'badge-light-primary';
            }
            $balance = $data->cash_in - $data->cash_out;
          @endphp
          <tr>
            <td class="align-middle">{{ $i++ }}</td>
            <td class="text-center align-middle">{{ $data->cash_in }}</td>
            <td class="text-center align-middle">{{ $data->cash_out }}</td>
            <td class="text-center align-middle">{{ $balance }}</td>
            <td class="text-center align-middle">{{ $data->date_at->format('d-m-Y') }}</td>
            <td class="text-center align-middle"><span data-kt-element="status" class="badge {{ $badge }} fw-bold px-4 py-3">{{ $status }}</span></td>
            <td class="text-end align-middle">
              <a href="/cashs/{{ $data->uid }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Voir détail de la caisse" class="btn btn-icon btn-bg-light btn-sm me-1">
                <i class="ki-duotone ki-switch text-primary fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </a>
              <a href="{{ $href_edit }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier la caisse" class="btn btn-icon btn-bg-light btn-sm me-1">
                <i class="ki-duotone ki-pencil {{ $color_edit }} fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </a>
              <a href="#" data-url="/cashs/{{ $data->uid }}" data-type="DELETE" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimé la caisse" class="btn btn-icon btn-bg-light btn-sm {{ $class_delete }}">
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