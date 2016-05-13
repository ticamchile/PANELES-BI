<?php

namespace BcTic\Cam\PanelesBiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use BcTic\Cam\PanelesBiBundle\Entity\Periodo;
use BcTic\Cam\PanelesBiBundle\Form\PeriodoType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name = "default_index")
     * @Template()
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

    	$form = $this->createForm(new PeriodoType(), new Periodo(), array(
            'action' => $this->generateUrl('reporte_generate'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ver'));

        $paneles = $em->getRepository('BcTicCamPanelesBiBundle:Panel')
                           ->createQueryBuilder('p')
                           ->join('p.usuarios','u')
                           ->where('u.id = :usuario AND p.visible = 1')
                           ->setParameters(array('usuario' => $this->get('security.context')->getToken()->getUser()->getId()))
                           ->getQuery()
                           ->execute();  

        return array(
          'paneles' => $paneles,
          'periodo_form' => $form->createView()
          );
    }

    /**
     * Creates a new View for Panel.
     *
     * @Route("/panel", name="reporte_generate")
     * @Method("POST")
     * @Template("BcTicCamPanelesBiBundle:Default:index.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Periodo();
        
        $form = $this->createForm(new PeriodoType(), $entity, array(
            'action' => $this->generateUrl('reporte_generate'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ver'));

        $form->handleRequest($request);

        if ($form->isValid()) {
          //Se despacha la vista del panel en cuestion, faltaría validar que el usuario tiene acceso a la URL:
          return $this->redirect($this->generateUrl('reporte_mostrar', array('panel_id' => $entity->getPanel()->getId(), 'mes' => $entity->getMes(), 'anno' => $entity->getAnno())));

        }

        $paneles = $em->getRepository('BcTicCamPanelesBiBundle:Panel')
                           ->createQueryBuilder('p')
                           ->join('p.usuarios','u')
                           ->where('u.id = :usuario ')
                           ->setParameters(array('usuario' => 1))
                           ->getQuery()
                           ->execute();  

        return array(
            'paneles' => $paneles,
            'periodo_form'   => $form->createView(),
        );
    }

     /**
     * Display the report
     *
     * @Route("/panel/{panel_id}/{mes}/{anno}/show.html", name="reporte_mostrar")
     * @Method("GET")
     * @Template()
     */
    public function displayPanelAction($panel_id,$mes,$anno)
    {

      $em = $this->getDoctrine()->getManager();
      $panel = $em->getRepository('BcTicCamPanelesBiBundle:Panel')->find($panel_id);

      return array(
          'mes' => $mes,
          'anno' => $anno,
          'panel' => $panel,
        );
    }

}
