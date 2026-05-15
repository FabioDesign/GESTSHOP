@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
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
				inputAttributes: { 
					required: true,
					placeholder: "Veuillez saisir le motif du rejet...",
				},
				inputValidator: (value) => {
					if (!value || value.trim() === '') {
						return {
							message: 'Le motif de rejet est obligatoire',
							icon: 'error'
						};
					}
					return null;
				},
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Confirmer',
				cancelButtonText: 'Annuler',
				customClass: {
					validationMessage: 'custom-validation-error'
				},
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: urlStatus,
						type: 'POST',
						data: {
						_token: $('meta[name="csrf-token"]').attr('content'),
						_method: typeStatus
						},
						beforeSend: function() {
						Swal.fire({
							title: 'Chargement en cours...',
							text: 'Veuillez patienter...',
							timer: 50000,
							showConfirmButton: false,
						}).then(function(result) {
							if (result.dismiss === "timer") {
							console.log("I was closed by the timer")
							}
						})
						},
						success: function(response) {
						if (response === 'x') {
							window.location.href = '/';
							return;
						}
						if (response.status == 1) {
							Swal.fire({
							title: "Félicitation !",
							text: response.message,
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
							text: response.message,
							icon: 'error',
							confirmButtonText: 'Fermer',
							customClass: {
								confirmButton: "btn btn-square font-weight-bold btn-light-success"
							},
							});
						}
						},
						error: function(xhr) {
						Swal.fire({
							title: 'Erreur!',
							text: 'Une erreur est survenue.',
							icon: 'error',
						});
						}
					});
				}
			});
		});
    </script>
@endsection