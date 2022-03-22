<?php

namespace App\Controller;

use App\Utilities\Analytics;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashbordController extends AbstractController
{
	CONST GOOGLE_APPLICATION_CREDENTIALS="/home/delrodie/www/backoffice/dreammaker/mase/public/analytics/DM-Mase-bb67a4531e94.json";
	private $analytics;
	
	public function __construct(Analytics $analytics)
	{
		$this->analytics = $analytics;
	}
	
	/**
	 * @Route("/", name="app_dashbord")
	 */
    public function index(): Response
    {
		putenv('GOOGLE_APPLICATION_CREDENTIALS='.$this->analytics->maseGA4());
		$property_id = '307925646';
		$client = new BetaAnalyticsDataClient(); //dd($client);
		
		$response = $client->runReport([
			'property' => 'properties/'.$property_id,
			'dateRanges' => [
				new DateRange([
					'start_date' => '2021-01-01',
					'end_date' => 'today'
				])
			],
			'dimensions' => [new Dimension(
				['name' => 'city']
			)]
		]);
		
		//print 'Report result: '. PHP_EOL;
		
		foreach ($response->getRows() as $row){ //dd($row);
			$resultats =  $row->getDimensionValues()[0]->getValue()
				. ' '.$row->getMetricsValues()[0]->getValue() . PHP_EOL;
		}
		
		//dd($response->getRowCount());
        return $this->render('dashbord/index.html.twig', [
            'aujourdhui' => date('Y-m-d', time()),
        ]);
    }
	
}
