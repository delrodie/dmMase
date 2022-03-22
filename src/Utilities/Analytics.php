<?php
	
	namespace App\Utilities;
	
	class Analytics
	{
		private $fichierDir;
		
		public function __construct($fichierDir)
		{
			$this->fichierDir = $fichierDir;
		}
		
		public function fichier()
		{
			return $this->fichierDir;
		}
	}
