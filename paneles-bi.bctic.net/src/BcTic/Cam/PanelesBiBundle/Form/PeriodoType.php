<?php

namespace BcTic\Cam\PanelesBiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PeriodoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('panel','entity', array(
                  'label' => 'Panel',
                  'class' => 'BcTicCamPanelesBiBundle:Panel',
                  'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p');
                    }
                ))
            ->add('mes','choice',array(
                'choices'   => array(
                    '1' => 'Enero', 
                    '2' => 'Febrero',
                    '3' => 'Marzo',
                    '4' => 'Abril',
                    '5' => 'Mayo',
                    '6' => 'Junio',
                    '7' => 'Julio',
                    '8' => 'Agosto',
                    '9' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre'),
                'required'  => true
                             ))
            ->add('anno','choice', array(
                'label' => 'Año',
                'choices' => array(
                     date('Y') => date('Y'),
                     date('Y') - 1 => date('Y') - 1,
                     date('Y') - 2 => date('Y') - 2,
                     ),
                'required'  => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BcTic\Cam\PanelesBiBundle\Entity\Periodo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bctic_cam_panelesbibundle_periodo';
    }
}
