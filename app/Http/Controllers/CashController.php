<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Cash, Category, Transaction};
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};

class CashController extends Controller
{
    //Liste de la caisse
	public function index()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Gestion de la caisse';
		//Menu
		$currentMenu = 'cashs';
		//Modal
		$actionIds = Myhelper::actions(Auth::user()->profile_id, 2);
		$addmodal = in_array(2, $actionIds) ? '<a href="/cashs/create" class="btn btn-sm fw-bold btn-primary">Ajouter une caisse</a>':'';
		//Requete Read
		$query = Cash::orderByDesc('created_at')->get();
		Myhelper::logs(
			Session::get('username'),
			Session::get('profil'),
			"Caisse: Liste",
			'Consulter',
			Session::get('avatar')
		);
		return view('pages.cashs.index', compact('title', 'currentMenu', 'addmodal', 'actionIds', 'query'));
	}
	// Afficher le détail d'une caisse
	public function show($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Détail du produit';
		// Menu
		$currentMenu = 'cashs';
		// Vérifier si le produit existe
		$query = Cash::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Cash::show - Aucune caisse trouvé pour l'UID : {$uid}");
			return redirect('/cashs');
		}
		// Modal
		$addmodal = '<a href="/cashs" class="btn btn-sm fw-bold btn-danger">Retour</a>';
		return view('pages.cashs.show', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
	//Add produit
	public function create()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = "Ajout d'une caisse";
		//Menu
		$currentMenu = 'cashs';
		//Modal
		$addmodal = '<a href="/cashs" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Ajouter</a>';
		//Requete Read
		$list = Category::where('status', 1)->get();
		return view('pages.cashs.create', compact('title', 'currentMenu', 'addmodal', 'list'));
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
				Rule::unique('cashs')->where(function ($query) {
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
			Log::warning("Cash::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first(),
			]);
		}
		$photo = $request->file('photo')->store('cashs', 'public');
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
			Cash::create($set);
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Caisse: {$request->libelle}",
				'Ajouter',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Caisse enregistré avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Cash::store - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de l'enregistrement.",
			]);
		}
	}
	// Afficher le formulaire d'édition d'une caisse
	public function edit($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Modification du produit';
		// Menu
		$currentMenu = 'cashs';
		// Vérifier si le produit existe
		$query = Cash::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Cash::edit - Aucune produit trouvé pour l'UID : {$uid}");
			return redirect('/cashs');
		}
		// Modal
		$addmodal = '<a href="/cashs" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
		return view('pages.cashs.edit', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
	// Mettre à jour une caisse
	public function update(Request $request, $uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		// Validator
		$validator = Validator::make($request->all(), [
			'libelle' => [
				'required',
				Rule::unique('cashs')->where(function ($query) use ($uid) {
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
			Log::warning("Cash::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first(),
			]);
		}
		// Vérifier si le produit existe
		$query = Cash::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Cash::update - Aucune produit trouvé pour l'UID : {$uid}");
			return response()->json([
				'status' => false,
				'message' => "Caisse non trouvé.",
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
				Log::warning("Cash::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
				return response()->json([
					'status' => false,
					'message' => $validator->errors()->first(),
				]);
			}
			$set['photo'] = $photo = $request->file('photo')->store('cashs', 'public');
		}
		DB::beginTransaction(); // Démarrer une transaction
		try {
			// Mettre à jour le produit
			$query->update($set);
			DB::commit(); // Valider la transaction
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Caisse: {$request->libelle}",
				'Modifier',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Caisse modifié avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack(); // Annuler la transaction en cas d'erreur
			Log::warning("Cash::update - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de la modification.",
			]);
		}
	}
	// Supprimer une caisse
	public function destroy($uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		try {
			// Vérifier si le produit existe
			$product = Cash::where('uid', $uid)->first();
			if (!$product) {
				Log::warning("Cash::destroy - Aucune produit trouvé pour l'UID : {$uid}");
				return response()->json([
					'status' => false,
					'message' => "Caisse non trouvé.",
				]);
			}
			// Vérifier si des transactions sont associés
			$productCount = Transaction::where('product_id', $product->id)->count();
			if ($productCount > 0) {
				Log::warning("Cash::destroy - Ce produit est associé à {$productCount} transaction(s).");
				return response()->json([
					'status' => false,
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
				"Caisse: {$product->libelle}",
				'Supprimer',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Caisse supprimé avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Cash::destroy - Erreur : {$e->getMessage()}");
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de la suppression.",
			]);
		}
	}
    //Liste de la caisse
	public function geststock()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Gestion de stock de la caisse';
		//Menu
		$currentMenu = 'geststock';
		// Modal
		$addmodal = '';
		//Requete Read
		$query = Cash::where('category_id', 0)
		->orderBy('status')
		->orderBy('stock')
		->get();
		Myhelper::logs(
			Session::get('username'),
			Session::get('profil'),
			"Caisse: Gestion de stock",
			'Consulter',
			Session::get('avatar')
		);
		return view('pages.cashs.geststock', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
}
