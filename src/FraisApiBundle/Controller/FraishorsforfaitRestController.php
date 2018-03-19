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


class FraishorsforfaitRestController extends FOSRestController
{
    
   /**
     * @ApiDoc(resource=true, description="Get les frais hors forfait pour un visiteur et un mois donné")
     */
    public function getLesfraishorsforfaitMoisAction($idVisiteur, $mois) {
        $pdo = PdoGsb::getPdoGsb();
        $lesFraisHorsForfaits = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        if(!$lesFraisHorsForfaits)
        {
            // code erreur 404
            throw new NotFoundHttpException('Frais hors forfait non disponibles [idVisiteur='.$idVisiteur.'] [mois='.$mois.']');
        }
        // retourne une réponse au format JSON et définit le content-type
        return new JsonResponse($lesFraisHorsForfaits);
    }
    
    /**
    * @ApiDoc(resource=true, description="Post crée un frais hors forfait")
    */
    public function postFraishorsforfaitAction(Request $request)
    {
        $pdo = PdoGsb::getPdoGsb();
        // récupérer les paramètres passés par POST dans l'objet $request
        $idVisiteur = $request->request->get('idVisiteur');
        $mois = $request->request->get('mois');
        $libelle = $request->request->get('libelle');
        $date = $request->request->get('date');
        $montant = $request->request->get('montant');
        // appeler la méthode de mise à jour de la classe pdogsb
        $pdo->creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant);
        // répondre au client
        $response = new Response();
        $statusCode = 201; // created, liste des codes ici http://www.codeshttp.com/
        $response->setStatusCode($statusCode); // created
        return $response;
    }
    
    /**
    * @ApiDoc(resource=true, description="Delete Supprime un frais hors forfait")
    */
    public function deleteFraishorsforfaitAction(Request $request)
    {
        $pdo = PdoGsb::getPdoGsb();
        // récupérer les paramètres passés par POST dans l'objet $request
        $idFrais = $request->request->get('idFrais');
        // appeler la méthode de mise à jour de la classe pdogsb
        $pdo->supprimerFraisHorsForfait($idFrais);
        // répondre au client
        $response = new Response();
        $statusCode = 200; // created, liste des codes ici http://www.codeshttp.com/
        $response->setStatusCode($statusCode); // updated
        return $response;
    }
}
