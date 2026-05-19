@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="uid" value="{{ $query->uid }}">
            <div class="row mb-5">
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Date de la caisse :</label>
                    <input type="text" value="{{ $query->date_at->format('d-m-Y') }}" class="form-control text-center" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Entrées :</label>
                    <input type="text" value="{{ $query->cash_in }}" class="form-control text-end" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Sorties :</label>
                    <input type="text" value="{{ $query->cash_out }}" class="form-control text-end" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Solde :</label>
                    <input type="text" value="{{ $query->cash_in - $query->cash_out }}" class="form-control text-end" readonly />
                </div>
            </div>
			@if($query->motif)
            <div class="row mb-5">
                <div class="col-md-12 col-12">
                    <label class="fw-bolder text-dark fs-5">Motif du rejet :</label>
                	<textarea class="form-control" readonly>{{ $query->motif }}</textarea>
                </div>
            </div>
			@endif
			<div class="separator separator-dashed my-10"></div>
            <!--begin::Repeater-->
			<div id="kt_docs_repeater_basic">
				<!--begin::Form group-->
				<div class="form-group">
					<div data-repeater-list="kt_docs_repeater_basic">
						@foreach($transactions as $transaction)
						<div data-repeater-item>
							<div class="form-group row mb-5">
								<div class="col-md-3">
									<label class="fw-bolder text-dark fs-5">Type :</label>
									<input type="text" value="{{ $transaction->category->libelle }}" class="form-control" readonly />
								</div>
								<div class="col-md-4">
									<label class="fw-bolder text-dark fs-5">Catégorie :</label>
									<input type="text" value="{{ $transaction->product->libelle }}" class="form-control" readonly />
								</div>
								<div class="col-md-2 col-12">
									<label class="fw-bolder text-dark fs-5">PU :</label>
									<input type="text" value="{{ $transaction->price }}" class="form-control text-end" readonly />
								</div>
								<div class="col-md-1 col-12">
									<label class="fw-bolder text-dark fs-5">Qté :</label>
									<input type="text" value="{{ $transaction->quantity }}" class="form-control text-center" readonly />
								</div>
								<div class="col-md-2 col-12">
									<label class="fw-bolder text-dark fs-5">Total :</label>
									<input type="text" value="{{ $transaction->price * $transaction->quantity }}" class="form-control text-end" readonly />
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				<!--end::Form group-->
			</div>
			<!--end::Repeater-->
        </form>
    </div>
</div>
@endsection

@section('scripts')
  	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
		$(document).on('click', '.btn-rjt', function() {
			Swal.fire({
				title: 'Rejeter la caisse',
				text: 'Veuillez confirmer votre action.',
				icon: 'warning',
				input: "textarea",
				inputPlaceholder: "Veuillez saisir le motif du rejet...",
    			inputAttributes: { required: true },
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Confirmer',
				cancelButtonText: 'Annuler',
				inputValidator: (value) => {
					if (!value || !value.trim()) {
						return "<span style='color: #f27474;font-weight: 600;'>Le motif du rejet est obligatoire !</span>";
					}
					if (value.trim().length < 10) {
						return "<span style='color: #f27474;font-weight: 600;'>Veuillez fournir un motif plus détaillé.</span>";
					}
				},
			}).then((result) => {
				if (result.isConfirmed) {
					const motif = result.value.trim();
					// Afficher un chargement
					Swal.fire({
						title: 'Traitement en cours...',
						text: 'Rejet de la caisse en cours',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					// Appel AJAX
					axios.post('/reject', {
						motif: motif,
						uid: $('#uid').val()
					}).then(response => {
						if (response.data.status == true) {
							Swal.fire({
								title: "Félicitation !",
								text: response.data.message,
								icon: 'success',
								confirmButtonText: "Fermer",
								customClass:{
									confirmButton: "btn btn-square font-weight-bold btn-light-success"
								}
							}).then(function() {
								location.reload();
							});
						} else {
							Swal.fire({
								title: 'Erreur !',
								text: response.data.message,
								icon: 'error',
								confirmButtonText: 'Fermer',
								customClass: {
									confirmButton: "btn btn-square font-weight-bold btn-light-success"
								},
							});
						}
					})
					.catch(error => {
						Swal.fire({
							title: 'Erreur !',
							text: 'Une erreur est survenue lors du rejet',
							icon: 'error',
							confirmButtonText: "Fermer",
							customClass:{
								confirmButton: "btn btn-square font-weight-bold btn-light-success"
							}
						});
					});
				}
			});
		});
    </script>
@endsection