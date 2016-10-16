<?php

namespace Prueba\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','textarea',array(
            		'label'=>'Titular',
            		'required'=>true,
            		'invalid_message' => 'Se necesita un titular.',
            		'attr' => array('cols' => '100%', 'rows' => '2', )
            ))
            ->add('body','textarea',array(
            		'label'=>'Descripción',
            		'required'=>true,
            		'invalid_message' => 'Se necesita una descripci�n.',
            		'attr' => array('cols' => '100%', 'rows' => '5', )
            ))
            ->add('image','textarea',array(
            		'label'=>'URL de la imagen',
            		'required'=>true,
            		'invalid_message' => 'Se necesita la URL de la imagen.',
            		'attr' => array('cols' => '100%', 'rows' => '2', )
            ))
            ->add('source','textarea',array(
            		'label'=>'Noticia',
            		'required'=>true,
            		'invalid_message' => 'Se necesita una noticia.',
            		'attr' => array('cols' => '100%', 'rows' => '2', )
            ))
            ->add('publisher','textarea',array(
            		'label'=>'Periódico',
            		'required'=>true,
            		'invalid_message' => 'Se necesita un periódico',
            		'attr' => array('cols' => '100%', 'rows' => '1', )
            ))
            ->add('date','date',array(
            		'label'=>'Fecha de publicación: año-mes-día',
            		'format' => 'yyyy-MM-dd',
            		'required'=>true,
            		'invalid_message' => 'Selecciona una fecha de publicación.',
            		'attr' => array('cols' => '100%', 'rows' => '5', )
            ))
            ->add('save','submit',array('label'=> 'Guardar'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Prueba\MainBundle\Entity\Feed'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'prueba_mainbundle_feed';
    }
}
