@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="rows" value="1">
            <input type="hidden" id="rootForm" value="cashs">
            <div class="row mb-5">
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Date de la caisse : <span class="text-danger">*</span></label>
                    <input type="text" name="date_at" class="form-control date_at" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Entrées : <span class="text-danger">*</span></label>
                    <input type="text" id="cash_in" name="cash_in" value="0" class="form-control text-end" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Sorties : <span class="text-danger">*</span></label>
                    <input type="text" id="cash_out" name="cash_out" value="0" class="form-control text-end" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Solde : <span class="text-danger">*</span></label>
                    <input type="text" id="balance" value="0" class="form-control text-end" readonly />
                </div>
            </div>
            <span class="msgError" style="display: none;"></span>
			<div class="separator separator-dashed my-10"></div>
            <!--begin::Repeater-->
			<div id="kt_docs_repeater_basic">
				<!--begin::Form group-->
				<div class="form-group">
					<div data-repeater-list="kt_docs_repeater_basic">
						<div data-repeater-item>
            				<input type="hidden" class="type">
							<div class="form-group row mb-5">
								<div class="col-md-3">
									<label class="fw-bolder text-dark fs-5">Type : <span class="text-danger">*</span></label>
									<select name="category_id" class="form-select category_id requiredField">
										<option value="" selected>Sélectionner</option>
										@foreach($list as $data)
											<option value="{{ $data->id }}">{{ $data->libelle }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-3">
									<label class="fw-bolder text-dark fs-5">Catégorie : <span class="text-danger">*</span></label>
									<select name="product_id" class="form-select product_id requiredField">
										<option value="" selected>Sélectionner</option>
									</select>
								</div>
								<div class="col-md-2 col-12">
									<label class="fw-bolder text-dark fs-5">PU : <span class="text-danger">*</span></label>
									<input type="text" name="price" class="form-control requiredField text-end amount price" placeholder="0" oninput="verif_int(this)" />
								</div>
								<div class="col-md-1 col-12">
									<label class="fw-bolder text-dark fs-5">Qté : <span class="text-danger">*</span></label>
									<input type="text" name="quantity" class="form-control requiredField text-center amount quantity" placeholder="0" oninput="verif_int(this)" />
								</div>
								<div class="col-md-2 col-12">
									<label class="fw-bolder text-dark fs-5">Total : <span class="text-danger">*</span></label>
									<input type="text" class="form-control requiredField text-end amount balance" placeholder="0" readonly />
								</div>
								<div class="col-md-1">
									<a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger btn-del mt-3 mt-md-8">
										<i class="ki-duotone ki-trash fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
										</i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end::Form group-->

				<!--begin::Form group-->
				<div class="form-group mt-5">
					<a href="javascript:;" data-repeater-create class="btn btn-light-primary btn-add">
						<i class="ki-duotone ki-plus fs-3"></i>
						Ajouter un élément
					</a>
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
	<script src="/assets/js/custom/formrepeater.bundle.js"></script>
	<script src="/assets/js/custom/flatpickr_fr.js"></script>
    <script>
		// Date du jour
		$('.date_at').flatpickr({
			locale: 'fr',
			altInput: true,
			altFormat: 'd-m-Y',
			dateFormat: 'Y-m-d',
			defaultDate: 'today',
			maxDate: 'today',
		});

		function preventDuplicateSelections() {
			let selections = [];
			// Récupérer toutes les combinaisons sélectionnées
			$('#kt_docs_repeater_basic [data-repeater-item]').each(function () {
				let type = $(this).find('.type').val();
				let product = $(this).find('.product_id').val();

				if (type && product) {
					selections.push(type + '-' + product);
				}
			});
			// Réinitialiser tous les selects
			$('.product_id option').prop('disabled', false);
			// Désactiver les doublons
			$('#kt_docs_repeater_basic [data-repeater-item]').each(function () {
				let currentType = $(this).find('.type').val();
				let currentProduct = $(this).find('.product_id').val();
				$(this).find('.product_id option').each(function () {
					let optionVal = $(this).val();
					let key = currentType + '-' + optionVal;
					if (
						selections.includes(key) &&
						optionVal != currentProduct // ne pas bloquer sa propre valeur
					) {
						$(this).prop('disabled', true);
					}
				});
			});
		}
		// Récupérer les catégories et les injecter dans le bon select2
		const getCategory = async (category_id, $field) => {
			if (!category_id) return;
			$field.find('.product_id').empty().append('<option value="" selected>Sélectionner</option>');
			try {
				const response = await axios.get('/getCategory/' + category_id);
				const items = response.data.data || [];
				$field.find('.type').val(response.data.type);
				items.forEach(data => {
					$field.find('.product_id').append(new Option(data.libelle, data.id, false, false));
				});
			} catch (e) {
				console.error(e);
			}
		};

		// Récupérer les produits
		const getProduct = async (product_id, $field) => {
			if (!product_id) return;
			try {
				const response = await axios.get('/getProduct/' + product_id);
				const product = response.data.data;
				let type = $field.find('.type').val();
				if (type == 1)
					prix = parseInt(product.prix_vente) || 0;
				else
					prix = parseInt(product.prix_achat) || 0;
				$field.find('.price').val(prix);
			} catch (e) {
				console.error(e);
			}
		};

		// Afficher/masquer le bouton Delete
		function toggleDeleteButtons(rows) {
			let deleteButtons = $('[data-repeater-delete]');
			if (rows <= 1)
				deleteButtons.addClass('disabled').css('pointer-events', 'none');
			else
				deleteButtons.removeClass('disabled').css('pointer-events', 'auto');
		}

		// Calculer le total des entrées et sorties
		function calculBalance() {
			let cash_in = cash_out = 0;
			$('#kt_docs_repeater_basic .balance').each(function() {
				if (jQuery.trim($(this).val()) !== '') {
					let $field = $(this).closest('[data-repeater-item]');
					let type = $field.find('.type').val();
					if (type == 1)
						cash_in += parseInt($(this).val());
					else if (type == 2)
						cash_out += parseInt($(this).val());
				}
			});
			balance = cash_in - cash_out;
			$('#cash_in').val(cash_in);
			$('#cash_out').val(cash_out);
			$('#balance').val(balance);
		}

		// Formulaire répété
		$('#kt_docs_repeater_basic').repeater({
			initEmpty: false,
			defaultValues: { 'text-input': 'foo' },

			show: function () {
				$(this).slideDown();

				const $item = $(this);

				// Listener category_id sur cette ligne uniquement
				$item.find('.category_id').off('change').on('change', async function () {
					const category_id = $(this).val();
					const $field = $(this).closest('[data-repeater-item]');
					$field.find('.amount').val('');
					await getCategory(category_id, $field);
					preventDuplicateSelections();
				});

				// Initialiser et listener sur la 1ère ligne
				$item.find('.product_id').off('change').on('change', async function () {
					const product_id = $(this).val();
					const $field = $(this).closest('[data-repeater-item]');
					$field.find('.amount').val('');
					await getProduct(product_id, $field);
				});

				// Initialiser et listener sur la 1ère ligne
				$item.find('.price, .quantity').off('keyup').on('keyup', async function () {
					const $field = $(this).closest('[data-repeater-item]');
					const price = $field.find('.price').val() || 0;
					const quantity = $field.find('.quantity').val() || 0;
					balance = parseInt(price) * parseInt(quantity);
					$field.find('.balance').val(balance);
					calculBalance();
				});
			},
		});

		// Initialiser et listener sur la 1ère ligne
		$('[data-repeater-item] .category_id').off('change').on('change', async function () {
			const category_id = $(this).val();
			const $field = $(this).closest('[data-repeater-item]');
			$field.find('.amount').val('');
			await getCategory(category_id, $field);
			preventDuplicateSelections();
		});

		// Initialiser et listener sur la 1ère ligne
		$('[data-repeater-item] .product_id').off('change').on('change', async function () {
			const product_id = $(this).val();
			const $field = $(this).closest('[data-repeater-item]');
			$field.find('.amount').val('');
			await getProduct(product_id, $field);
		});

		// Initialiser et listener sur la 1ère ligne
		$('[data-repeater-item] .price, [data-repeater-item] .quantity').off('keyup').on('keyup', async function () {
			const $field = $(this).closest('[data-repeater-item]');
			const price = $field.find('.price').val() || 0;
			const quantity = $field.find('.quantity').val() || 0;
			balance = parseInt(price) * parseInt(quantity);
			$field.find('.balance').val(balance);
			calculBalance();
		});
		
		$(document).on('click', '.btn-add', function() {
			let rows = parseInt($('#rows').val()) + 1;
			$('#rows').val(rows);
			toggleDeleteButtons(rows);
		});
		
		$(document).on('click', '.btn-del', function() {
			let $item = $(this).closest('[data-repeater-item]');
			// Supprimer la ligne avec animation
			$item.slideUp(400, function() {
				$(this).remove();
				// Mettre à jour les compteurs
				let rows = $('#kt_docs_repeater_basic [data-repeater-item]').length;
				$('#rows').val(rows);
				toggleDeleteButtons(rows);
				// Exécuter le calcul sur les lignes restantes
				calculBalance();
			});
		});
		toggleDeleteButtons(1);
    </script>
@endsection