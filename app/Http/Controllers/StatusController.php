<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Auth, Log};
use App\Models\{Cash, Product, Profile, User};

class StatusController extends Controller
{
    public function update($type, $uid)
    {
        if (!Auth::check()) {
            return 'x';
        }
        try {
            // Mapping dynamique
            $models = [
                'cashs' => [
                    'model' => Cash::class,
                    'label' => 'Caisse'
                ],
                'category' => [
                    'model' => Product::class,
                    'label' => 'Categorie'
                ],
                'product' => [
                    'model' => Product::class,
                    'label' => 'Produit'
                ],
                'profiles' => [
                    'model' => Profile::class,
                    'label' => 'Profil'
                ],
                'users' => [
                    'model' => User::class,
                    'label' => 'Utilisateur'
                ],
            ];
            // Vérifier si le type existe
            if (!isset($models[$type])) {
                return response()->json([
                    'status' => false,
                    'message' => "Type invalide.",
                ]);
            }
            $modelClass = $models[$type]['model'];
            $label = $models[$type]['label'];
            // Récupération de l'enregistrement
            $item = $modelClass::where('uid', $uid)->first();
            if (!$item) {
                Log::warning("StatusController - Aucun {$label} trouvé pour UID : {$uid}");
                return response()->json([
                    'status' => false,
                    'message' => "{$label} non trouvé.",
                ]);
            }
            // Cas spécifique : Profil admin
            if ($type === 'profiles' && $item->id == 1) {
                Log::warning("StatusController - Tentative désactivation admin UID : {$uid}");
                return response()->json([
                    'status' => false,
                    'message' => "Le profil administrateur ne peut pas être désactivé.",
                ]);
            }
            // Changement de statut
            if ($type === 'cashs') {
                switch ($item->status) {
                    case 0 :
                        $actionIds = Myhelper::actions(Auth::user()->profile_id, 2);
                        if (in_array(6, $actionIds)) {
                            $action = 'Transmise';
                            $set = [
                                'status' => 1,
                                'transmitted_at' => now(),
                                'transmitted_by' => Auth::user()->id,
                            ];
                        } else {
                            $action = 'Validée';
                            $set = [
                                'status' => 2,
                                'validated_at' => now(),
                                'validated_by' => Auth::user()->id,
                            ];
                        }
                        break;
                    case 1 :
                        $action = 'Validée';
                        $set = [
                            'status' => 2,
                            'validated_at' => now(),
                            'validated_by' => Auth::user()->id,
                        ];
                        break;
                    default :
                        $newStatus = 0;
                        $action = 'Désactivé';
                }
            } else {
                $newStatus = $item->status == 1 ? 0 : 1;
                $action = $newStatus == 1 ? 'Activé' : 'Désactivé';
                $set = [
                    'status' => $newStatus,
                ];
            }
            $item->update($set);
            $libelle = $item->libelle ?? ($item->lastname . ' ' . $item->firstname);
            // Log
            Myhelper::logs(
                Session::get('username'),
                Session::get('profil'),
                "{$label}: {$libelle} $action",
				'Modifier',
                Session::get('avatar')
            );
			return response()->json([
				'status' => true,
				'message' => "{$label} " . Str::lower($action) . " avec succès.",
			]);
        } catch (\Exception $e) {
            Log::warning("StatusController : {$e->getMessage()}");
            return response()->json([
                'status' => false,
                'message' => "Erreur lors du changement de statut.",
            ]);
        }
    }
}