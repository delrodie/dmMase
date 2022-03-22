<?php

namespace App\Controller;

use App\Utilities\Analytics;
use Google_Client;
use Google_Service_Analytics;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_ReportRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashbordController extends AbstractController
{
	
	private $analytics;
	
	public function __construct(Analytics $analytics)
	{
		$this->analytics = $analytics;
	}
	
	/**
	 * @Route("/", name="app_dashbord")
	 * @throws \Google\Exception
	 */
    public function index(): Response
    {
		//dd($this->analytics->fichier());
		$client = new \Google_Client();
		$client->setAuthConfig($this->analytics->fichier());
		$client->addScope(\Google_Service_Analytics::ANALYTICS_READONLY); //dd($_SESSION['access_token']);
		
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']){
			$client->setAccessToken($_SESSION['access_token']);
			$analytics = new \Google_Service_AnalyticsReporting($client);
			$response = $this->getReport($analytics);
			
			$this->printResults($response);
		}else{
			$redirect_uri = 'http://'. $_SERVER['HTTP_HOST']. '/backend/oauth2callback'; //dd($redirect_uri);
			header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
			
			return $this->redirect($redirect_uri);
		}
		
        return $this->render('dashbord/index.html.twig', [
            'aujourdhui' => date('Y-m-d', time()),
        ]);
    }
	
	/**
	 * @Route("/oauth2callback", name="backend_analytics", methods={"GET"})
	 * @throws \Google\Exception
	 */
	public function oauth2callback()
	{
		//session_start();

		// Create the client object and set the authorization configuration
		// from the client_secrets.json you downloaded from the Developers Console.
				$client = new Google_Client();
				$client->setAuthConfig($this->analytics->fichier());
				$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/backend/oauth2callback');
				$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
		
		// Handle authorization flow from the server.
				if (! isset($_GET['code'])) {
					$auth_url = $client->createAuthUrl();
					header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
					
					return $this->redirect($auth_url);
				} else {
					$client->authenticate($_GET['code']);
					$_SESSION['access_token'] = $client->getAccessToken();
					$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
					header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
					
					return $this->redirect($redirect_uri);
				}
	}
	
	/**
	 * @param $analytics
	 * @return mixed
	 */	
	function getReport($analytics)
	{
		$VIEW_ID = '306928198';
		
		// Create the DateRange object.
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate("7daysAgo");
		$dateRange->setEndDate("today");
		
		// Create the Metrics object.
		$sessions = new Google_Service_AnalyticsReporting_Metric();
		$sessions->setExpression("ga:sessions");
		$sessions->setAlias("sessions");
		
		// Create the ReportRequest object.
		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId($VIEW_ID);
		$request->setDateRanges($dateRange);
		$request->setMetrics(array($sessions));
		
		$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests( array( $request) );
		return $analytics->reports->batchGet( $body );
	}
	
	function printResults($reports) {
		for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
			$report = $reports[ $reportIndex ];
			$header = $report->getColumnHeader();
			$dimensionHeaders = $header->getDimensions();
			$metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
			$rows = $report->getData()->getRows();
			
			for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
				$row = $rows[ $rowIndex ];
				$dimensions = $row->getDimensions();
				$metrics = $row->getMetrics();
				for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
					print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
				}
				
				for ($j = 0; $j < count($metrics); $j++) {
					$values = $metrics[$j]->getValues();
					for ($k = 0; $k < count($values); $k++) {
						$entry = $metricHeaders[$k];
						print($entry->getName() . ": " . $values[$k] . "\n");
					}
				}
			}
		}
	}
}
