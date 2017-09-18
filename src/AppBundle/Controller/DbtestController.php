<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DbtestController extends Controller
{

    public function indexAction(Request $request)
    {
    //     $records = $this->getDoctrine()
    //    ->getRepository('AppBundle:Stories')
    //    ->findAll();

        $em = $this->getDoctrine()->getEntityManager();

        $stories = $em->getRepository('AppBundle:Story')
            ->findAll();

        /*

        foreach ($stories as $story) {
            $photos = $story->getPhotos();
            //var_dump($story);
            //echo $photos[0]->getFile();
           // echo "<br><br>";
        }


        */




return $this->render('default/db.html.twig', [
           'stories'=>$stories,
       ]);



   // ... do something, like pass the $product object into a template
    }
}
