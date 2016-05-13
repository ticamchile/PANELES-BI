<?php

namespace BcTic\Cam\PanelesBiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PanelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('responsable')
            ->add('ruta','textarea', array('label' => 'Configuración'))
            ->add('visible','choice', array(
                'choices' => array(
                    0 => 'NO VISIBLE/OCULTO',
                    1 => 'VISIBLE',
                    )
                ))
            ->add('usuarios')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BcTic\Cam\PanelesBiBundle\Entity\Panel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bctic_cam_panelesbibundle_panel';
    }
}
