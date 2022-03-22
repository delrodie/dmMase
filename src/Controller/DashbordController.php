<?php

namespace App\Controller;

use App\Utilities\Analytics;
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
	 */
    public function index(): Response
    {
        return $this->render('dashbord/index.html.twig', [
            'aujourdhui' => date('Y-m-d', time()),
        ]);
    }
	
}
