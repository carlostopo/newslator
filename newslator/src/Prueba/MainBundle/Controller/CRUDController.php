<?php

namespace Prueba\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Prueba\MainBundle\Entity\Feed;
use Prueba\MainBundle\Form\FeedType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;

class CRUDController extends Controller
{  
    /* public function muestraNoticiasHoyAction()
    {  	
       	$date = new \DateTime();
    	$fecha = $date->format('Y-m-d');    	
    	
    	$outputs = $this->getDoctrine()
    	->getManager()
    	->createQuery('SELECT c FROM PruebaMainBundle:Feed c WHERE c.date = :fecha')
    	->setParameters(array('fecha'=>$fecha))    	 
    	->getResult();
    	 
    	return $this->render("PruebaMainBundle:Default:index.html.twig", array('outputs'=>$outputs));
    } */
    
    /* public function leerNoticiaHoyAction($id)
    {
    	$em =$this->getDoctrine()->getManager();
    	$noticia =$em->getRepository('PruebaMainBundle:Feed')->findOneById($id);
    	if (!$noticia) {
    		throw $this->createNotFoundException(
    				'No se ha encontrado la noticia para el identificador '.$id
    				);
    	}
    
    	return $this->render("PruebaMainBundle:Default:leer.html.twig", array('noticia'=>$noticia));
    } */
    
    public function listaNoticiasAction()
    {
    	$em =$this->getDoctrine()->getManager();
    	$outputs =$em->getRepository('PruebaMainBundle:Feed')->findAll();
    	if (!$outputs) {
    		throw $this->createNotFoundException(
    				'No se ha encontrado ninguna noticia.'
    				);
    	}
    	return $this->render("PruebaMainBundle:Default:lista.html.twig", array('outputs'=>$outputs));
    }
    
    public function borrarNoticiaAction($id){
    	$em =$this->getDoctrine()->getManager();
    	$noticia =$em->getRepository('PruebaMainBundle:Feed')->findOneById($id);
    	if (!$noticia) {
    		throw $this->createNotFoundException(
    				'No se ha encontrado la noticia para el identificador '.$id
    				);
    	}
    	$em->remove($noticia);
    	$em->flush();
    	 
    	return $this->render("PruebaMainBundle:Default:borrada.html.twig", array('noticia'=>$noticia,'id'=>$id));
    }
    
    public function crearNoticiaAction(Request $request){
    	$em =$this->getDoctrine()->getManager();
    	$noticia = new FeedType();
    	$form = $this->createForm($noticia);
    	$form->handleRequest($request);
    
    	if($form->isSubmitted() && $form->isValid()){
    		$noticia=$form->getData();
    		$em->persist($noticia);
    		$em -> flush();
    		 
    		return $this->redirect($this->generateUrl('newslator_lista'));
    	}
    	return $this->render('PruebaMainBundle:Default:crear.html.twig', array('form'=> $form->createView()));
    }
    
    public function editarNoticiaAction(Request $request, $id){
    	$em =$this->getDoctrine()->getManager();
    	$noticia =$em->getRepository('PruebaMainBundle:Feed')->findOneById($id);
    	if (!$noticia) {
    		throw $this->createNotFoundException(
    				'No se ha encontrado la noticia para el identificador '.$id
    				);
    	}
    	 
    	$form = $this->createForm(new FeedType(),$noticia);
    	$form->handleRequest($request);
    	 
    	if($form->isSubmitted() && $form->isValid()){
    		$noticia->setTitle($form->get('title')->getData());
    		$noticia->setBody($form->get('body')->getData());
    		$noticia->setImage($form->get('image')->getData());
    		$noticia->setSource($form->get('source')->getData());
    		$noticia->setPublisher($form->get('publisher')->getData());
    		$noticia->setDate($form->get('date')->getData());
    		 
    		$em->persist($noticia);
    		$em -> flush();
    		 
    		return $this->redirect($this->generateUrl('newslator_lista'));
    	}
    	return $this->render('PruebaMainBundle:Default:editar.html.twig', array('form'=> $form->createView(),'id'=>$id));
    }
    
}