<?php

namespace Prueba\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
	/*public function inicioAction()
	{
		return $this->render('PruebaMainBundle:Default:index.html.twig');
	}*/
	
	public function contactoAction()
	{
		return $this->render('PruebaMainBundle:Default:contacto.html.twig');
	}
}
