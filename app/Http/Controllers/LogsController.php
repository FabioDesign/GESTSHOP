<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth};

class LogsController extends Controller
{
    //Liste de Pistes d'audit
	public function index() {
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = "Pistes d'audit";
		// Menu
		$currentMenu = "logs";
		// Modal
		$addmodal = '';
		return view('pages.logs', compact('title', 'currentMenu', 'addmodal'));
	}
    //Liste de Pistes d'audit
	public function getLogs() {
        if (!Auth::check()) {
            return 'x';
        }
		try {
			//Requete Read
			$query = DB::select('CALL sp_get_logs()');
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
