<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Product, Transaction};
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};

class ProductController extends Controller
{
    //Liste des produits
	public function index()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Gestion des produits';
		//Menu
		$currentMenu = 'products';
		//Modal
		$actionIds = Myhelper::actions(Auth::user()->profile_id, 4);
		$addmodal = in_array(2, $actionIds) ? '<a href="/products/create" class="btn btn-sm fw-bold btn-primary">Ajouter un produit</a>':'';
		//Requete Read
		$query = Product::where('category_id', 0)
		->orderByDesc('created_at')
		->get();
		Myhelper::logs(
			Session::get('username'),
			Session::get('profil'),
			"Produit: Liste",
			'Consulter',
			Session::get('avatar')
		);
		return view('pages.products.index', compact('title', 'currentMenu', 'addmodal', 'actionIds', 'query'));
	}
	// Afficher le détail d'un produit
	public function show($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Détail du produit';
		// Menu
		$currentMenu = 'products';
		// Vérifier si le produit existe
		$query = Product::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Product::show - Aucun produit trouvé pour l'UID : {$uid}");
			return redirect('/products');
		}
		// Modal
		$addmodal = '<a href="/products" class="btn btn-sm fw-bold btn-danger">Retour</a>';
		return view('pages.products.show', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
	//Add produit
	public function create()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = "Ajout d'un produit";
		//Menu
		$currentMenu = 'products';
		//Modal
		$addmodal = '<a href="/products" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Ajouter</a>';
		return view('pages.products.create', compact('title', 'currentMenu', 'addmodal'));
	}
	//Add produit
	public function store(request $request)
	{
        if (!Auth::check()) {
            return 'x';
        }
		// Validator
		$validator = Validator::make($request->all(), [
			'libelle' => [
				'required',
				Rule::unique('products')->where(function ($query) {
					return $query->whereNull('deleted_at');
				}),
			],
			'description' => 'required',
			'seuil' => 'required|integer|min:1',
			'prix_achat' => 'required|integer|min:100|lte:prix_vente',
			'prix_vente' => 'required|integer|min:100',
			'photo' => 'required|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
		], [
			'libelle.required' => "Le libellé est obligatoire.",
			'libelle.unique' => "Le libellé existe déjà dans la base de données.",
			'description.required' => "La description est obligatoire.",
			'seuil.required'  => "Le seuil est obligatoire.",
			'seuil.integer'   => "Le seuil doit être un entier.",
			'seuil.min'       => "Le seuil doit être supérieur à 0.",
			'prix_achat.required'  => "Le prix d'achat est obligatoire.",
			'prix_achat.integer'   => "Le prix d'achat doit être un entier.",
			'prix_achat.min'       => "Le prix d'achat doit être supérieur à 100Fr.",
			'prix_achat.lte'       => "Le prix d'achat doit être inférieur ou égal au prix de vente.",
			'prix_vente.required'  => "Le prix de vente est obligatoire.",
			'prix_vente.integer'   => "Le prix de vente doit être un entier.",
			'prix_vente.min'       => "Le prix de vente doit être supérieur à 100Fr.",
			'photo.file' => "La photo doit être un fichier.",
			'photo.mimes' => "La photo doit être un fichier de type : png, jpg, jpeg, svg ou webp.",
			'photo.max' => "La photo ne doit pas être supérieur à 2Mo.",
		]);
		// Error field
		if ($validator->fails()) {
			Log::warning("Product::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => $validator->errors()->first(),
			]);
		}
		$photo = $request->file('photo')->store('products', 'public');
		$set = [
			'seuil' => $request->seuil,
			'libelle' => $request->libelle,
			'prix_achat' => $request->prix_achat,
			'prix_vente' => $request->prix_vente,
			'description' => $request->description,
			'photo' => $photo,
		];
		DB::beginTransaction();
		try {
			Product::create($set);
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Produit: {$request->libelle}",
				'Ajouter',
				Session::get('avatar')
			);
			return response()->json([
				'status' => 1,
				'message' => "Produit enregistré avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Product::store - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => "Erreur lors de l'enregistrement.",
			]);
		}
	}
	// Afficher le formulaire d'édition d'un produit
	public function edit($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Modification du produit';
		// Menu
		$currentMenu = 'products';
		// Vérifier si le produit existe
		$query = Product::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Product::edit - Aucune produit trouvé pour l'UID : {$uid}");
			return redirect('/products');
		}
		// Modal
		$addmodal = '<a href="/products" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
		return view('pages.products.edit', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
	// Mettre à jour un produit
	public function update(Request $request, $uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		// Validator
		$validator = Validator::make($request->all(), [
			'libelle' => [
				'required',
				Rule::unique('products')->where(function ($query) use ($uid) {
					return $query->where('uid', '!=', $uid)->whereNull('deleted_at');
				}),
			],
			'description' => 'required',
			'seuil' => 'required|integer|min:1',
			'prix_achat' => 'required|integer|min:100|lte:prix_vente',
			'prix_vente' => 'required|integer|min:100',
		], [
			'libelle.required' => "Le libellé est obligatoire.",
			'libelle.unique' => "Le libellé existe déjà dans la base de données.",
			'description.required' => "La description est obligatoire.",
			'seuil.required'  => "Le seuil est obligatoire.",
			'seuil.integer'   => "Le seuil doit être un entier.",
			'seuil.min'       => "Le seuil doit être supérieur à 0.",
			'prix_achat.required'  => "Le prix d'achat est obligatoire.",
			'prix_achat.integer'   => "Le prix d'achat doit être un entier.",
			'prix_achat.min'       => "Le prix d'achat doit être supérieur à 100Fr.",
			'prix_achat.lte'       => "Le prix d'achat doit être inférieur ou égal au prix de vente.",
			'prix_vente.required'  => "Le prix de vente est obligatoire.",
			'prix_vente.integer'   => "Le prix de vente doit être un entier.",
			'prix_vente.min'       => "Le prix de vente doit être supérieur à 100Fr.",
		]);
		// Error field
		if ($validator->fails()) {
			Log::warning("Product::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => $validator->errors()->first(),
			]);
		}
		// Vérifier si le produit existe
		$query = Product::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Product::update - Aucune produit trouvé pour l'UID : {$uid}");
			return response()->json([
				'status' => 0,
				'message' => "Produit non trouvé.",
			]);
		}
		$set = [
			'seuil' => $request->seuil,
			'libelle' => $request->libelle,
			'prix_achat' => $request->prix_achat,
			'prix_vente' => $request->prix_vente,
			'description' => $request->description,
		];
		$photo = '';
		if ($request->file('photo') != '') {
			// Validator
			$validator = Validator::make($request->all(), [
				'photo' => 'required|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
			], [
				'photo.file' => "La photo doit être un fichier.",
				'photo.mimes' => "La photo doit être un fichier de type : png, jpg, jpeg, svg ou webp.",
				'photo.max' => "La photo ne doit pas être supérieur à 2Mo.",
			]);
			// Error field
			if ($validator->fails()) {
				Log::warning("Product::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
				return response()->json([
					'status' => 0,
					'message' => $validator->errors()->first(),
				]);
			}
			$set['photo'] = $photo = $request->file('photo')->store('products', 'public');
		}
		DB::beginTransaction(); // Démarrer une transaction
		try {
			// Mettre à jour le produit
			$query->update($set);
			DB::commit(); // Valider la transaction
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Produit: {$request->libelle}",
				'Modifier',
				Session::get('avatar')
			);
			return response()->json([
				'status' => 1,
				'message' => "Produit modifié avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack(); // Annuler la transaction en cas d'erreur
			Log::warning("Product::update - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => "Erreur lors de la modification.",
			]);
		}
	}
	// Supprimer un produit
	public function destroy($uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		try {
			// Vérifier si le produit existe
			$product = Product::where('uid', $uid)->first();
			if (!$product) {
				Log::warning("Product::destroy - Aucune produit trouvé pour l'UID : {$uid}");
				return response()->json([
					'status' => 0,
					'message' => "Produit non trouvé.",
				]);
			}
			// Vérifier si des transactions sont associés
			$productCount = Transaction::where('product_id', $product->id)->count();
			if ($productCount > 0) {
				Log::warning("Product::destroy - Ce produit est associé à {$productCount} transaction(s).");
				return response()->json([
					'status' => 0,
					'message' => "Ce produit est associé à {$productCount} transaction(s).",
				]);
			}
			DB::beginTransaction();
			// Supprimer le produit
			$product->delete();
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Produit: {$product->libelle}",
				'Supprimer',
				Session::get('avatar')
			);
			return response()->json([
				'status' => 1,
				'message' => "Produit supprimé avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Product::destroy - Erreur : {$e->getMessage()}");
			return response()->json([
				'status' => 0,
				'message' => "Erreur lors de la suppression.",
			]);
		}
	}
    //Liste des produits
	public function geststock()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Gestion de stock des produits';
		//Menu
		$currentMenu = 'geststock';
		// Modal
		$addmodal = '';
		//Requete Read
		$query = Product::where('category_id', 0)
		->orderBy('status')
		->orderBy('stock')
		->get();
		Myhelper::logs(
			Session::get('username'),
			Session::get('profil'),
			"Produit: Gestion de stock",
			'Consulter',
			Session::get('avatar')
		);
		return view('pages.products.geststock', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
}
