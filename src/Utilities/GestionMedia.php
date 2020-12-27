<?php


namespace App\Utilities;


use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GestionMedia
{
    private $mediaSlide;
    private $mediaMission;
    private $mediaAgenda;
    private $mediaRex;

    public function __construct($slideDirectory, $missionDirectory, $agendaDirectory, $rexDirectory)
    {
        $this->mediaSlide = $slideDirectory;
        $this->mediaMission = $missionDirectory;
        $this->mediaAgenda = $agendaDirectory;
        $this->mediaRex = $rexDirectory;
    }

    /**
     * Enregistrement du fichier dans le repertoire approprié
     *
     * @param UploadedFile $file
     * @param null $media
     * @return string
     */
    public function upload(UploadedFile $file, $media = null)
    {
        // Initialisation du slug
        $slugify = new Slugify();

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slugify($originalFileName);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Deplacement du fichier dans le repertoire dedié
        try {
            if ($media === 'slide') $file->move($this->mediaSlide, $newFilename);
            elseif ($media === 'mission') $file->move($this->mediaMission, $newFilename);
            elseif ($media === 'agenda') $file->move($this->mediaAgenda, $newFilename);
            elseif ($media === 'rex') $file->move($this->mediaRex, $newFilename);
            else $file->move($this->mediaSlide, $newFilename);
        }catch (FileException $e){

        }

        return $newFilename;
    }

    /**
     * Suppression de l'ancien media sur le server
     *
     * @param $ancienMedia
     * @param null $media
     * @return bool
     */
    public function removeUpload($ancienMedia, $media = null)
    {
        if ($media === 'slide') unlink($this->mediaSlide.'/'.$ancienMedia);
        if ($media === 'mission') unlink($this->mediaMission.'/'.$ancienMedia);
        if ($media === 'agenda') unlink($this->mediaAgenda.'/'.$ancienMedia);
        if ($media === 'rex') unlink($this->mediaRex.'/'.$ancienMedia);
        else return false;

        return true;
    }
}