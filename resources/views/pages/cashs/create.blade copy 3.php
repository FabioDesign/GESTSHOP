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
			<div id="kt_docs_repeater_basic">
				<!--begin::Form group-->
				<div class="form-group">
					<div data-repeater-list="kt_docs_repeater_basic">
						<div data-repeater-item>
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
									<select name="category_id" class="form-select category_id requiredField">
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
									<input type="text" name="total" class="form-control requiredField text-end amount total" placeholder="0" readonly />
								</div>
								<div class="col-md-1">
									<a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
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
            	<span class="msgError" style="display: none;"></span>

				<!--begin::Form group-->
				<div class="form-group mt-5">
					<a href="javascript:;" data-repeater-create class="btn btn-light-primary">
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
    <script src="/assets/js/custom/select2.js"></script>
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
		// Select 2
		$('.category_id').select2({
			placeholder: "Sélectionner",
			width: '100%',
		});
		// Désactiver le bouton de suppression du premier élément
		$('[data-repeater-delete]').addClass('disabled').css('pointer-events', 'none');
		// Formulaire repété
		$('#kt_docs_repeater_basic').repeater({
			initEmpty: false,

			defaultValues: {
				'text-input': 'foo'
			},

			show: function () {
				$(this).slideDown();
			},

			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});
		// Selectionner les types de catégories
		$('.type_id').on('change', async function () {
			const type_id = $(this).val();
			getCategory(type_id);
		});
		const getCategory = async (type_id) => {
			if (type_id == '') return;
			const selectCategory = $('.category_id');
			selectCategory.empty();
			
			if (type_id != 0) {
				selectCategory.append(
					`<option value="" selected>Sélectionner</option>`
				);
				try {
					const response = await axios.get( '/getCategory/' + type_id);
					const item = response.data.data || [];
					if (item.length > 0) {
						item.map(data => {
							selectCategory.append(
								`<option value="${data.id}">${data.libelle}</option>`
							);
						});
					}
				} catch (e) {
					console.error(e);
				}
			}
		}
		// Selectionner les produits
		$('#category_id').on('change', async function () {
			const category_id = $(this).val();
			getProduct(category_id);
		});
		const getProduct = async (category_id) => {
			if (category_id == '') return;
			$('.amount').val('');
			
			if (category_id != 0) {
				try {
					const response = await axios.get( '/getProduct/' + category_id);
					const item = response.data.data || [];
					if (item.length > 0) {
						item.map(data => {
							if(data.category_id == 0) {
								$('.amount').val(data.price);
							}
						});
					}
				} catch (e) {
					console.error(e);
				}
			}
		}
    </script>
@endsection