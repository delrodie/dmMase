<?php

namespace App\Controller;

use App\Utilities\Analytics;
use Google_Client;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_ReportRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/statistiques")
 */
class AnalyticsController extends AbstractController
{
	
	private $analytics;
	
	public function __construct(Analytics $analytics)
	{
		$this->analytics = $analytics;
	}
	
    /**
     * @Route("/", name="backend_analytics_helloanalytics")
     */
    public function index(): Response
    {
		$analytics = $this->initializeAnalytics(); //dd($analytics);
	    $response = $this->getReport($analytics);
	    //$this->printResults($response);
        return $this->render('analytics/index.html.twig', [
            'controller_name' => $this->printResults($response),
        ]);
    }
	
	
	/**
	 * @return Google_Service_AnalyticsReporting
	 * @throws \Google\Exception
	 */
	function initializeAnalytics(): Google_Service_AnalyticsReporting
	{
		
		// Use the developers console and download your service account
		// credentials in JSON format. Place them in this directory or
		// change the key file location if necessary.
		//$KEY_FILE_LOCATION = __DIR__ . '/service-account-credentials.json';
		$KEY_FILE_LOCATION = $this->analytics->fichierUA();
		
		// Create and configure a new client object.
		$client = new Google_Client();
		$client->setApplicationName("Hello Analytics Reporting");
		$client->setAuthConfig($KEY_FILE_LOCATION);
		$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
		
		return new Google_Service_AnalyticsReporting($client);
	}
	
	
	/**
	 * @param $analytics
	 * @return mixed
	 */
	function getReport($analytics) {
		
		// Replace with your view ID, for example XXXX.
		//$VIEW_ID = "UA-223589877-1";
		$VIEW_ID = "263129732";
		
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
	
	
	/**
	 * @param $reports
	 * @return void
	 */
	function printResults($reports) { //dd($reports);
		for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
			$report = $reports[ $reportIndex ];
			$header = $report->getColumnHeader();
			$dimensionHeaders = $header->getDimensions(); //dd(count($dimensionHeaders));
			$metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
			$rows = $report->getData()->getRows();
			
			for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
				$row = $rows[ $rowIndex ];
				$dimensions = $row->getDimensions();
				$metrics = $row->getMetrics();
				for ($i = 0; $i < ($dimensionHeaders === null ? 0 : count($dimensionHeaders)) && $i < count($dimensions); $i++) {
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
