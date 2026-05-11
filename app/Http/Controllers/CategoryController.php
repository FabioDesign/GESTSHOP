<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Category, Product, Transaction};
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};

class CategoryController extends Controller
{
    //Liste des Categories
	public function index()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Gestion des categories';
		//Menu
		$currentMenu = 'category';
		//Modal
		$actionIds = Myhelper::actions(Auth::user()->profile_id, 5);
		$addmodal = in_array(2, $actionIds) ? '<a href="/category/create" class="btn btn-sm fw-bold btn-primary">Ajouter une categorie</a>':'';
		//Requete Read
		$query = Product::where('category_id', '!=', 0)
		->orderByDesc('created_at')
		->get();
		Myhelper::logs(
			Session::get('username'),
			Session::get('profil'),
			"Categories: Liste",
			'Consulter',
			Session::get('avatar')
		);
		return view('pages.category.index', compact('title', 'currentMenu', 'addmodal', 'actionIds', 'query'));
	}
	// Afficher le détail de la categorie
	public function show($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Détail de la categorie';
		// Menu
		$currentMenu = 'category';
		// Vérifier si la categorie existe
		$query = Product::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Category::show - Aucune categorie trouvée pour l'UID : {$uid}");
			return redirect('/category');
		}
		// Modal
		$addmodal = '<a href="/category" class="btn btn-sm fw-bold btn-danger">Retour</a>';
		return view('pages.category.show', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
    //Liste des Categories
	public function create()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = "Ajout d'une categorie";
		//Menu
		$currentMenu = 'category';
		//Modal
		$addmodal = '<a href="/category" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Ajouter</a>';
		//Requete Read
		$list = Category::where('status', 1)->get();
		return view('pages.category.create', compact('title', 'currentMenu', 'addmodal', 'list'));
	}
	//Add categories
	public function store(Request $request)
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
			'category_id' => 'required',
		], [
			'libelle.required' => "La categorie est obligatoire.",
			'libelle.unique' => "La categorie existe déjà dans la base de données.",
			'category_id.required' => "Le type est obligatoire.",
		]);
		// Error field
		if ($validator->fails()) {
			Log::warning("Category::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first(),
			]);
		}
		$set = [
			'libelle' => $request->libelle,
			'description' => $request->description,
			'category_id' => $request->category_id,
		];
		DB::beginTransaction();
		try {
			Product::create($set);
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Categories: {$request->libelle}",
				'Ajouter',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Categorie enregistrée avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Category::store - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de l'enregistrement.",
			]);
		}
	}
	// Afficher le formulaire d'édition d'une categorie
	public function edit($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Modification de la categorie';
		// Menu
		$currentMenu = 'category';
		// Vérifier si la categorie existe
		$query = Product::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Category::edit - Aucune categorie trouvée pour l'UID : {$uid}");
			return redirect('/category');
		}
		// Modal
		$addmodal = '<a href="/category" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
		//Requete Read
		$list = Category::where('status', 1)->get();
		return view('pages.category.edit', compact('title', 'currentMenu', 'addmodal', 'query', 'list'));
	}
	// Mettre à jour une categories
	public function update(Request $request, $uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
        try {
			// Vérifier si le categories existe
			$product = Product::where('uid', $uid)->first();
			if (!$product) {
				Log::warning("Category::update - Aucune categorie trouvée pour l'UID : {$uid}");
				return response()->json([
					'status' => false,
					'message' => "Categorie non trouvée.",
				]);
			}
			// Validator
			$validator = Validator::make($request->all(), [
				'libelle' => [
					'required',
					Rule::unique('products')->where(function ($query) use ($uid) {
						return $query->where('uid', '!=', $uid)->whereNull('deleted_at');
					}),
				],
				'category_id' => 'required',
			], [
				'libelle.required' => "La categorie est obligatoire.",
				'libelle.unique' => "La categorie existe déjà dans la base de données.",
				'category_id.required' => "Le type est obligatoire.",
			]);
			// Error field
			if ($validator->fails()) {
				Log::warning("Category::update - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
				return response()->json([
					'status' => false,
					'message' => $validator->errors()->first(),
				]);
			}
			$set = [
				'libelle' => $request->libelle,
				'description' => $request->description,
				'category_id' => $request->category_id,
			];
			DB::beginTransaction(); // Démarrer une transaction
			// Mettre à jour la categorie
			$product->update($set);
			DB::commit(); // Valider la transaction
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Categories: {$request->libelle}",
				'Modifier',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Categorie modifiée avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack(); // Annuler la transaction en cas d'erreur
			Log::warning("Category::update - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de la modification.",
			]);
		}
	}
	// Supprimer une categories
	public function destroy($uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		try {
			// Vérifier si la categorie existe
			$product = Product::where('uid', $uid)->first();
			if (!$product) {
				Log::warning("Category::destroy - Aucune categorie trouvée pour l'UID : {$uid}");
				return response()->json([
					'status' => false,
					'message' => "Categorie non trouvée.",
				]);
			}
			// Vérifier si des transactions sont associés
			$categoryCount = Transaction::where('product_id', $product->id)->count();
			if ($categoryCount > 0) {
				Log::warning("Category::destroy - Cette categorie est associée à {$categoryCount} transaction(s).");
				return response()->json([
					'status' => false,
					'message' => "Cette categorie est associée à {$categoryCount} transaction(s).",
				]);
			}
			DB::beginTransaction();
			// Supprimer la categorie
			$product->delete();
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Categories: " . $product->libelle,
				'Supprimer',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Categorie supprimée avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Category::destroy - Erreur : {$e->getMessage()}");
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de la suppression.",
			]);
		}
	}
    //Liste des produits
	public function getCategory($type)
	{
        if (!Auth::check()) {
            return 'x';
        }
		try {
			//Requete Read
			$query = Product::select('id', 'libelle', 'category_id')
            ->where('category_id', $type)
			->orWhere('category_id', 0)
			->orderByDesc('category_id')
			->orderBy('libelle')
			->get();
			return response()->json([
				'status' => true,
				'data' => $query,
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Category::getCategory - Erreur : {$e->getMessage()}");
			return response()->json([
				'status' => false,
				'message' => "Erreur de chargement des données.",
			]);
		}
	}
}
