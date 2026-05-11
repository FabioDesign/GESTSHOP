<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\{DB, Hash, Log, Validator, Auth};

class PasswordController extends Controller
{
    // Connexion
	public function index()
    {
        return view('forgotpass');
	}
	//Logic Forgot password
	public function store(request $request)
    {
		//Validator
		$validator = Validator::make($request->all(), [
        	'email' => 'required|email',
	    ], [
        	'email.*' => "0|Adresse e-mail non valide.",
	    ]);
        // Error field
        if ($validator->fails()) {
            Log::warning("Forgotpass::store - Validator : {$validator->errors()->first()} - {$request->email}");
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first(),
			]);
        }
		//Requete Read
		$user = User::join('profiles', 'profiles.id', '=', 'users.profile_id')
		->where([
			'users.status' => 1,
			'email' => $request->email
		])
		->first();
		if ($user) {
			$prenom = explode(' ', $user->firstname);
			$username = $prenom[0].' '.$user->lastname;
			//Requete Read
			$password = Myhelper::generate();
			$subject = "Nouveau Mot de passe";
			$gender = $user->gender == 'M' ? 'Cher':'Chère';
			$content = "{$gender} {$username},<br/>
			Votre nouveau mot de passe est : <strong>{$password}</strong><br/><br/>
			Cordialement<br/>
			L'équipe Ambassade de Guinée - CI<br>
			27 01 02 03 04<br>
			ambagui-ci@yopomail.com";
			Myhelper::sendMail($user->email, '', $subject, $content);
			//Update passwd_change_code
			$user->update([
				'password_at' => now(),
				'password' => Hash::make($password),
			]);
            // Avatar
            if ($user->avatar != '')
                $avatar = $user->avatar;
            else
                $avatar = $user->gender == 'M' ? 'avatars/homme.jpg' : 'avatars/femme.jpg';
			Myhelper::logs(
                $username,
                $user->libelle,
                'Mot de passe oublié',
                'Modifier',
                $avatar
            );
			return response()->json([
				'status' => true,
				'message' => "Mot de passe envoyé par mail avec succès.",
			]);
		} else {
            Log::warning("Forgotpass::store - Adresse e-mail non trouvée : {$request->email}");
			return response()->json([
				'status' => false,
				'message' => "Adresse e-mail non trouvée.",
			]);
        }
	}
    // Liste des utilisateurs
    public function edit()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
		//Title
		$title = 'Changement de mot de passe';
        //Menu
		$currentMenu = 'users';
		//Modal
		$addmodal = '<a href="/dashbord" class="btn btn-sm fw-bold btn-danger">Retour</a>
		<a href="#" class="btn btn-sm fw-bold btn-success submitForm">Modifier</a>';
        return view('pages.password', compact('title', 'currentMenu', 'addmodal'));
    }
    //Modification de Mot de passe
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect('x');
        }
        // Validator
        $validator = Validator::make($request->all(), [
            'oldpass' => ['required', 'min:8'],
            'password' => [
                'required',
                'confirmed',
                'different:oldpass',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers(),
            ],
        ], [
            'oldpass.required' => "L'ancien mot de passe est obligatoire.",
            'oldpass.min' => "L'ancien mot de passe doit contenir au moins 8 caractères.",
            'password.required' => "Le nouveau mot de passe est obligatoire.",
            'password.confirmed' => "Les mots de passe doivent correspondre.",
            'password.different' => "Le nouveau mot de passe doit être différent de l'ancien.",
            'password.min' => "Le mot de passe doit contenir au moins 8 caractères.",
            'password.mixed' => "Le mot de passe doit contenir au moins des Chiffres, Majuscules, etc.",
        ]);
		// Error field
		if ($validator->fails()) {
			Log::warning("Password::update - Validator : {$validator->errors()->first()} - " . json_encode($request->all()));
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first(),
			]);
		}
        // Vérification de l'ancien mot de passe
        if (!Hash::check($request->oldpass, Auth::user()->password)) {
            Log::warning("Password::update - Ancien mot de passe incorrect pour l'utilisateur ID : " . Auth::user()->id);
			return response()->json([
				'status' => false,
				'message' => "Ancien mot de passe incorrect.",
			]);
        }
        DB::beginTransaction(); // Démarrer une transaction
        try {
            // Mettre à jour du password
            User::findOrFail(Auth::user()->id)->update([
                'password' => Hash::make($request->password),
                'password_at' => now(),
            ]);
            DB::commit(); // Valider la transaction
            Myhelper::logs(
                Session::get('username'),
                Session::get('profil'),
                "Mot de passe: Nouveau",
                'Modifier',
                Session::get('avatar')
            );
            return response()->json([
                'status' => 2,
                'message' => "Mot de passe modifié avec succès.",
            ]);
        } catch(\Exception $e) {
            DB::rollBack(); // Annuler la transaction en cas d'erreur
            Log::warning("Password::update - Erreur : {$e->getMessage()}" . json_encode($request->all()));
            return response()->json([
                'status' => false,
                'message' => "Erreur lors de la modification.",
            ]);
        }
    }
}
