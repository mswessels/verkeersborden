<?php namespace App\Http\Controllers;

class HomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{		
		$meta = array(
			'meta_title' => 'Bekijk alle verkeersborden en doe een gratis Examen Test!',
			'meta_description' => 'Verkeersborden zijn belangrijk om te kennen, op deze website kun je gratis verkeersborden oefenen en je kennis testen. Handig voor je CRB Examen rijbewijs.',
		);
		
		return view('home' ,$meta);
	}
	
	public function alleBorden()
	{
		$meta = array(
			'meta_title' => 'Alle Verkeersborden',
			'meta_description' => 'Alle verkeersborden van Nederland in een handig overzicht. Bij elkaar hebben we in ons land bijna 200 verschillende borden, bekijk hier alle verkeersborden.',
		);
		
		return view('alleborden' ,$meta);
	}
	
	public function theorieExamen()
	{
		$meta = array(
			'meta_title' => 'Gratis CBR Theorie Examen Oefenen',
			'meta_description' => 'Test je kennis van de verkeersborden, doe een gratis test. Wij hebben alle verkeersborden die in Nederland te vinden zijn in een gratis verkeersborden oefening.',
		);
		
		return view('theorie' ,$meta);
	}
	
	public function links()
	{
		$meta = array(
			'meta_title' => 'Linkpartners',
			'meta_description' => 'Links van onze partners vindt je op een handige pagina. Opzoek naar een rijschool in de regio? Bekijk partners bij jouw in de buurt. Link ruilen?',
		);
		
		return view('links' ,$meta);
	}

}
