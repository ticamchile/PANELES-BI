<?php

namespace BcTic\Cam\PanelesBiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArchivoDeCargaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt','hidden')
            ->add('status','hidden')
            ->add('tipo','choice', array('choices' => array(
                     'PRESUPUESTO_VS_REAL_TI' => 'PANEL TI: PRESUPUESTO V/S REAL',
                     'SOLICITUD_SERVICIOS_TI' => 'PANEL TI: SOLICITUD SERVICIO',
                     'MESA_DE_AYUDA_UPTIME_TI' => 'PANEL TI: MESA DE AYUDA & UPTIME (Panel TI)',
                     'CONSUMOS_COMPRAS' => 'PANEL COMPRAS: CONSUMOS',
                     'PM_COMPRAS' => 'PANEL COMPRAS: PRECIOS MEDIOS',
                     'SUBINVENTARIOS_COMPRAS' => 'PANEL COMPRAS: MAESTRO DE SUBINVENTARIOS',
                     'MAESTRODEMATERIALES_COMPRAS' => 'PANEL COMPRAS: MAESTRO DE MATERIALES'
                   )
                 ))
            ->add('file','file', array('label' => 'Archivo a importar (* Máx 50Mb)'))
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
            ->add('notes','textarea',array('label' => 'Notas'));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BcTic\Cam\PanelesBiBundle\Entity\ArchivoDeCarga'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bctic_cam_panelesbibundle_archivodecarga';
    }
}
