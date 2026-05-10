<?php

namespace App\Http\Controllers;

use Session;
use Myhelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth};

class DashboardController extends Controller
{
	//Tableau de bord
  	public function index(request $request)
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
		return view('pages.dashboard', compact('title', 'currentMenu', 'addmodal'));
  	}
}
