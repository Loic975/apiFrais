<?php

namespace FraisApiBundle\Controller;

require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PdoGsb;


class FichefraisRestController extends FOSRestController
{
    /**
     * @ApiDoc(resource=true, description="Get les mois disponibles pour un visiteur")
     */
    public function getLesmoisdisponiblesAction($idVisiteur)
    {
        $pdo = PdoGsb::getPdoGsb();
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        
        if(!$lesMois)
        {
            throw new NotFoundHttpException('Mois non disponibles [idVisiteur='.$idVisiteur.']');
        }

        return new JsonResponse($lesMois);
    }
    
    /**
     * @ApiDoc(resource=true, description="Get le nombre de justificatifs pour un visiteur et un mois donné")
     */
    public function getNbjustificatifsMoisAction($idVisiteur, $mois) {
        $pdo = PdoGsb::getPdoGsb();
        $lesJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $mois);
        if(!$lesJustificatifs)
        {
            throw new NotFoundHttpException('Justificatifs non disponibles [idVisiteur='.$idVisiteur.'] [mois='.$mois.']');
        }

        return new JsonResponse($lesJustificatifs);
    }

    /**
     * @ApiDoc(resource=true, description="Get vérifie s'il s'agit du premier frais du visiteur et du mois")
     */
    public function getEstpremierfraisMoisAction($idVisiteur, $mois) {
        $pdo = PdoGsb::getPdoGsb();
        $trouve = $pdo->estPremierFraisMois($idVisiteur, $mois);
        return new JsonResponse($trouve);
    }
    
    /**
     * @ApiDoc(resource=true, description="Get les informations de la fiche de frais du visiteur et du mois")
     */
    public function getLesinfosfichefraisMoisAction($idVisiteur, $mois) {
        $pdo = PdoGsb::getPdoGsb();
        $lesInfosFichesFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
        if(!$lesInfosFichesFrais)
        {
            throw new NotFoundHttpException('Informations de la fiche de frais non disponibles [idVisiteur='.$idVisiteur.'] [mois='.$mois.']');
        }

        return new JsonResponse($lesInfosFichesFrais);
    }
    
    /**
     * @ApiDoc(resource=true, description="Get la date de la fiche de frais du visiteur et du mois")
     */
    public function getDatefichefraisAction($idVisiteur) {
        $pdo = PdoGsb::getPdoGsb();
        $dateFichesFrais = $pdo->getDateFicheFrais($idVisiteur);
        if(!$dateFichesFrais)
        {
            throw new NotFoundHttpException('Date de la fiche de frais non disponibles [idVisiteur='.$idVisiteur.']');
        }

        return new JsonResponse($dateFichesFrais);
    }
    
    /**
    * @ApiDoc(resource=true, description="Post crée une fiche de frais")
    */
    public function postFichefraisAction(Request $request)
    {
        $pdo = PdoGsb::getPdoGsb();
        // récupérer les paramètres passés par POST dans l'objet $request
        $idVisiteur = $request->request->get('idVisiteur');
        $mois = $request->request->get('mois');
        // appeler la méthode de mise à jour de la classe pdogsb
        $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
        // répondre au client
        $response = new Response();
        $statusCode = 201; // created, liste des codes ici http://www.codeshttp.com/
        $response->setStatusCode($statusCode); // created
        return $response;
    }
}
