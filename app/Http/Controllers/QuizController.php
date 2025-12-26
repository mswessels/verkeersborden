<?php namespace App\Http\Controllers;

use Session, Auth, Input, Cookie, Request, Response, Redirect;
use App\Sign as Sign;
use App\Result as Result;

class QuizController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function getStart()
	{
		$meta = array(
			'meta_title' => 'Verkeersborden Oefenen',
			'meta_description' => 'Verkeersborden oefenen kan hier gratis. We hebben een quiz gemaakt bestaande uit 20 verkeersborden, hoeveel ken jij er? Kom hier verkeersborden oefenen.',
		);
		
		return view('start' , $meta);
	}
	
	public function doStart()
	{
		Session::forget('questions');
		
		return redirect('/quiz');	
	}
	
	public function getQuestion()
	{
		if( Session::has('questions') )
		{
			$questions = Session::get('questions');
			
			$keys =  array_keys( $questions['data'] );
			
			// Process answer, save to session
			if( Input::has('answer') ) {
				
				$answer = is_numeric( Input::get('answer') ) ? Input::get('answer') : 0;
			
				$key = end((array_keys($questions['data'])));
				
				$questions['data'][ $key  ] = $answer;
				
				Session::put('questions', $questions);
				
				// Clear URL
				return redirect('/quiz');
			}
			
			$questions['count'] = $questions['count'] + 1;
			
			if( $questions['count'] > 20)
			{
				return $this->redirectAfterFinish();
			}
			
		} else {
			
			$keys = array();
			$questions = array();
			$questions['count'] = 1;
			
		}
		
		// find a sign and find 3 signs in the same category
		$sign = Sign::orderBy(\DB::raw('RAND()'))->where('category_id','<>',10)->WhereNotIn('id',$keys)->take(1)->get()->toArray();
		$options = Sign::orderBy(\DB::raw('RAND()'))->where('id','<>',$sign[0]['id'])->where('category_id',$sign[0]['category_id'])->take(3)->get()->toArray();
		
		// in cat H (8) are only 2 signs, 
		// we'll check if there's 3 extra signs else we'll find more.
		$check = 3 - count($options);
		if(! empty( $check ) ) {
			
			$more_options = Sign::orderBy(\DB::raw('RAND()'))->where('id','<>',$sign[0]['id'])->where('category_id',4)->take( $check )->get()->toArray();
			// merge the 2 arrays 
			$options = array_merge($options,$more_options);
		
		}
		
		// merge the 2 arrays 
		$all_options = array_merge($options, $sign);
		
		$questions['data'][ $sign[0]['id'] ] = 0;
		
		// magic trick
		shuffle($all_options);
		
		Session::put('questions', $questions);
			
		$data = array(
			'image' => $sign[0]['image'],
			'options' => $all_options,
			'count' => $questions['count'],
			'meta_title' => 'Vraag '.$questions['count'].' / 20',
		);
		 
		return view('question', $data);
	}
	
	public function redirectAfterFinish()
	{
		if ( Auth::guest() ):
			return redirect('/auth/register');
		
		else:
			return redirect('/quiz/resultaat');
		
		endif;
	}
	
	public function getResults()
	{
		// only for logged in users
		if( Auth::guest() )
			return redirect('/verkeersborden-oefenen');
		
		// find results or save
		if( Session::has('questions') && Session::get('questions')['count'] == 20):
			$results = $this->saveResults();
		else:
			$results = Result::where('user_id', Auth::user()->id )->orderBy('created_at','DESC')->first();
		endif;
		
		// if session exsists save the thgingsssws

		if ( Auth::guest() )
			return redirect('/auth/register');
			
		$data = array(
			'results' => $results,
			'questions' => json_decode($results->results),
			'passed' => $results->right >= 15 ? true : false,
			'myresults' => Result::where('user_id', Auth::user()->id )->orderBy('created_at','DESC')->take(10)->get(),
			'otherresults' => Result::where('user_id','!=', Auth::user()->id)->with('user')->orderBy('created_at','DESC')->take(10)->get(),
			'meta_title' => $results->right >= 15 ? 'Geslaagd!' : 'Gezakt...',
		);
		
		return view('results',$data);
	}
	
	public function saveResults()
	{
		
		$questions = Session::get('questions');
		
		if($questions['count'] <> 20)
			return redirect('/verkeersborden-oefenen')->with('message','Je hebt de quiz niet afgemaakt.');
		
		$wrong = 0;
		$right = 0;
		
		foreach($questions['data'] as $key => $value)
		{
			if( $key == $value ) {
				$right++;
			} else {
				$wrong++;
			}
		}
		
		$results = new Result;
		
		$results->user_id = Auth::user()->id;
		$results->total   = $questions['count'];
		$results->right   = $right;
		$results->wrong	  = $wrong;
		$results->results = json_encode($questions['data']);
		
		$results->save();
		
		Session::forget('questions');
		
		return $results;
		
	}
}