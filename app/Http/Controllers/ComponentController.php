<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ComponentController extends BaseController
{
	/**
	 * Contoh method jika nanti ingin merender dashboard lewat controller ini.
	 * Saat ini hanya mengembalikan UI statis tanpa model / database.
	 */
	public function dashboard()
	{
		return view('dashboard');
	}

	/**
	 * Opsional: jika ingin mengakses header sebagai view terpisah.
	 */
	public function header()
	{
		return view('component.header');
	}

	/**
	 * Opsional: jika ingin mengakses sidebar sebagai view terpisah.
	 */
	public function sidebar()
	{
		return view('component.sidebar');
	}
}

