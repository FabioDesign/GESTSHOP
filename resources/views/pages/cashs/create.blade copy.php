@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
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
                    <input type="text" id="balance" name="balance" value="0" class="form-control text-end" readonly />
                </div>
            </div>
			<div class="separator separator-dashed my-10"></div>
            <!--begin::Repeater-->
			<div data-repeater-list="kt_docs_repeater_basic">
				{{-- ✅ Template masqué, jamais rendu visuellement, jamais initialisé par select2 --}}
				<div data-repeater-item style="display:none">
					<div class="form-group row mb-5">
						<div class="col-md-3">
							<label class="fw-bolder text-dark fs-5">Type : <span class="text-danger">*</span></label>
							<select name="type_id" class="form-select type_id requiredField">
								<option value="" selected>Sélectionner</option>
								@foreach($list as $data)
									<option value="{{ $data->id }}">{{ $data->libelle }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3">
							<label class="fw-bolder text-dark fs-5">Catégorie : <span class="text-danger">*</span></label>
							{{-- ✅ Select vide, sans aucune option --}}
							<select name="category_id" class="form-select cat_select requiredField">
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
							<input type="text" name="total" class="form-control requiredField text-end amount total" placeholder="0" readonly />
						</div>
						<div class="col-md-1">
							<a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
								<i class="ki-duotone ki-trash fs-5">
									<span class="path1"></span><span class="path2"></span>
									<span class="path3"></span><span class="path4"></span>
									<span class="path5"></span>
								</i>
							</a>
						</div>
					</div>
				</div>
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
    <script src="/assets/js/custom/select2.js"></script>
    <script>
		// 🔍 DIAGNOSTIC — à retirer après analyse
		console.log('Options dans cat_select au chargement :');
		$('[data-repeater-item] .cat_select').each(function() {
			console.log($(this).html());
		});

		// Date du jour
		$('.date_at').flatpickr({
			locale: 'fr',
			altInput: true,
			altFormat: 'd-m-Y',
			dateFormat: 'Y-m-d',
			defaultDate: 'today',
			maxDate: 'today',
		});

		// ✅ Init select2
		function initSelect2(el) {
			const $el = $(el);

			// Vider le select avant tout
			$el.empty();

			if ($el.data('select2')) {
				$el.select2('destroy');
			}

			$el.removeClass('select2-hidden-accessible');
			$el.removeAttr('data-select2-id');
			$el.find('option').removeAttr('data-select2-id');

			$el.select2({
				placeholder: "Sélectionner",
				width: '100%',
				dropdownParent: $el.closest('.col-md-3'),
			});
		}

		// ✅ Récupérer les catégories
		const getCategory = async (type_id, catSelect) => {
			if (!type_id) return;
			catSelect.empty().append('<option value="" selected>Sélectionner</option>');
			try {
				const response = await axios.get('/getCategory/' + type_id);
				const items = response.data.data || [];
				items.forEach(data => {
					catSelect.append(new Option(data.libelle, data.id, false, false));
				});
				catSelect.trigger('change.select2');
			} catch (e) {
				console.error(e);
			}
		};

		// ✅ Récupérer les produits
		const getProduct = async (category_id, $row) => {
			if (!category_id) return;
			$row.find('.price').val('');
			try {
				const response = await axios.get('/getProduct/' + category_id);
				const items = response.data.data || [];
				items.forEach(data => {
					if (data.category_id == 0) {
						console.log('Produit trouvé pour category_id 0 :', data);
						$row.find('.price').val(data.price);
					}
				});
			} catch (e) {
				console.error(e);
			}
		};

		// ✅ Afficher/masquer le bouton Delete
		function toggleDeleteButtons() {
			const items = $('[data-repeater-item]:visible');
			const deleteButtons = $('[data-repeater-delete]');
			if (items.length <= 1) {
				deleteButtons.addClass('disabled').css('pointer-events', 'none');
			} else {
				deleteButtons.removeClass('disabled').css('pointer-events', 'auto');
			}
		}

		// ✅ Initialiser une ligne complète
		function bindRow($item) {
			initSelect2($item.find('.cat_select'));

			// Type → charger catégories
			$item.find('.type_id').off('change').on('change', async function () {
				const type_id = $(this).val();
				const catSelect = $(this).closest('[data-repeater-item]').find('.cat_select');
				await getCategory(type_id, catSelect);
			});

			// Catégorie → charger prix produit
			$item.find('.cat_select').off('change').on('change', async function () {
				const category_id = $(this).val();
				const $row = $(this).closest('[data-repeater-item]');
				await getProduct(category_id, $row);
			});
		}

		// ✅ Repeater
		$('#kt_docs_repeater_basic').repeater({
			initEmpty: false,
			defaultValues: { 'text-input': 'foo' },

			show: function () {
				$(this).slideDown();
				bindRow($(this));
				toggleDeleteButtons();
			},

			hide: function (deleteElement) {
				const catSelect = $(this).find('.cat_select');
				if (catSelect.data('select2')) {
					catSelect.select2('destroy');
				}
				$(this).slideUp(deleteElement);
				toggleDeleteButtons();
			}
		});

		// ✅ Initialiser toutes les lignes déjà dans le DOM après le repeater
		setTimeout(function () {
			$('[data-repeater-item]').each(function () {
				bindRow($(this));
			});
			toggleDeleteButtons();

			// 🔍 DIAGNOSTIC après setTimeout — pour voir ce qu'il y a dans le DOM après init
			console.log('Options dans cat_select après setTimeout :');
			$('[data-repeater-item] .cat_select').each(function () {
				console.log($(this).html());
			});
		}, 100);
    </script>
@endsection