<?php

namespace Prueba\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Prueba\MainBundle\Entity\Feed;
use Symfony\Component\Validator\Constraints\DateTime;
//use Symfony\Component\Validator\Constraints\True;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\BrowserKit\Request;
use SimpleXMLElement;

class ScrapingController extends Controller
{  
	//guarda y muestra la noticia más destacada de cada periódico
	public function leerNoticiasAhoraAction(){
		$elmundo = $this->elMundoRSSAction();
		$this->guardaNoticiaAction($elmundo);
		
		$elpais = $this->elPaisRSSAction();
		$this->guardaNoticiaAction($elpais);
		
		$elperiodico = $this->elPeriodicoRSSAction();
		$this->guardaNoticiaAction($elperiodico);
		
		$larazon = $this->laRazonRSSAction();
		$this->guardaNoticiaAction($larazon);
		
		$elconfidencial = $this->elConfidencialRSSAction();
		$this->guardaNoticiaAction($elconfidencial);
		
		$noticias = array(
				'elmundo'=>$elmundo,
				'elpais'=>$elpais,
				'elperiodico'=>$elperiodico,
				'larazon'=>$larazon,
				'elconfidencial'=>$elconfidencial
		);
		
		return $this->render("PruebaMainBundle:Default:index.html.twig", array('noticias'=>$noticias));
		
	}
	
	public function guardaNoticiaAction($datos){
		$em = $this->getDoctrine()->getManager();
		$noticia_bbdd = $em->getRepository('PruebaMainBundle:Feed')->findOneBy(array('source'=>$datos['source']));
		if(!$noticia_bbdd){
			$noticia = new Feed();
			$noticia->setTitle($datos['title']);
			$noticia->setBody($datos['body']);
			$noticia->setImage($datos['image']);
			$noticia->setSource($datos['source']);
			$noticia->setPublisher($datos['publisher']);
			
			$date = new \DateTime();//Object Date
			$date = $date->format('Y-m-d'); //String
			$date = new \DateTime($date); //Date			
			$noticia->setDate($date);
			
			$em->persist($noticia);
			$em->flush();
		}
	}
	
	public function elMundoRSSAction(){
		$map_url = 'http://estaticos.elmundo.es/elmundo/rss/portada.xml';
		$response_xml_data = file_get_contents($map_url);
		//$data = simplexml_load_string($response_xml_data);
		$noticias = new SimpleXMLElement($response_xml_data);
		$item = $noticias->channel->item;
		
		$title = $item->title;
		$title = strval($title);
		
		$source = $item->link;
		$source = strval($source);
		
		//body
		$description = $item->description;
		$body = strip_tags($description);
		$body = preg_replace("/&#?[a-z0-9]{2,8};/i","",$body);
		$body = str_replace('Leer', '', $body);
		
		//imagen
		preg_match_all('/src="(.*?)"/',$description,$imagenes);
		if(count($imagenes)>1){
			$errors = array_filter($imagenes);
			if (!empty($errors)) {
				$image = $imagenes[1][0];
			}else {
				$image = 'No se ha podido obtener.';
			}
		} else {
			$image = 'No se ha podido obtener.';
		}
		
		//imagen especial de el mundo
		$areemplazar = '/http(.*?)\.xml/';
		preg_match_all($areemplazar,$image,$imagenes);
		if(count($imagenes)>0){
			$errors = array_filter($imagenes);
			if (!empty($errors)) {
				$image = 'No se ha podido obtener.';
			}
		}	
		
		$date = $item->pubDate;
		$date = strval($date);
		
		$publisher = 'El Mundo';
		
		$elmundo = array(
				'title'=>$title,
				'body'=>$body,
				'image'=>$image,
				'source'=>$source,
				'pubDate'=>$date,
				'publisher' => $publisher
				
		);
		//var_dump($elmundo);
		//print_r($items->asXML());
		return $elmundo;
	}
	
	public function elPaisRSSAction(){
		$map_url = 'http://ep00.epimg.net/rss/tags/ultimas_noticias.xml';
		$response_xml_data = file_get_contents($map_url);
		//$data = simplexml_load_string($response_xml_data);
		$noticias = new SimpleXMLElement($response_xml_data);
		$item = $noticias->channel->item;
		
		$publisher = 'El País';
		
		$title = $item->title;
		$title = strval($title);
	
		$source = $item->link;
		$source = strval($source);
		$source = str_replace('#?ref=rss&format=simple&link=link', '', $source);
	
		$description = $item->description;
		$body = strval($description);
		$body = strip_tags($body);
		$body = preg_replace("/&#?[a-z0-9]{2,8};/i","",$body);
		
		preg_match_all('/src="(.*?)"/',$description,$imagenes);
		if(count($imagenes)>1){
			$errors = array_filter($imagenes);
			if (!empty($errors)) {
				$image = $imagenes[1][0];
			}else {
				$image = 'No se ha podido obtener.';
			}
		} else {
			$image = 'No se ha podido obtener.';
		}
	
		$date = $item->pubDate;
		$date = strval($date);
	
		$elpais = array(
				'title'=>$title,
				'body'=>$body,
				'image'=>$image,
				'source'=>$source,
				'pubDate'=>$date,
				'publisher' => $publisher
				
		);
		//var_dump($elpais);
		//print_r($items->asXML());
		return $elpais;
	}
	
	public function elPeriodicoRSSAction(){
		$map_url = 'http://www.elperiodico.com/es/rss/rss_portada.xml';
		$response_xml_data = file_get_contents($map_url);
		//$data = simplexml_load_string($response_xml_data);
		$noticias = new SimpleXMLElement($response_xml_data);
		$item = $noticias->channel->item;
	
		$publisher = 'El Periódico';
		
		$title = $item->title;
		$title = strval($title);
	
		$source = $item->link;
		$source = strval($source);
		$source = str_replace('?utm_source=rss-noticias&utm_medium=feed&utm_campaign=portada', '', $source);
	
		$description = $item->description;
		$body = strip_tags($description);
		$body = preg_replace("/&#?[a-z0-9]{2,8};/i","",$body);
		
		//image
		preg_match_all('/src="(.*?)"/',$description,$imagenes);
		if(count($imagenes)>1){
			$errors = array_filter($imagenes);
			if (!empty($errors)) {
				$image = $imagenes[1][0];
			}else {
				$image = 'No se ha podido obtener.';
			}
		} else {
			$image = 'No se ha podido obtener.';
		}
	
		$date = $item->pubDate;
		$date = strval($date);
	
		$elperiodico = array(
				'title'=>$title,
				'body'=>$body,
				'image'=>$image,
				'source'=>$source,
				'pubDate'=>$date,
				'publisher' => $publisher
	
		);
		//var_dump($elperiodico);
		//print_r($items->asXML());
		return $elperiodico;
	}
	
	public function laRazonRSSAction(){
		$map_url = 'http://www.larazon.es/rss/portada.xml';
		
		$response_xml_data = file_get_contents($map_url);
		//$data = simplexml_load_string($response_xml_data);
		$noticias = new SimpleXMLElement($response_xml_data);
		$item = $noticias->channel->item;
	
		$publisher = 'La Razón';
		//title
		$title = $item->title;
		$title = strval($title);
	
		//source
		$source = $item->link;
		$source = strval($source);
	
		$description = $item->description;
		$body = strip_tags($description);
		$body = preg_replace("/&#?[a-z0-9]{2,8};/i","",$body);
		
		//image
		preg_match_all('/src="(.*?)"/',$description,$imagenes);
		if(count($imagenes)>1){
			$errors = array_filter($imagenes);
			if (!empty($errors)) {
				$image = $imagenes[1][0];
			}else {
				$image = 'No se ha podido obtener.';
			}
		} else {
			$image = 'No se ha podido obtener.';
		}
			
		//date
		$date = $item->pubDate;
		$date = strval($date);
	
		$larazon = array(
				'title'=>$title,
				'body'=>$body,
				'image'=>$image,
				'source'=>$source,
				'pubDate'=>$date,
				'publisher' => $publisher
	
		);
		//var_dump($larazon);
		//print_r($items->asXML());
		return $larazon;
	}
	
	public function elConfidencialRSSAction(){
		$map_url = 'http://rss.elconfidencial.com/espana/';
	
		$response_xml_data = file_get_contents($map_url);
		$data = simplexml_load_string($response_xml_data);
		//foreach ($data->children() as $ch){
			//var_dump($ch);
		//}
		//exit();
		$noticias = new SimpleXMLElement($response_xml_data);
		$item = $noticias->entry;
		
		$publisher = 'El Confidencial';
		
		$title = $item->title;
		$title = strval($title);
		
		$source = $item->id;
		$source = strval($source);
	
		$summary = $item->summary;
		$body = strval($summary);
		$body =strip_tags($body);
		$body = preg_replace("/&#?[a-z0-9]{2,8};/i","",$body);
		
		//image
		preg_match_all('/src="(.*?)"/',$summary,$imagenes);
		if(count($imagenes)>1){
			$errors = array_filter($imagenes);
			if (!empty($errors)) {
				$image = $imagenes[1][0];
			}else {
				$image = 'No se ha podido obtener.';
			}
		} else {
			$image = 'No se ha podido obtener.';
		}
		
		//date
		$date = $item->published;
		$date = strval($date);
	
		$elconfidencial = array(
				'title'=>$title,
				'body'=>$body,
				'image'=>$image,
				'source'=>$source,
				'pubDate'=>$date,
				'publisher' => $publisher
	
		);
		//var_dump($elconfidencial);
		//print_r($items->asXML());
		return $elconfidencial;
	}
}