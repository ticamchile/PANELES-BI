<?php

namespace BcTic\Cam\PanelesBiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IndicadorFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaDesde','date', array(
                'widget' => 'choice',
                'format' => 'dMy',
                'years' => array('2013','2014','2015','2016')
              )) 
            ->add('fechaHasta','date', array(
                'widget' => 'choice',
                'format' => 'dMy',
                'years' => array('2013','2014','2015','2016')
              ))
            ->add('indicadores','entity', array(
                  'label' => 'Indicadores',
                  'class' => 'BcTicCamPanelesBiBundle:Indicador',
                  'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                           ->groupBy('i.indicador')
                           ->orderBy('i.indicador', 'ASC');
                    },
                   'empty_value' => '- TODOS LOS INDICADORES -',
                   'empty_data' => "",
                   'multiple' => true,
                   'required' => true
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BcTic\Cam\PanelesBiBundle\Entity\IndicadorFilter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bctic_cam_panelesbibundle_indicador';
    }
}
