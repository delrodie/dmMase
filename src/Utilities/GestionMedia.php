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
    private $mediaActualite;
    private $mediaPresse;
    private $mediaDocument;

    public function __construct($slideDirectory, $missionDirectory, $agendaDirectory, $rexDirectory, $actualiteDirectory, $presseDirectory, $documentDirectory)
    {
        $this->mediaSlide = $slideDirectory;
        $this->mediaMission = $missionDirectory;
        $this->mediaAgenda = $agendaDirectory;
        $this->mediaRex = $rexDirectory;
        $this->mediaActualite = $actualiteDirectory;
        $this->mediaPresse = $presseDirectory;
        $this->mediaDocument = $documentDirectory;
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
            elseif ($media === 'actualite') $file->move($this->mediaActualite, $newFilename);
            elseif ($media === 'presse') $file->move($this->mediaPresse, $newFilename);
            elseif ($media === 'document') $file->move($this->mediaDocument, $newFilename);
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
        elseif ($media === 'mission') unlink($this->mediaMission.'/'.$ancienMedia);
        elseif ($media === 'agenda') unlink($this->mediaAgenda.'/'.$ancienMedia);
        elseif ($media === 'rex') unlink($this->mediaRex.'/'.$ancienMedia);
        elseif ($media === 'actualite') unlink($this->mediaActualite.'/'.$ancienMedia);
        elseif ($media === 'presse') unlink($this->mediaPresse.'/'.$ancienMedia);
        elseif ($media === 'document') unlink($this->mediaDocument.'/'.$ancienMedia);
        else return false;

        return true;
    }
}