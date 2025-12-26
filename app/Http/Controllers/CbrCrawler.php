<?php namespace App\Http\Controllers;

use Mail;

set_time_limit ( 0 );



class CbrCrawler extends Controller {	



	/**

	 * Show the application dashboard to the user.

	 *

	 * @return Response

	 */

	public function start()

	{	

		$leeg = 0;

		for($i = 15000; $i < 25000; $i++)

		{

			$url = "http://www.rijschoolgegevens.nl/data.asp?option=rijschool&pageid=2&fromsearch=0&rijschoolid=".$i."&automaat=0";

			//$url = "http://www.rijschoolgegevens.nl/data.asp?option=rijschool&pageid=2&fromsearch=0&examencategorie=B&rijschoolid=4696&automaat=0";

			

			$data = $this->getUrlContent($url);

			if($data && !empty($data))

			{

				$pattern = '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';

				preg_match_all($pattern, $data, $matches);

				

				if(count($matches))

				{

					if(isset($matches[0]) && count($matches[0]))

					{

						foreach ($matches[0] as $key => $email) {

							$rijschool = \App\Rijschool::firstOrCreate(['email' => $email]);

						}

					}

				}

			} else {

				

				$leeg++; 

				

			}

		}

		

		echo "Klaar";

		echo "<br/>".$leeg." Leeg";

	}

	

	private function getUrlContent($url){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		$data = curl_exec($ch);

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return ($httpcode>=200 && $httpcode<300) ? $data : false;

	}

	

	public function start_mailing()

	{

		foreach(\App\Rijschool::orderBy('id','asc')->skip(3569)->take(500)->get() as $rijschool)

		{

			Mail::send('emails.rijschool', $rijschool->toArray(), function ($m) use ( $rijschool ) 

			{

				$m->to( $rijschool->email )

				//$m->to( 'm.wessels@outlook.com' )

				  ->from('info@deverkeersborden.nl','Johan van DeVerkeersborden.nl')

				  ->subject('DeVerkeersborden.nl is vernieuwd!');

			});

		}

	}

}
