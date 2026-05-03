<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Demand, Document, User};
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};

class DemandController extends Controller
{
    // Liste des demandes consulaires
	public function index()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Gestion des demandes consulaires';
		// Menu
		$currentMenu = 'demands';
		// Modal
		$actionIds = Myhelper::actions(Auth::user()->profile_id, 2);
		$addmodal = in_array(2, $actionIds) ? '<a href="/demands/create" class="btn btn-sm fw-bold btn-primary">Ajouter une demande</a>':'';
		// Requete Read
		$query = Demand::orderByDesc('created_at')->get();
		return view('pages.demands.index', compact('title', 'currentMenu', 'addmodal', 'actionIds', 'query'));
	}
    // Liste des demandes consulaires
	public function getDemands() {
		//Requete Read
		$query = Demand::orderBy('status')
		->orderByDesc('created_at')
		->get();
		// Transformer les données
		$demands = $query->map(fn($data) => [
			'uid' => $data->uid,
			'code' => $data->code,
			'libelle' => $data->document->libelle,
			'number' => $data->number,
			'email' => $data->email,
			'company' => $data->company,
			'status' => match((int)$data->status) {
				0 => __('message.inactive'),
				1 => __('message.active'),
				2 => __('message.blocked'),
			},
			'created_at' => $data->created_at->format('d/m/Y H:i'),
		]);
		return response()->json([
			'status' => true,
			'data' => $demands,
		]);
	}
	// Afficher le détail d'une demande
	public function show($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Détail du document consulaire';
		// Menu
		$currentMenu = 'demands';
		// Vérifier si le document existe
		$query = Demand::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Demand::show - Aucune demande trouvée pour l'UID : {$uid}");
			return redirect('/demands');
		}
		// Modal
		$addmodal = '<a href="/demands" class="btn btn-sm fw-bold btn-danger">Retour</a>';
		return view('pages.demands.show', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
    //Liste des demandes
	public function create()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = "Ajout d'une demande";
		//Menu
		$currentMenu = 'demands';
		//Modal
		$addmodal = '<a href="/demands" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Ajouter</a>';
		return view('pages.demands.create', compact('title', 'currentMenu', 'addmodal'));
	}
	//Add document
	public function store(request $request)
	{
        if (!Auth::check()) {
            return 'x';
        }
		// Validator
		$validator = Validator::make($request->all(), [
			'libelle' => [
				'required',
				Rule::unique('demands')->where(function ($query) {
					return $query->whereNull('deleted_at');
				}),
			],
			'amount' => 'required|integer|min:1',
			'day' => 'required|integer|min:1',
			'description' => 'required',
		], [
			'libelle.required' => "Le document est obligatoire.",
			'libelle.unique' => "Le document existe déjà dans la base de données.",
			'amount.*' => "Le montant est obligatoire et doit être un entier.",
			'day.*' => "Le nombre de jours est obligatoire et doit être un entier.",
			'description.required' => "La description est obligatoire.",
		]);
		// Error field
		if ($validator->fails()) {
			Log::warning("Demand::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => $validator->errors()->first(),
			]);
		}
		$set = [
			'day' => $request->day,
			'amount' => $request->amount,
			'icone' => "far fa-address-card",
			'description' => $request->description,
			'libelle' => Str::upper(Myhelper::valideString($request->libelle)),
		];
		DB::beginTransaction();
		try {
			Demand::create($set);
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Document consulaire: {$request->libelle}",
				'Ajouter',
				Session::get('avatar')
			);
			return response()->json([
				'status' => 1,
				'message' => "Document consulaire enregistré avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Demand::store - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => "Erreur lors de l'enregistrement.",
			]);
		}
	}
	// Afficher le formulaire d'édition d'une demande
	public function edit($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Modification du document consulaire';
		// Menu
		$currentMenu = 'demands';
		// Vérifier si le document existe
		$query = Demand::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Demand::edit - Aucune document trouvé pour l'UID : {$uid}");
			return redirect('/demands');
		}
		// Modal
		$addmodal = '<a href="/demands" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
		return view('pages.demands.edit', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
	// Mettre à jour une demande
	public function update(Request $request, $uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		// Validator
		$validator = Validator::make($request->all(), [
			'libelle' => [
				'required',
				Rule::unique('demands')->where(function ($query) use ($uid) {
					return $query->where('uid', '!=', $uid)->whereNull('deleted_at');
				}),
			],
			'amount' => 'required|integer|min:1',
			'day' => 'required|integer|min:1',
			'description' => 'required',
		], [
			'libelle.required' => "Le document est obligatoire.",
			'libelle.unique' => "Le document existe déjà dans la base de données.",
			'amount.*' => "Le montant est obligatoire et doit être un entier.",
			'day.*' => "Le nombre de jours est obligatoire et doit être un entier.",
			'description.required' => "La description est obligatoire.",
		]);
		// Error field
		if ($validator->fails()) {
			Log::warning("Demand::update - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => $validator->errors()->first(),
			]);
		}
		// Vérifier si le document existe
		$query = Demand::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Demand::update - Aucune document trouvé pour l'UID : {$uid}");
			return response()->json([
				'status' => 0,
				'message' => "Document consulaire non trouvé.",
			]);
		}
		$set = [
			'day' => $request->day,
			'amount' => $request->amount,
			'description' => $request->description,
			'libelle' => Str::upper(Myhelper::valideString($request->libelle)),
		];
		DB::beginTransaction(); // Démarrer une transaction
		try {
			// Mettre à jour le document
			$query->update($set);
			DB::commit(); // Valider la transaction
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Document consulaire: {$request->libelle}",
				'Modifier',
				Session::get('avatar')
			);
			return response()->json([
				'status' => 1,
				'message' => "Document consulaire modifié avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack(); // Annuler la transaction en cas d'erreur
			Log::warning("Demand::update - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => "Erreur lors de la modification.",
			]);
		}
	}
	// Supprimer une demande
	public function destroy($uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		try {
			// Vérifier si le document existe
			$document = Demand::where('uid', $uid)->first();
			if (!$document) {
				Log::warning("Demand::destroy - Aucune document trouvé pour l'UID : {$uid}");
				return response()->json([
					'status' => 0,
					'message' => "Document consulaire non trouvé.",
				]);
			}
			// Vérifier si des utilisateurs sont associés
			$documentCount = User::where('document_id', $document->id)->count();
			if ($documentCount > 0) {
				Log::warning("Demand::destroy - Cet document est associé à {$documentCount} utilisateur(s).");
				return response()->json([
					'status' => 0,
					'message' => "Cet document est associé à {$documentCount} utilisateur(s).",
				]);
			}
			DB::beginTransaction();
			// Supprimer le document
			$document->delete();
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Document consulaire: {$document->libelle}",
				'Supprimer',
				Session::get('avatar')
			);
			return response()->json([
				'status' => 1,
				'message' => "Document consulaire supprimé avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Demand::destroy - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => "Erreur lors de la suppression.",
			]);
		}
	}
}
