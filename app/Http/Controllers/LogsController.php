<?php

namespace App\Http\Controllers;

use App\Models\Logs;
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
		//Requete Read-
		$query = DB::select('CALL sp_get_logs()');
		return response()->json([
			'status' => true,
			'data' => $query,
		]);
	}
}
