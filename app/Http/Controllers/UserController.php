<?php
namespace App\Http\Controllers; 

use Session;
use Myhelper;
use \Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Cash, Logs, Permission, Profile, User};
use Illuminate\Support\Facades\{DB, Hash, Log, Validator, Auth};

class UserController extends Controller
{    
    // Liste des utilisateurs
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Gestion des utilisateurs';
		//Menu
		$currentMenu = 'users';
		//Modal
		$actionIds = Myhelper::actions(Auth::user()->profile_id, 6);
		$addmodal = in_array(2, $actionIds) ? '<a href="/users/create" class="btn btn-sm fw-bold btn-primary">Ajouter un utilisateur</a>':'';
		//Requete Read
		$query = User::where('id', '!=', 1)->orderByDesc('created_at')->get();
		Myhelper::logs(
			Session::get('username'),
			Session::get('profil'),
			"Utilisateur: Liste",
			'Consulter',
			Session::get('avatar')
		);
        return view('pages.users.index', compact('title', 'currentMenu', 'addmodal', 'actionIds', 'query'));
    }
    // Détail d'Utilisateur
	public function show($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = "Détail de l'utilisateur";
		// Menu
		$currentMenu = 'users';
		// Vérifier si l'utilisateur existe
		$query = User::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("User::show - Aucun utilisateur trouvée pour l'UID : {$uid}");
			return redirect('/users');
		}
		// Modal
		$addmodal = '<a href="/users" class="btn btn-sm fw-bold btn-danger">Retour</a>';
		return view('pages.users.show', compact('title', 'currentMenu', 'addmodal', 'query'));
	}
    //Liste des utilisateurs
	public function create()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = "Ajout d'un utilisateur";
		//Menu
		$currentMenu = 'users';
		//Modal
		$addmodal = '<a href="/users" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Ajouter</a>';
        $gender = ['M' => 'Masculin', 'F' => 'Féminin'];
		$profile = Profile::where('id', '!=', 1)->orderBy('libelle')->get();
		return view('pages.users.create', compact('title', 'currentMenu', 'addmodal', 'gender', 'profile'));
	}
    // Account creation
    public function store(Request $request)
    {
		// Validator
		$validator = Validator::make($request->all(), [
			'gender' => 'required|in:M,F',
			'lastname' => 'required',
			'firstname' => 'required',
			'number' => [
				'required',
				'digits:10',
				Rule::unique('users')->where(function ($query) {
					return $query->whereNull('deleted_at');
				}),
			],
			'email' => [
				'required',
				Rule::unique('users')->where(function ($query) {
					return $query->whereNull('deleted_at');
				}),
			],
			'profile_id' => 'required',
		], [
			'gender.required' => "Le genre est obligatoire.",
			'gender.in' => "Le genre est incorrecte.",
			'lastname.required' => "Le nom est obligatoire.",
			'firstname.required' => "Les prénoms sont obligatoires.",
			'number.required' => "Le numéro de téléphone est obligatoire.",
			'number.digits' => "Le numéro de téléphone doit être 10 caractères.",
			'number.unique' => "Le numéro de téléphone existe déjà dans la base de données.",
			'email.required' => "L'email est obligatoire.",
			'email.unique' => "L'email existe déjà dans la base de données.",
			'profile_id.required' => "Le profil est obligatoire.",
		]);
		// Error field
		if ($validator->fails()) {
			Log::warning("User::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => $validator->errors()->first(),
			]);
		}
        // Formatage du nom et prénoms
        $lastname = mb_strtoupper($request->lastname, 'UTF-8');
        $firstname = mb_convert_case(Str::lower($request->firstname), MB_CASE_TITLE, "UTF-8");
        $set = [
            'gender' => $request->gender,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'number' => $request->number,
            'email' => Str::lower($request->email),
            'profile_id' => $request->profile_id,
            'password_at' => now(),
            'password' => Hash::make('Azerty@123'),
        ];
        DB::beginTransaction(); // Démarrer une transaction
        try {
            // Création de l'utilisateur
            User::create($set);
            DB::commit(); // Valider la transaction
            Myhelper::logs(
                Session::get('username'),
                Session::get('profil'),
                "Utilisateur: {$lastname} {$firstname}",
                'Ajouter',
                Session::get('avatar')
            );
            return response()->json([
                'status' => 1,
                'message' => "Utilisateur enregistré avec succès.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler la transaction en cas d'erreur
            Log::warning("User::store - Erreur : {$e->getMessage()} " . json_encode($request->all()));
            return response()->json([
                'status' => 0,
                'message' => "Erreur lors de l'enregistrement.",
            ]);
        }
    }
	// Afficher le formulaire d'édition d'un utilisateur
	public function edit($uid)
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = "Modification d'un utilisateur";
		// Menu
		$currentMenu = 'users';
		// Vérifier si l'utilisateur existe
		$query = User::where('uid', $uid)->first();
		if (!$query) {
			Log::warning("User::edit - Aucun utilisateur trouvée pour l'UID : {$uid}");
			return redirect('/users');
		}
		// Modal
		$addmodal = '<a href="/users" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
        $gender = ['M' => 'Masculin', 'F' => 'Féminin'];
		$profile = Profile::where('id', '!=', 1)->orderBy('libelle')->get();
		return view('pages.users.edit', compact('title', 'currentMenu', 'addmodal', 'query', 'gender', 'profile'));
	}
    // Modification
    public function update(Request $request, $uid)
    {
        if (!Auth::check()) {
            return 'x';
        }
        try {
            // Vérifier si l'utilisateur existe
            $user = User::where('uid', $uid)->first();
            if (!$user) {
                Log::warning("User::update - Aucun utilisateur trouvé pour l'UID : {$uid}");
                return response()->json([
                    'status' => 0,
                    'message' => "Utilisateur non trouvé.",
                ]);
            }
            // Validator
            $validator = Validator::make($request->all(), [
                'gender' => 'required|in:M,F',
                'lastname' => 'required',
                'firstname' => 'required',
                'number' => [
                    'required',
                    Rule::unique('users')->where(function ($query) use ($uid) {
                        return $query->where('uid', '!=', $uid)->whereNull('deleted_at');
                    }),
                ],
                'email' => [
                    'required',
                    Rule::unique('users')->where(function ($query) use ($uid) {
                        return $query->where('uid', '!=', $uid)->whereNull('deleted_at');
                    }),
                ],
                'profile_id' => 'required',
            ], [
                'gender.required' => "Le genre est obligatoire.",
                'gender.in' => "Le genre est incorrecte.",
                'lastname.required' => "Le nom est obligatoire.",
                'firstname.required' => "Les prénoms sont obligatoires.",
                'number.required' => "Le numéro de téléphone est obligatoire.",
                'number.unique' => "Le numéro de téléphone existe déjà dans la base de données.",
                'email.required' => "L'email est obligatoire.",
                'email.unique' => "L'email existe déjà dans la base de données.",
                'profile_id.required' => "Le profil est obligatoire.",
            ]);
            // Error field
            if ($validator->fails()) {
                Log::warning("User::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                ]);
            }
            // Formatage du nom et prénoms
            $lastname = mb_strtoupper($request->lastname, 'UTF-8');
            $firstname = mb_convert_case(Str::lower($request->firstname), MB_CASE_TITLE, "UTF-8");
            $set = [
                'gender' => $request->gender,
                'lastname' => $lastname,
                'firstname' => $firstname,
                'number' => $request->number,
                'email' => Str::lower($request->email),
                'profile_id' => $request->profile_id,
                'password_at' => now(),
                'password' => Hash::make('Azerty@123'),
            ];
            $avatar = '';
            if ($request->file('avatar') != '') {
                // Validator
                $validator = Validator::make($request->all(), [
                    'avatar' => 'required|file|mimes:png,jpg,jpeg|max:2048',
                ], [
                    'avatar.file' => "L'avatar doit être un fichier.",
                    'avatar.mimes' => "L'avatar doit être un fichier de type : png, jpg ou jpeg",
                    'avatar.max' => "L'avatar ne doit pas être supérieur à 2Mo.",
                ]);
                // Error field
                if ($validator->fails()) {
                    Log::warning("User::store - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
                    return response()->json([
                        'status' => 0,
                        'message' => $validator->errors()->first(),
                    ]);
                }
                $set['avatar'] = $avatar = $request->file('avatar')->store('avatars', 'public');
            }
            DB::beginTransaction(); // Démarrer une transaction
			// Mettre à jour l'utilisateur
			$user->update($set);
            DB::commit(); // Valider la transaction
            Myhelper::logs(
                Session::get('username'),
                Session::get('profil'),
                "Utilisateur: {$lastname} {$firstname}",
                'Modifier',
                Session::get('avatar')
            );
            if ($request->has('account')) {
                // Préparer les données de session
                $prenom = explode(' ', $firstname);
                $username = $prenom[0] . ' ' . $lastname;
                Session::put('username', $username);
                // Avatar
                if ($avatar != '')
                Session::put('avatar', $avatar);
            }
            return response()->json([
                'status' => 1,
                'message' => "Utilisateur modifié avec succès.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler la transaction en cas d'erreur
            Log::warning("User::store - Erreur : {$e->getMessage()} " . json_encode($request->all()));
            return response()->json([
                'status' => 0,
                'message' => "Erreur lors de la modification.",
            ]);
        }
	}
	// Supprimer un utilisateur
	public function destroy($uid)
	{
        if (!Auth::check()) {
            return 'x';
        }
		try {
            // Vérifier si l'utilisateur existe
            $user = User::where('uid', $uid)->first();
            if (!$user) {
                Log::warning("User::destroy - Aucun utilisateur trouvé pour l'UID : {$uid}");
                return response()->json([
                    'status' => 0,
                    'message' => "Utilisateur non trouvé.",
                ]);
            }
			// Vérifier si des utilisateurs sont associés
			$userCount = Cash::where('created_by', $user->id)->count();
			if ($userCount > 0) {
				Log::warning("User::destroy - Cet utilisateur est associée à {$userCount} document(s).");
				return response()->json([
					'status' => 0,
					'message' => "Cet utilisateur est associée à {$userCount} document(s).",
				]);
			}
			DB::beginTransaction();
			// Supprimer l'utilisateur
			$user->delete();
			DB::commit();
			Myhelper::logs(
				Session::get('username'),
				Session::get('profil'),
				"Utilisateur: {$user->firstname} {$user->lastname}",
				'Supprimer',
				Session::get('avatar')
			);
			return response()->json([
				'status' => 1,
				'message' => "Utilisateur supprimé avec succès.",
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::warning("User::destroy - Erreur : {$e->getMessage()}");
			return response()->json([
				'status' => 0,
				'message' => "Erreur lors de la suppression.",
			]);
		}
	}
	// Info perso user
	public function account() {
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = "Modification de mon profil";
		// Menu
		$currentMenu = 'users';
		// Modal
		$addmodal = '<a href="/users" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
        $gender = ['M' => 'Masculin', 'F' => 'Féminin'];
        $query = User::where('id', Auth::user()->id)->first();
        // Avatar
        if ($query->avatar == '')
        $query->avatar = $query->gender == 'M' ? 'avatars/homme.jpg' : 'avatars/femme.jpg';
		$profile = Profile::all();
		return view('pages.users.account', compact('title', 'currentMenu', 'addmodal', 'query', 'gender', 'profile'));
	}
    // Connexion
	public function login()
    {
        return view('login');
	}
    // Authentification avec Laravel Auth
    public function auth(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required|min:8',
        ], [
            'login.required' => "Login ou mot de passe incorrect.",
            'password.*' => "Login ou mot de passe incorrect.",
        ]);
        // Error field
        if ($validator->fails()) {
            Log::warning("User::auth - Validator : {$validator->errors()->first()}");
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {
            // Déterminer si le login est un email ou un numéro de téléphone
            $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'number';
            // Tentative de connexion avec Laravel Auth
            $credentials = [
                $loginField => $request->login,
                'password' => $request->password,
                'status' => 1, // Compte actif
            ];
            // Vérifier d'abord si l'utilisateur existe et son statut
            $user = User::where($loginField, $request->login)->first();
            if (!$user) {
                return response()->json([
                    'status' => 0,
                    'message' => "Login ou mot de passe incorrect.",
                ]);
            }
            // Vérifier le statut du compte
            if ($user->status == 0) {
                return response()->json([
                    'status' => 0,
                    'message' => "Votre compte est inactif.",
                ]);
            }
            if ($user->status == 2) {
                return response()->json([
                    'status' => 0,
                    'message' => "Votre compte est bloqué.",
                ]);
            }
            // Vérifier le statut du profil
            if ($user->profile && $user->profile->status == 0) {
                return response()->json([
                    'status' => 0,
                    'message' => "Votre profil est désactivé.",
                ]);
            }
            // Tentative de connexion
            if (Auth::attempt($credentials)) {
                // Connexion réussie
                $user = Auth::user();
                // Mise à jour de la dernière connexion
                $user->update([
                    'login_at' => now(),
                ]);
                // Préparer les données de session
                $prenom = explode(' ', $user->firstname);
                $username = $prenom[0] . ' ' . $user->lastname;
                // Récupération des menus
                $menus = Permission::select('menus.id', 'libelle', 'target', 'icone')
                    ->join('menus', 'menus.id', '=', 'permissions.menu_id')
                    ->where('profile_id', $user->profile_id)
                    ->where('status', 1)
                    ->where('action_id', 1)
                    ->orderBy('position')
                    ->get();
                if ($menus->isEmpty()) {
                    Log::warning("Aucun menu trouvé pour ce profil : " . $user->profile_id);
                    Auth::logout();
                    return response()->json([
                        'status' => 0,
                        'message' => "Aucun menu trouvé pour ce profil.",
                    ]);
                }
                $page = $menus->first()->target ?? '/';
                // Stocker des informations supplémentaires en session
                Session::put('username', $username);
                Session::put('profil', $user->profile->libelle ?? '');
                Session::put('menus', $menus);
                // Avatar
                if ($user->avatar != '')
                    $avatar = $user->avatar;
                else
                    $avatar = $user->gender == 'M' ? 'avatars/homme.jpg' : 'avatars/femme.jpg';
                Session::put('avatar', $avatar);
                // Log de connexion
                Myhelper::logs(
                    $username,
                    $user->profile->libelle ?? '',
                    $menus->first()->libelle,
                    'Connecter',
                    $avatar
                );
                return response()->json([
                    'status' => 1,
                    'data' => $page,
                ]);
            } else {
                // Mot de passe incorrect
                Log::warning("Tentative de connexion échouée pour : {$request->login}");
                return response()->json([
                    'status' => 0,
                    'message' => "Login ou mot de passe incorrect.",
                ]);
            }
        } catch (\Exception $e) {
            Log::warning("Echec de connexion : {$e->getMessage()}" . json_encode($request->all()));
			return response()->json([
				'status' => 0,
				'message' => "Service indisponible, veuillez réessayer plus tard !",
			]);
        }
    }
    // Déconnexion avec Laravel Auth
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Myhelper::logs(
                Session::get('username'), 
                Session::get('profil'), 
                Session::get('title'), 
                'Deconnecter',
                Session::get('avatar')
            );
            // Déconnexion avec Laravel Auth
            Auth::logout();
            // Invalidation de la session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect('/');
    }
    // Middleware pour vérifier les permissions
    public function checkPermission($permission)
    {
        $user = Auth::user();
        if (!$user) return false;
        
        return Permission::where('profile_id', $user->profile_id)
            ->whereHas('menu', function($query) use ($permission) {
                $query->where('libelle', $permission);
            })
            ->exists();
    }
}