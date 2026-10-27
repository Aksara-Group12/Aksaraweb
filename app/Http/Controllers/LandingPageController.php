<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Subscribe;
use App\Models\User;

class LandingPageController extends Controller
{
	public function index()
	{
		$packages = Paket::where('is_active', true)
			->orderBy('harga')
			->get();

		$stats = [
			'users' => User::count(),
			'subscriptions' => Subscribe::count(),
			'packages' => $packages->count(),
		];

		return view('landingpage.index', compact('packages', 'stats'));
	}
}
