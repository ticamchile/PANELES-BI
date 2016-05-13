<?php

namespace BcTic\Cam\PanelesBiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MonitorServicioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('area')
            ->add('sistema')
            ->add('mes')
            ->add('anno')
            ->add('incidencias')
            ->add('uptime')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BcTic\Cam\PanelesBiBundle\Entity\MonitorServicio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bctic_cam_panelesbibundle_monitorservicio';
    }
}
