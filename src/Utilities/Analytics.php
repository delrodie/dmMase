<?php
	
	namespace App\Utilities;
	
	class Analytics
	{
		private $fichierDir;
		private $maseDir;
		
		public function __construct($fichierDir, $maseDir)
		{
			$this->fichierDir = $fichierDir;
			$this->maseDir = $maseDir;
		}
		
		public function fichierUA()
		{
			return $this->fichierDir;
		}
		
		public function maseGA4()
		{
			return $this->maseDir;
		}
	}
