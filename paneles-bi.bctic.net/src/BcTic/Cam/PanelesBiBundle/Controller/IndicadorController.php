<?php

namespace BcTic\Cam\PanelesBiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BcTic\Cam\PanelesBiBundle\Entity\Indicador;
use BcTic\Cam\PanelesBiBundle\Entity\IndicadorFilter;
use BcTic\Cam\PanelesBiBundle\Form\IndicadorFilterType;

/**
 * Indicador controller.
 *
 * @Route("/indicador")
 */
class IndicadorController extends Controller
{

    
    /**
     * Lists all Indicador entities in a filter .
     *
     * @Route("/filter", name="indicador_filter")
     * @Method("GET")
     * @Template()
     */
    public function filterAction()
    {
        

        $entity = new IndicadorFilter();
        $entity->setFechaDesde(date_create(date('Y').'-01-01'));
        $entity->setFechaHasta(date_create(date('Y').'-12-31'));

        $form = $this->createForm(new IndicadorFilterType(), $entity, array(
            'action' => $this->generateUrl('indicador_report'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Buscar'));

        return array(
            'form'   => $form->createView(),
            'json' => false,
        );
    }

    /**
     * Creates a report
     *
     * @Route("/report", name="indicador_report")
     * @Method("POST")
     * @Template("BcTicCamPanelesBiBundle:Indicador:filter.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new IndicadorFilter();
        
        $form = $this->createForm(new IndicadorFilterType(), $entity, array(
            'action' => $this->generateUrl('indicador_report'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Buscar'));

        $form->handleRequest($request);

        if ($form->isValid()) {


        //Ahora las categorias, para determinar el largo de la serie
        $categorias = array();
        $empty = array();
        $fechaDesde = $entity->getFechaDesde();
        //Los meses de diferencia - tomo el mes mayor y bajo:
        while($fechaDesde < $entity->getFechaHasta()) {
          $categorias[] = $fechaDesde->format('m-Y');
          $empty[$fechaDesde->format('m-Y')] = 0;
          $fechaDesde->add(new \DateInterval('P1M'));
        }    

        //Las series:
        //¿CUANTAS AREAS HAY PARA LOS INDICADORES INDICADOS?
        $indicadores = array();
        $indicadoresRef = array();
        foreach($entity->getIndicadores() as $indicadorEntity) {
           $indicadores[$indicadorEntity->getIndicador()] = $indicadorEntity->getIndicador();
           $indicadoresRef[$indicadorEntity->getIndicador()] = $empty;
        }

        array_walk($indicadores,function (&$i) { $i = "'".$i."'"; });

        $em = $this->getDoctrine()->getManager();
        //LA SQL:
        $matrix = array();
        $sql = "SELECT DISTINCT(area) FROM INDICADOR WHERE indicador in (".implode(",",$indicadores).") GROUP BY area, CONCAT(mes,'-',anno)";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $info) {
           $matrix[$info['area']] = $indicadoresRef;
        }


        
        //Ahora relleno
        $sql = "SELECT area,valor, DATE_FORMAT(STR_TO_DATE(CONCAT(dia,'-',mes,'-',anno),'%d-%m-%Y'),'%m-%Y') as periodo,indicador FROM INDICADOR WHERE indicador in (".implode(",",$indicadores).") GROUP BY area, indicador, CONCAT(mes,'-',anno)";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $info) {
           $matrix[$info['area']][$info['indicador']][$info['periodo']] = (float) $info['valor'];
        }

        $series = array();
        //Matrix a serie:
        foreach ($matrix as $serieMatrixKey => $serieMatrix) {
          foreach ($serieMatrix as $serieDataMatrixKey => $serieDataMatrixValues) {
            $series[] = array(
              'name' => $serieMatrixKey.' - '.str_replace(array('_'),array(' '),$serieDataMatrixKey),
              'data' => array_values($serieDataMatrixValues)
             );
          }  
        }

        $data = array(
        'categories' => $categorias,
        'yAxisTitle' => 'Valor indicador',
        'xAxisTitle' => 'Periodo',
        'series' => $series,
        ); 

          return array(
            'form'   => $form->createView(),
            'json' => json_encode($data)
          );           
        }

        return array(
            'form'   => $form->createView(),
            'json' => false,
        );
    }


    /**
     * Lists all Indicador entities.
     *
     * @Route("/index/{page}", name="indicador_index", defaults={ "page" = 1 })
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        //10 is the page size
        $entities = $em->getRepository('BcTicCamPanelesBiBundle:Indicador')->findBy(
              array(),
              array('anno' => 'ASC','mes' => 'DESC','dia' => 'ASC','indicador' => 'ASC'),
              50,
              50 * ($page - 1)

        );

        $csrf = $this->get('form.csrf_provider');


        return array(
            'page' => $page,
            'entities' => $entities,
            'csrf' => $csrf,
        );
    }

    /**
     * Finds and displays a Indicador entity.
     *
     * @Route("/show/{id}", name="indicador_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Indicador')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Indicador entity.');
        }

        $csrf = $this->get('form.csrf_provider');


        return array(
            'entity'      => $entity,
            'csrf' => $csrf,
        );
    }
}
