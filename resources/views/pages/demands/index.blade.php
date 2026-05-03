@extends('layouts.master')

@section('content')
  <!--begin::Card-->
  <div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
      <!--begin::Card title-->
      <div class="card-title">
        <!--begin::Toolbar-->
        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
          <!--begin::Select-->
				  <select id="tableLength" class="form-select form-select-sm form-select-solid w-80px" data-control="select2" data-hide-search="true">
            <option value="" disabled>Afficher</option>
            <option value="10" selected="selected">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <!--end::Select-->
        </div>
        <!--end::Toolbar-->
      </div>
      <!--begin::Card title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar">
        <!--begin::Search-->
        <div class="d-flex align-items-center position-relative my-1">
          <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3">
            <span class="path1"></span>
            <span class="path2"></span>
          </i>
          <input type="text" id="tableSearch" class="form-control form-control-solid form-select-sm w-200px ps-9" placeholder="Rechercher..." />
        </div>
        <!--end::Search-->
      </div>
      <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-2">
      <!--begin::Table-->
      <div class="table-responsive" id="tableData">
        <div class="loading-spinner text-center mt-10">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
          <div class="mt-5">Chargement des données...</div>
        </div>
      </div>
      <!--end::Table-->
    </div>
    <!--end::Card body-->
  </div>
  <!--end::Card-->
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <script>
    function formatDateTime(dateString) {
      const d = new Date(dateString);

      const day = String(d.getDate()).padStart(2, '0');
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const year = d.getFullYear();

      const hours = String(d.getHours()).padStart(2, '0');
      const minutes = String(d.getMinutes()).padStart(2, '0');

      return `${day}-${month}-${year} ${hours}:${minutes}`;
    }
    const getDemands = async () => {
      try {
        const response = await axios.get( '/getDemands');
        return response.data.data || [];
      } catch (e) {
        console.error(e);
        return [];
      }
    }

    getDemands().then(
      response => {
        if (response.length > 0) {
          let i = 1;
          let outTable = `
            <table id="logsTable" class="table table-striped table-row-bordered gs-7 border rounded align-middle">
              <thead>
                <tr class="fw-bolder text-gray-800 fs-6">
                  <th>#</th>
                  <th>Code</th>
                  <th>Libellé</th>
                  <th class="text-center">Montant</th>
                  <th class="text-center">Nombre de jours</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Statut</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody class="text-gray-600 fw-semibold">
          `;
				  response.map(data => {
            let dateHour = formatDateTime(data.created_at);
            let dateRDV = formatDateTime(data.daterdv_at);
            switch ($data.status) {
              case 0:
                status = 'Brouillon';
                action = 'Transférer';
                badge = 'badge-light-info';
                break;
              case 1:
                status = 'Transféré';
                action = 'Valider/Rejeter';
                color = 'badge-light-warning';
                break;
              case 2:
                status = 'Validé';
                action = 'Imprimer';
                color = 'badge-light-success';
                break;
              case 3:
                status = 'Rejeté';
                action = '';
                color = 'badge-light-danger';
                break;
              default:
                status = 'N/A';
                action = '';
                color = 'badge-light-secondary';
            }
            outTable += `<tr>
              <td>${i}</td>
              <td>${data.code}</td>
              <td>${data.libelle}</td>
              <td class="text-center">${data.amount}</td>
              <td class="text-center">${dateHour}</td>
              <td class="text-center">${dateRDV}</td>
              <td><span class="badge badge-light-${color} fw-bold px-4 py-3">${data.action}</span></td>
              <td>${dateHour}</td>
            </tr>`;
            i++;
          });
          outTable += `</tbody></table>`;
          $('#tableData').html(outTable);
              
          // Initialiser DataTable avec pagination et recherche
          logsTable = $('#logsTable').DataTable({
            paging: true,
            searching: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            info: false,
            ordering: true,
            responsive: true,
            dom: 'rtip', // Masquer les contrôles par défaut de DataTables
          });
          
          // Personnaliser la recherche
          $('#tableSearch').on('keyup', function() {
            logsTable.search(this.value).draw();
          });
          
          // Personnaliser le nombre d'entrées affichées
          $('#tableLength').on('change', function() {
            logsTable.page.len(this.value).draw();
          });
          
          // Mettre à jour le sélecteur de longueur lorsque DataTable change
          logsTable.on('length.dt', function(e, settings, length) {
            $('#tableLength').val(length);
          });
        }
      }
    );
  </script>
@endsection