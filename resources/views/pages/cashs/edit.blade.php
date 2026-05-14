@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body py-4">
        <form class="formField">
            <input type="hidden" id="rows" value="{{ $transactions->count() }}">
            <input type="hidden" id="rootForm" value="cashs/{{ $query->uid }}">
            <div class="row mb-5">
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Date de la caisse : <span class="text-danger">*</span></label>
                    <input type="text" name="date_at" value="{{ old('libelle', $query->libelle) }}" class="form-control date_at" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Entrées : <span class="text-danger">*</span></label>
                    <input type="text" id="cash_in" name="cash_in" value="{{ old('cash_in', $query->cash_in) }}" class="form-control text-end" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Sorties : <span class="text-danger">*</span></label>
                    <input type="text" id="cash_out" name="cash_out" value="{{ old('cash_out', $query->cash_out) }}" class="form-control text-end" readonly />
                </div>
                <div class="col-md-3 col-12">
                    <label class="fw-bolder text-dark fs-5">Solde : <span class="text-danger">*</span></label>
                    <input type="text" id="balance" value="{{ $query->cash_in - $query->cash_out }}" class="form-control text-end" readonly />
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
            				<input type="hidden" class="type" value="{{ $transaction->category->type_id }}" />
							<div class="form-group row mb-5">
								<div class="col-md-3">
									<label class="fw-bolder text-dark fs-5">Type : <span class="text-danger">*</span></label>
									<select name="category_id" class="form-select category_id requiredField">
										<option value="" selected>Sélectionner</option>
										@foreach($category as $data)
											<option value="{{ $data->id }}" @php echo $data->id == $transaction->category_id ? 'selected':'' @endphp>{{ $data->libelle }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-3">
									<label class="fw-bolder text-dark fs-5">Catégorie : <span class="text-danger">*</span></label>
									<select name="product_id" class="form-select product_id requiredField">
										<option value="" selected>Sélectionner</option>
										@foreach($product as $data)
											@if (($data->category_id == $transaction->category_id) || ($data->category_id == 0))
											<option value="{{ $data->id }}" @php echo $data->id == $transaction->product_id ? 'selected':'' @endphp>{{ $data->libelle }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-md-2 col-12">
									<label class="fw-bolder text-dark fs-5">PU : <span class="text-danger">*</span></label>
									<input type="text" name="price" value="{{ old('price', $transaction->price) }}" class="form-control requiredField text-end amount price" oninput="verif_int(this)" />
								</div>
								<div class="col-md-1 col-12">
									<label class="fw-bolder text-dark fs-5">Qté : <span class="text-danger">*</span></label>
									<input type="text" name="quantity" value="{{ old('quantity', $transaction->quantity) }}" class="form-control requiredField text-center amount quantity" oninput="verif_int(this)" />
								</div>
								<div class="col-md-2 col-12">
									<label class="fw-bolder text-dark fs-5">Total : <span class="text-danger">*</span></label>
									<input type="text" value="{{ $transaction->price * $transaction->quantity }}" class="form-control requiredField text-end amount balance" readonly />
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
						@endforeach
					</div>
				</div>
				<!--end::Form group-->
            	<span class="msgError" style="display: none;"></span>

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
		let rows = parseInt($('#rows').val());
		// Date du jour
		$('.date_at').flatpickr({
			locale: 'fr',
			altInput: true,
			altFormat: 'd-m-Y',
			dateFormat: 'Y-m-d',
			defaultDate: 'today',
			maxDate: 'today',
		});

		// Récupérer les catégories et les injecter dans le bon select2
		const getCategory = async (product_id, $category, $type) => {
			if (!product_id) return;

			$category.empty().append('<option value="" selected>Sélectionner</option>');

			try {
				const response = await axios.get('/getCategory/' + product_id);
				const items = response.data.data || [];
				$type.val(response.data.type);
				items.forEach(data => {
					$category.append(new Option(data.libelle, data.id, false, false));
				});
			} catch (e) {
				console.error(e);
			}
		};

		// Récupérer les produits
		const getProduct = async (product_id, $price) => {
			if (!product_id) return;
			try {
				const response = await axios.get('/getProduct/' + product_id);
				const product = response.data.data;
				if ($('#type').val() == 1)
					prix = parseInt(product.prix_vente) || 0;
				else
					prix = parseInt(product.prix_achat) || 0;
				$price.val(prix);
			} catch (e) {
				console.error(e);
			}
		};

		// Afficher/masquer le bouton Delete
		function toggleDeleteButtons(rows) {
			let deleteButtons = $('[data-repeater-delete]');
			if (rows <= 1) {
				deleteButtons.addClass('disabled').css('pointer-events', 'none');
			} else {
				deleteButtons.removeClass('disabled').css('pointer-events', 'auto');
			}
		}

		// Calculer le total des entrées et sorties
		function calculBalance() {
			let cash_in = cash_out = 0;
			$('#kt_docs_repeater_basic .balance').each(function() {
				if (jQuery.trim($(this).val()) !== '') {
					let $field = $(this).closest('[data-repeater-item]');
					let type = $field.find('.type').val();
					console.log($(this).val(), type, 'calculBalance');
					if (type == 1) {
						cash_in += parseInt($(this).val());
					} else if (type == 2) {
						cash_out += parseInt($(this).val());
					}
				}
			});
			balance = cash_in - cash_out;
			$('#cash_in').val(cash_in);
			$('#cash_out').val(cash_out);
			$('#balance').val(balance);
			console.log(cash_in, cash_out, balance, 'calculBalance');
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
					console.log($(this).val(), 'change 1 : category_id');
					const category_id = $(this).val();
					const $field = $(this).closest('[data-repeater-item]');
					const $category = $field.find('.product_id');
					const $type = $field.find('.type');
					$field.find('.amount').val('');
					await getCategory(category_id, $category, $type);
				});

				// Initialiser et listener sur la 1ère ligne
				$item.find('.product_id').off('change').on('change', async function () {
					console.log($(this).val(), 'change 1 : product_id');
					const product_id = $(this).val();
					const $field = $(this).closest('[data-repeater-item]');
					const $price = $field.find('.price');
					$field.find('.amount').val('');
					await getProduct(product_id, $price);
				});

				// Initialiser et listener sur la 1ère ligne
				$item.find('.price, .quantity').off('keyup').on('keyup', async function () {
					console.log($(this).val(), 'change 1 : price or quantity');
					const $field = $(this).closest('[data-repeater-item]');
					const price = $field.find('.price').val() || 0;
					const quantity = $field.find('.quantity').val() || 0;
					balance = parseInt(price) * parseInt(quantity);
					$field.find('.balance').val(balance);
					calculBalance();
				});
			},

			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});

		// Initialiser et listener sur la 1ère ligne
		$('[data-repeater-item]:first .category_id').off('change').on('change', async function () {
			console.log($(this).val(), 'change 2 : category_id');
			const category_id = $(this).val();
			const $field = $(this).closest('[data-repeater-item]');
			const $category = $field.find('.product_id');
			const $type = $field.find('.type');
			$field.find('.amount').val('');
			await getCategory(category_id, $category, $type);
		});

		// Initialiser et listener sur la 1ère ligne
		$('[data-repeater-item]:first .product_id').off('change').on('change', async function () {
			console.log($(this).val(), 'change 2 : product_id');
			const product_id = $(this).val();
			const $field = $(this).closest('[data-repeater-item]');
			const $price = $field.find('.price');
			$field.find('.amount').val('');
			await getProduct(product_id, $price);
		});

		// Initialiser et listener sur la 1ère ligne
		$('[data-repeater-item]:first .price, [data-repeater-item]:first .quantity').off('keyup').on('keyup', async function () {
			console.log($(this).val(), 'change 2 : price or quantity');
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
			let rows = parseInt($('#rows').val()) - 1;
			$('#rows').val(rows);
			calculBalance();
			toggleDeleteButtons(rows);
		});
		toggleDeleteButtons(rows);
    </script>
@endsection