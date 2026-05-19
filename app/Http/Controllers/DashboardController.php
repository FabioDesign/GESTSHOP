<?php

namespace App\Http\Controllers;

use PDO;
use Session;
use Myhelper;
use App\Models\AnnualStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth};

class DashboardController extends Controller
{
	//Tableau de bord
  	public function index()
	{
        if (!Auth::check()) {
            return redirect('/');
        }
		// Title
		$title = 'Tableau de bord';
		// Menu
		$currentMenu = 'dashboard';
		// Modal
		$addmodal = '';
		//Requete Read
		$query = AnnualStat::select('years')
		->orderByDesc('years')
		->get();
		return view('pages.dashboard', compact('title', 'currentMenu', 'addmodal', 'query'));
  	}
    //Liste de Statistique
	public function statistics(request $request) {
        if (!Auth::check()) {
            return 'x';
        }
		try {
			//Requete Read
			$pdo = DB::getPdo();
			$stmt = $pdo->prepare(
				'CALL sp_get_stats_data(?, ?, ?, ?)',
				[
					$request->years,
					$request->months,
					$request->days,
					$request->type
				]
			);
			$stmt->execute();

			// Premier résultat → annual_stats
			$annual_stats = $stmt->fetchAll(PDO::FETCH_OBJ);

			// Passer au second résultat
			$stmt->nextRowset();

			// Second résultat → logs
			$logs = $stmt->fetchAll(PDO::FETCH_OBJ);
			return response()->json([
				'status' => true,
				'annual_stats' => $annual_stats,
    			'data'         => $logs,
			]);
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
