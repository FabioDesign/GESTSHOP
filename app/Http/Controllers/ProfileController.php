<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Menu, Permission, Profile, User};
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};

class ProfileController extends Controller
{
    //Liste des Profils
	public function index()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Gestion des Profils';
		//Menu
		$currentMenu = 'profiles';
		//Modal
		$actionIds = Myhelper::actions(Auth::user()->profile_id, 6);
		$addmodal = in_array(2, $actionIds) ? '<a href="/profiles/create" class="btn btn-sm fw-bold btn-primary">Ajouter un profil</a>':'';
		//Requete Read
		$query = Profile::where('id', '!=', 1)->orderByDesc('created_at')->get();
		Myhelper::logs(
			Session::get('username'),
			Session::get('profil'),
			"Profil: Liste",
			'Consulter',
			Session::get('avatar')
		);
		return view('pages.profiles.index', compact('title', 'currentMenu', 'addmodal', 'actionIds', 'query'));
	}
	// Afficher le détail d'un profil
	public function show($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Détail du Profil';
		// Menu
		$currentMenu = 'profiles';
		// Vérifier si le profil existe
		$query = Profile::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Profile::show - Aucun profil trouvé pour l'UID : {$uid}");
			return redirect('/profiles');
		}
		// Modal
		$addmodal = '<a href="/profiles" class="btn btn-sm fw-bold btn-danger">Retour</a>';
		// Récupérer les menus avec leurs actions
		$menusWithActions = Menu::with('actions')
			->where('status', 1)
			->orderBy('position')
			->get();
		// Récupérer les permissions actuelles du profil
		$currentPermissions = Permission::where('profile_id', $query->id)
			->get()
			->map(function($permission) {
				return $permission->menu_id . '|' . $permission->action_id;
			})
			->toArray();
		return view('pages.profiles.show', compact('title', 'currentMenu', 'addmodal', 'menusWithActions', 'query', 'currentPermissions'));
	}
    //Liste des Profils
	public function create()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = "Ajout d'un Profil";
		//Menu
		$currentMenu = 'profiles';
		//Modal
		$addmodal = '<a href="/profiles" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Ajouter</a>';
		// Dans votre contrôleur
		$menusWithActions = Menu::with('actions')
			->where('status', 1)
			->orderBy('position')
			->get();
		return view('pages.profiles.create', compact('title', 'currentMenu', 'addmodal', 'menusWithActions'));
	}
	//Add/Mod Profil
	public function store(request $request)
	{
        if (!Auth::check()) {
            return 'x';
        }
		// Validator
		$validator = Validator::make($request->all(), [
			'libelle' => [
				'required',
				Rule::unique('profiles')->where(function ($query) {
					return $query->whereNull('deleted_at');
				}),
			],
			'description' => 'required',
			'permissions' => 'required|array',
		], [
			'libelle.required' => "Le libellé est obligatoire.",
			'libelle.unique' => "Le libellé existe déjà dans la base de données.",
			'description.required' => "La description est obligatoire.",
			'permissions.required' => "Cocher au moins une case.",
			'permissions.array' => "Format des permissions invalide.",
		]);
		// Error field
		if ($validator->fails()) {
			Log::warning("Profile::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first(),
			]);
		}
		$set = [
			'libelle' => $request->libelle,
			'description' => $request->description,
		];
		DB::beginTransaction(); // Démarrer une transaction
		try {
			$query = Profile::create($set);
			// Valider la transaction
			DB::commit();
			// Si des permissions sont fournies, les associer au profil
			if ($request->has('permissions') && is_array($request->permissions)) {
				foreach ($request->permissions as $permissions) {
					$permission = Str::of($permissions)->explode('|');
					// Enregistrer la permission
					Permission::firstOrCreate([
						'menu_id' => $permission[0],
						'action_id' => $permission[1],
						'profile_id' => $query->id,
					]);
				}
			}
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Profil: {$request->libelle}",
				'Ajouter',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Profil enregistré avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack(); // Annuler la transaction en cas d'erreur
			Log::warning("Profile::store - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de l'enregistrement.",
			]);
		}
	}
	// Afficher le formulaire d'édition d'un profil
	public function edit($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Modification du Profil';
		// Menu
		$currentMenu = 'profiles';
		// Vérifier si le profil existe
		$query = Profile::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("Profile::edit - Aucun profil trouvé pour l'UID : {$uid}");
			return redirect('/profiles');
		}
		// Modal
		$addmodal = '<a href="/profiles" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
		// Récupérer les menus avec leurs actions
		$menusWithActions = Menu::with('actions')
			->where('status', 1)
			->orderBy('position')
			->get();
		// Récupérer les permissions actuelles du profil
		$currentPermissions = Permission::where('profile_id', $query->id)
			->get()
			->map(function($permission) {
				return $permission->menu_id . '|' . $permission->action_id;
			})
			->toArray();
		return view('pages.profiles.edit', compact('title', 'currentMenu', 'addmodal', 'menusWithActions', 'query', 'currentPermissions'));
	}
	// Mettre à jour un profil
	public function update(Request $request, $uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
        try {
			// Vérifier si le profil existe
			$profile = Profile::where('uid', $uid)->first();
			if (!$profile) {
				Log::warning("Profile::show - Aucun profil trouvé pour l'UID : {$uid}");
				return response()->json([
					'status' => false,
					'message' => "Profil non trouvé.",
				]);
			}
			// Validator
			$validator = Validator::make($request->all(), [
				'libelle' => [
					'required',
					Rule::unique('profiles')->where(function ($query) use ($uid) {
						return $query->where('uid', '!=', $uid)->whereNull('deleted_at');
					}),
				],
				'description' => 'required',
				'permissions' => 'required|array',
			], [
				'libelle.required' => "Le libellé est obligatoire.",
				'libelle.unique' => "Le libellé existe déjà dans la base de données.",
				'description.required' => "La description est obligatoire.",
				'permissions.required' => "Cocher au moins une case.",
				'permissions.array' => "Format des permissions invalide.",
			]);
			// Error field
			if ($validator->fails()) {
				Log::warning("Profile::update - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
				return response()->json([
					'status' => false,
					'message' => $validator->errors()->first(),
				]);
			}
			$set = [
				'libelle' => $request->libelle,
				'description' => $request->description,
			];
			DB::beginTransaction(); // Démarrer une transaction
			// Mettre à jour le profil
			$profile->update($set);
			// Supprimer les anciennes permissions
			Permission::where('profile_id', $profile->id)->delete();
			// Ajouter les nouvelles permissions
			if ($request->has('permissions') && is_array($request->permissions)) {
				foreach ($request->permissions as $permissionValue) {
					$permission = Str::of($permissionValue)->explode('|');
					// Enregistrer la permission
					Permission::firstOrCreate([
						'menu_id' => $permission[0],
						'action_id' => $permission[1],
						'profile_id' => $profile->id,
					]);
				}
			}
			DB::commit(); // Valider la transaction
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Profil: {$request->libelle}",
				'Modifier',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Profil modifié avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack(); // Annuler la transaction en cas d'erreur
			Log::warning("Profile::update - Erreur : {$e->getMessage()} " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de la modification.",
			]);
		}
	}
	// Supprimer un profil
	public function destroy($uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		try {
			// Vérifier si le profil existe
			$query = Profile::where('uid', $uid)->first();
			if (!$query) {
				Log::warning("Profile::destroy - Aucun profil trouvé pour l'UID : {$uid}");
				return response()->json([
					'status' => false,
					'message' => "Profil non trouvé.",
				]);
			}
			// Ne pas permettre la désactivation du profil admin
			if ($query->id == 1) {
				Log::warning("Profile::destroy - Profil administrateur pour l'UID : {$uid}");
				return response()->json([
					'status' => false,
					'message' => "Le profil administrateur ne peut pas être supprimé.",
				]);
			}
			// Vérifier si des utilisateurs sont associés
			$userCount = User::where('profile_id', $query->id)->count();
			if ($userCount > 0) {
				Log::warning("Profile::destroy - Ce profil est associé à {$userCount} utilisateur(s).");
				return response()->json([
					'status' => false,
					'message' => "Ce profil est associé à {$userCount} utilisateur(s).",
				]);
			}
			DB::beginTransaction();
			// Supprimer les permissions associées
			Permission::where('profile_id', $query->id)->delete();
			// Supprimer le profil
			$query->delete();
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Profil: " . $query->libelle,
				'Supprimer',
				Session::get('avatar')
			);
			return response()->json([
				'status' => true,
				'message' => "Profil supprimé avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("Profile::destroy - Erreur : {$e->getMessage()}");
			return response()->json([
				'status' => false,
				'message' => "Erreur lors de la suppression.",
			]);
		}
	}
}
