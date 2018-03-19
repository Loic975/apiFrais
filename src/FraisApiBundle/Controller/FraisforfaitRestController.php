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


class FraisforfaitRestController extends FOSRestController
{
    /**
     * @ApiDoc(resource=true, description="Get les frais forfait pour un visiteur et un mois donné")
     */
   public function getLesfraisforfaitMoisAction($idVisiteur, $mois) {
        $pdo = PdoGsb::getPdoGsb();
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        if(!$lesFraisForfait)
        {
            // code erreur 404
            throw new NotFoundHttpException('Frais forfait non disponibles [idVisiteur='.$idVisiteur.'] [mois='.$mois.']');
        }
        // retourne une réponse au format JSON et définit le content-type
        return new JsonResponse($lesFraisForfait);
    }
    
    /**
    * @ApiDoc(resource=true, description="Put Met à jour un frais forfait")
    */
    public function putMajfraisforfaitAction(Request $request)
    {
        $pdo = PdoGsb::getPdoGsb();
        // récupérer les paramètres passés par POST dans l'objet $request
        $idVisiteur = $request->request->get('idVisiteur');
        $mois = $request->request->get('mois');
        $lesFrais = $request->request->get('lesFrais');
        // appeler la méthode de mise à jour de la classe pdogsb
        $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        // répondre au client
        $response = new Response();
        $statusCode = 200; // created, liste des codes ici http://www.codeshttp.com/
        $response->setStatusCode($statusCode); // updated
        return $response;
    }
}
