<?php

namespace BcTic\Cam\PanelesBiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SolicitudServicioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('area')
            ->add('tipo')
            ->add('mes')
            ->add('anno')
            ->add('cantidad')
            ->add('tiempoDeAtencionPromedio')
            ->add('estado')
            ->add('categoria')
            ->add('porcentaje')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BcTic\Cam\PanelesBiBundle\Entity\SolicitudServicio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bctic_cam_panelesbibundle_solicitudservicio';
    }
}
