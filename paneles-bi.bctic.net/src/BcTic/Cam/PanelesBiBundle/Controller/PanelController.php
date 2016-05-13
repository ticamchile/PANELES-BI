<?php

namespace BcTic\Cam\PanelesBiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BcTic\Cam\PanelesBiBundle\Entity\Panel;
use BcTic\Cam\PanelesBiBundle\Form\PanelType;

/**
 * Panel controller.
 *
 * @Route("/paneles")
 */
class PanelController extends Controller
{

    /**
     * Lists all Panel entities.
     *
     * @Route("/index/{page}", name="paneles_index", defaults={ "page" = 1 })
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        //10 is the page size
        $entities = $em->getRepository('BcTicCamPanelesBiBundle:Panel')->findBy(
              array(),
              array('id' => 'DESC'),
              10,
              10 * ($page - 1)

        );

        $csrf = $this->get('form.csrf_provider');


        return array(
            'page' => $page,
            'entities' => $entities,
            'csrf' => $csrf,
        );
    }
    /**
     * Creates a new Panel entity.
     *
     * @Route("/add", name="paneles_create")
     * @Method("POST")
     * @Template("BcTicCamPanelesBiBundle:Panel:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Panel();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se grabaron correctamente.'
            );

            return $this->redirect($this->generateUrl('paneles_index', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Panel entity.
    *
    * @param Panel $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Panel $entity)
    {
        $form = $this->createForm(new PanelType(), $entity, array(
            'action' => $this->generateUrl('paneles_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new Panel entity.
     *
     * @Route("/new", name="paneles_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Panel();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Panel entity.
     *
     * @Route("/edit/{id}", name="paneles_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Panel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Panel entity.');
        }

        $editForm = $this->createEditForm($entity);


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
    * Creates a form to edit a Panel entity.
    *
    * @param Panel $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Panel $entity)
    {
        $form = $this->createForm(new PanelType(), $entity, array(
            'action' => $this->generateUrl('paneles_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Panel entity.
     *
     * @Route("/update/{id}", name="paneles_update")
     * @Method("PUT")
     * @Template("BcTicCamPanelesBiBundle:Panel:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Panel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Panel entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se grabaron correctamente.'
            );

            return $this->redirect($this->generateUrl('paneles_index', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Finds and displays a Panel entity.
     *
     * @Route("/show/{id}", name="paneles_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Panel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Panel entity.');
        }

        $csrf = $this->get('form.csrf_provider');


        return array(
            'entity'      => $entity,
            'csrf' => $csrf,
        );
    }
    /**
     * Deletes a Panel entity.
     *
     * @Route("/delete/{id}/{token}", name="paneles_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id, $token)
    {

        $csrf = $this->get('form.csrf_provider');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Panel')->find($id);

        if (!$entity) {
              throw $this->createNotFoundException('Unable to find Panel entity.');
        }

        if ($csrf->isCsrfTokenValid('entity'.$entity->getId(), $token)) {
            $em->remove($entity);
            $em->flush();

             $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se borraron correctamente.'
            );
        }


        return $this->redirect($this->generateUrl('paneles_index'));
    }

    protected function acumulate(&$data = array()){
      $i = 1;
      while(isset($data[$i]) && isset($data[$i - 1])) {
        $data[$i] = $data[$i] + $data[$i - 1];
        $i++;
      }     
    }

    /**
     * Finds and displays a Panel entity.
     *
     * @Route("/data/{mes}/{anno}/panel_1_1.html", name="panel_data_1_1")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_1_Action($mes,$anno)
    {

      $area = "TI";

      $em = $this->getDoctrine()->getManager();

      $item = array();

      $sql = "SELECT indicador as tipo FROM VIEW_INDICADOR_FINANZAS;";
      //CREATE VIEW VIEW_INDICADOR_FINANZAS as SELECT DISTINCT(REPLACE(REPLACE(REPLACE(indicador,'REAL_',''),'PRESUPUESTO_',''),'PROYECTADO_','')) as indicador FROM INDICADOR WHERE indicador LIKE '%FINANZAS\_%' ORDER BY indicador ASC;
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();

      foreach ($stmt->fetchAll() as $info) {
        $item[$info['tipo']] = array('monto' => 0, 'presupuesto' => 0, 'monto_acumulado' => 0, 'presupuesto_acumulado' => 0, 'proyectado' => 0, 'presupuesto_anual' => 0) ;

        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'REAL_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['monto'] = $info_aux['monto'];
        }

        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'PRESUPUESTO_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['presupuesto'] = $info_aux['monto'];
        }

        //Acumulado - Todo hasta = o menor del mes/Año
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'REAL_".$info['tipo']."' AND mes <= ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['monto_acumulado'] = $info_aux['monto'];
        }

        //Acumulado - Todo hasta = o menor del mes/Año
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'PRESUPUESTO_".$info['tipo']."' AND mes <= ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['presupuesto_acumulado'] = $info_aux['monto'];
        }

        //Acumulado - Todo hasta 
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'PROYECTADO_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['proyectado'] = $info_aux['monto'];
        }

        //Acumulado 
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'PRESUPUESTO_".$info['tipo']."' AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['presupuesto_anual'] = $info_aux['monto'];
        }



      }


      return array(
        'data' => $item
        );
    }

     /**
     * Finds and displays a Panel entity.
     *
     * @Route("/data/{mes}/{anno}/panel_1_2.json", name="panel_data_1_2")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_2_Action($mes,$anno)
    {
      $data = array(
        'categories' => array(
             'Ene',
             'Feb',
             'Mar',
             'Abr',
             'May',
             'Jun',
             'Jul',
             'Ago',
             'Sept',
             'Oct',
             'Nov',
             'Dic'
             ),
        'yAxisTitle' => 'MM$',
        'xAxisTitle' => 'Mes '.$anno,
        'series' => array(
            array(
              'name' => 'Presupuesto',
              'color' => '#6095c9',
              'data' => array(0,0,0,0,0,0,0,0,0,0,0,0)
             ),
            array(
              'name' => 'Real',
              'color' => '#8979e5',
              'data' => array(0,0,0,0,0,0,0,0,0,0,0,0)
             ),
            ),
        );  

      //Relleno las series con la SQL:
      $em = $this->getDoctrine()->getManager();
      
      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor)/1000 as MONTO FROM INDICADOR WHERE indicador LIKE 'PRESUPUESTO_FINANZAS_%' AND anno = ".$anno." GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][0]['data'][$info['mes'] - 1] = (float) $info['MONTO'];
      }

      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor)/1000 as MONTO FROM INDICADOR WHERE indicador LIKE 'REAL_FINANZAS_%' AND anno = ".$anno." GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][1]['data'][$info['mes'] - 1] = (float) $info['MONTO'];
      }

      return new JsonResponse($data);
    }

     /**
     * Finds and displays a Panel entity.
     *
     * @Route("/data/{mes}/{anno}/panel_1_3.json", name="panel_data_1_3")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_3_Action($mes,$anno)
    {

      $meses = array();
      $i = 1;
      while($i < ($mes - 1) ) {
        $meses[] = 0;
        $i++;
      } 
      $data = array(
        'categories' => array(
             'Ene',
             'Feb',
             'Mar',
             'Abr',
             'May',
             'Jun',
             'Jul',
             'Ago',
             'Sept',
             'Oct',
             'Nov',
             'Dic'
             ),
        'yAxisTitle' => 'MM$',
        'xAxisTitle' => 'Mes '.$anno,
        'series' => array(
            array(
              'name' => 'Presupuesto',
              'color' => '#6095c9',
              'data' => array(0,0,0,0,0,0,0,0,0,0,0,0)
             ),
            array(
              'name' => 'Real',
              'color' => '#FF0000',
              'data' => $meses
             ),
            array(
              'name' => 'Proyectado',
              'color' => '#A50000',
              'data' => array(0,0,0,0,0,0,0,0,0,0,0,0)
             ), 
            ),
        );  

      //Relleno las series con la SQL:
      $em = $this->getDoctrine()->getManager();
      
      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor)/1000 as MONTO FROM INDICADOR WHERE indicador LIKE 'PRESUPUESTO_FINANZAS_%' AND anno = ".$anno." GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][0]['data'][$info['mes'] - 1] = (float) $info['MONTO'];
      }
      //Acumular
      $this->acumulate($data['series'][0]['data']);

      //DEPENDE DEL MES QUE ESTOY.
      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor)/1000 as MONTO FROM INDICADOR WHERE indicador LIKE 'REAL_FINANZAS_%' AND anno = ".$anno." AND mes <= ".$mes." GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][1]['data'][$info['mes'] - 1] = (float) $info['MONTO'];
      }
      //Acumular
      $this->acumulate($data['series'][1]['data']);

      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor)/1000 as MONTO FROM INDICADOR WHERE indicador LIKE 'PROYECTADO_FINANZAS_%' AND anno = ".$anno." GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][2]['data'][$info['mes'] - 1] = (float) $info['MONTO'];
      }
      $this->acumulate($data['series'][2]['data']); 

      return new JsonResponse($data);
    }

     /**
     * Finds and displays a Panel entity.
     *
     * @Route("/data/{mes}/{anno}/panel_1_4.json", name="panel_data_1_4")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_4_Action($mes,$anno)
    {
      $data = array(
        'categories' => array(
             'Ene',
             'Feb',
             'Mar',
             'Abr',
             'May',
             'Jun',
             'Jul',
             'Ago',
             'Sept',
             'Oct',
             'Nov',
             'Dic',
             'Acum'
             ),
        'yAxisTitle' => 'MM$',
        'xAxisTitle' => 'Mes '.$anno,
        'series' => array(
            array(
              'name' => 'Cumple',
              'color' => '#AAF416',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'column',
              'valueSuffix' => '%',
              'yAxis' => 0,
             ),
            array(
              'name' => 'Meta',
              'color' => '#FF0000',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'line',
              'valueSuffix' => '%',
              'yAxis' => 0,
             ), 
            array(
              'name' => 'Total general',
              'color' => '#6095c9',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'spline',
              'valueSuffix' => '',
              'yAxis' => 1,
             ),
            ),
        );  

      //Relleno las series con la SQL:
      $em = $this->getDoctrine()->getManager();
      
      $cumple = array(null,null,null,null,null,null,null,null,null,null,null,null);
      //CUMPLE

      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'SERVICIO_SOLICITUD_SERVICIO_CUMPLE' AND area = 'TI' AND anno = ".$anno." and valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      //SERVICIO_SOLICITUD_SERVICIO_CUMPLE
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $cumple[$info['mes'] - 1] = (integer) $info['CANTIDAD'];
      }

      $noCumple = array(null,null,null,null,null,null,null,null,null,null,null,null);
      //NO_CUMPLE DIFERENCIA:
      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'SERVICIO_SOLICITUD_SERVICIO_NO_CUMPLE' AND area = 'TI' AND anno = ".$anno." and valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $noCumple[$info['mes'] - 1] = (integer) $info['CANTIDAD'];
      }

      foreach ($data['series'][0]['data'] as $mes => $valor) {
        if ( ($cumple[$mes] + $noCumple[$mes]) == 0) { 
          $data['series'][0]['data'][$mes] = 0;
          continue;
        }
        
        $data['series'][0]['data'][$mes] = round(100 * ($cumple[$mes] / ($cumple[$mes] + $noCumple[$mes]) ),1);


      }

      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'SERVICIO_SOLICITUD_SERVICIO_META' AND area = 'TI' AND anno = ".$anno." and valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      /*

      | area         | varchar(45)  | NO   | MUL | NULL    |                |
      | granularidad | varchar(1)   | NO   |     | NULL    |                |
      | dia          | int(11)      | NO   |     | NULL    |                |
      | mes          | int(11)      | NO   |     | NULL    |                |
      | anno         | int(11)      | NO   |     | NULL    |                |
      | indicador    | varchar(100) | NO   |     | NULL    |                |
      | valor        | double       | NO   |     | 0       |     

      INSERT INTO INDICADOR (area,granularidad,dia,mes,anno,indicador,valor) VALUES 
      ("TI","M",1,1,2014,"SERVICIO_SOLICITUD_SERVICIO_META","99.0"),
      ("TI","M",1,2,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,3,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,4,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,5,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,6,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,7,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,8,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,9,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,10,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,11,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0"),
      ("TI","M",1,12,2014,"SERVICIO_SOLICITUD_SERVICIO_META","95.0");

      */
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][1]['data'][$info['mes'] - 1] = (float) $info['CANTIDAD'];
      } 

      //CUMPLE
      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor) as CANTIDAD FROM INDICADOR WHERE indicador IN ('SERVICIO_SOLICITUD_SERVICIO_CUMPLE','SERVICIO_SOLICITUD_SERVICIO_NO_CUMPLE') AND area = 'TI' AND anno = ".$anno." GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][2]['data'][$info['mes'] - 1] = (integer) $info['CANTIDAD'];
      }

      $data['minY'] = 90;

      //PROMEDIO LOS DATOS PARA EL FINAL:
      $total = 0;
      $hits = 0;
      foreach ($data['series'][0]['data'] as $key => $value) {
        if ($value > 0) {
          $hits++;
          $total = $total + $value;
        }  
      }
      $data['series'][0]['data'][] = ($hits == 0) ? 0 : ($total / $hits);

      //PROMEDIO LOS DATOS PARA EL FINAL:
      $total = 0;
      $hits = 0;
      foreach ($data['series'][1]['data'] as $key => $value) {
        if (!is_null($value)) {
          $hits++;
          $total = $total + $value;
        }  
      }
      $data['series'][1]['data'][] = ($hits == 0) ? 0 : ($total / $hits);

      //SUMO LOS DATOS PARA EL FINAL:
      $total = 0;
      foreach ($data['series'][2]['data'] as $key => $value) {
        $total = $total + $value;
      }
      $data['series'][2]['data'][] = $total;

      $data['maxOppositeY'] = 2 * round($total,0);

      return new JsonResponse($data);
    }

     /**
     * Finds and displays a Panel entity.
     *
     * @Route("/data/{mes}/{anno}/panel_1_5.json", name="panel_data_1_5")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_5_Action($mes,$anno)
    {
      $data = array(
        'categories' => array(
             'Ene',
             'Feb',
             'Mar',
             'Abr',
             'May',
             'Jun',
             'Jul',
             'Ago',
             'Sept',
             'Oct',
             'Nov',
             'Dic',
             'Acum'
             ),
        'yAxisTitle' => '',
        'xAxisTitle' => 'Mes '.$anno,
        'series' => array(
            array(
              'name' => 'Tiempo de atención promedio (Horas)',
              'color' => '#FF0000',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'line',
              'valueSuffix' => 'hrs',
              'yAxis' => 0,
             ), 
            array(
              'name' => 'Cantidad de tickets',
              'color' => '#6095c9',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'line',
              'valueSuffix' => '',
              'yAxis' => 1,
             ),
            ),
        );  

      //Relleno las series con la SQL:
      $em = $this->getDoctrine()->getManager();
      
      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, AVG(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'MESA_DE_AYUDA_TIEMPO_DE_ATENCION' AND area = 'TI' AND anno = ".$anno." and valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][0]['data'][$info['mes'] - 1] = (float) round($info['CANTIDAD'],1);
      } 

      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, SUM(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'MESA_DE_AYUDA_CANTIDAD' AND area = 'TI' AND anno = ".$anno." and valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][1]['data'][$info['mes'] - 1] = (integer) $info['CANTIDAD'];
      } 

      //PROMEDIO LOS DATOS PARA EL FINAL:
      $total = 0;
      $hits = 0;
      foreach ($data['series'][0]['data'] as $key => $value) {
        if (!is_null($value)) { 
          $hits++;
          $total = $total + $value;
        }
      }
      $data['series'][0]['data'][] = ($hits == 0) ? 0 : ($total / $hits);

      //SUMO LOS DATOS PARA EL FINAL:
      $total = 0;
      foreach ($data['series'][1]['data'] as $key => $value) {
        if (!is_null($value)) { 
          $total = $total + $value;
        }

      }
      $data['series'][1]['data'][] = $total;

      $data['maxOppositeY'] = 2 * round($total,0);

      return new JsonResponse($data);
    }

    /**
     *
     * @Route("/data/{mes}/{anno}/panel_1_6.json", name="panel_data_1_6")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_6_Action($mes,$anno)
    {
      return $this->panel_uptime($mes,$anno,'ORACLE');
    }


    /**
     *
     * @Route("/data/{mes}/{anno}/panel_1_7.json", name="panel_data_1_7")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_7_Action($mes,$anno)
    {
      return $this->panel_uptime($mes,$anno,'CORREO');
    }

    /**
     *
     * @Route("/data/{mes}/{anno}/panel_1_8.json", name="panel_data_1_8")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_8_Action($mes,$anno)
    {
      return $this->panel_uptime($mes,$anno,'INTERNET');
    }

    /**
     *
     * @Route("/data/{mes}/{anno}/panel_1_9.json", name="panel_data_1_9")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_9_Action($mes,$anno)
    {
      return $this->panel_uptime($mes,$anno,'REDES');
    }

    /**
     *
     * @Route("/data/{mes}/{anno}/panel_1_10.json", name="panel_data_1_10")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_10_Action($mes,$anno)
    {
      return $this->panel_uptime($mes,$anno,'SAMAC');
    }  

    /**
     *
     * @Route("/data/{mes}/{anno}/panel_1_11.json", name="panel_data_1_11")
     * @Method("GET")
     * @Template()
     */
    public function panel_data_1_11_Action($mes,$anno)
    {
      return $this->panel_uptime($mes,$anno,'GENERAL');
    }        

    protected function panel_uptime($mes,$anno,$sistema)
    {
      //EN CASO DE UPTIME ES TIEMPO DE ATENCION CLIENTE.
      $data = array(
        'categories' => array(
             'Ene',
             'Feb',
             'Mar',
             'Abr',
             'May',
             'Jun',
             'Jul',
             'Ago',
             'Sept',
             'Oct',
             'Nov',
             'Dic',
             'Prom'
             ),
        'yAxisTitle' => 'Indicador',
        'xAxisTitle' => 'Mes '.$anno,
        'series' => array(
            array(
              'name' => 'UPTIME',
              'color' => '#AAF416',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'column',
              'valueSuffix' => '%',
              'yAxis' => 0,
             ),
            array(
              'name' => 'Meta',
              'color' => '#FF0000',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'line',
              'valueSuffix' => '%',
              'yAxis' => 0,
             ), 
            array(
              'name' => 'Incidencias',
              'color' => '#6095c9',
              'data' => array(null,null,null,null,null,null,null,null,null,null,null,null),
              'type' => 'line',
              'valueSuffix' => '',
              'yAxis' => 1,
             ),
            ),
        );  

      //Relleno las series con la SQL:
      $em = $this->getDoctrine()->getManager();
      
      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, AVG(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'UPTIME_RATIO_".$sistema."' AND area = 'TI' AND anno = ".$anno." AND valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        //UPTIME %
        $data['series'][0]['data'][$info['mes'] - 1] = 100 * (float) $info['CANTIDAD'];
      }

      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, AVG(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'UPTIME_CANTIDAD_".$sistema."' AND area = 'TI' AND anno = ".$anno." AND valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        //UPTIME %
        $data['series'][2]['data'][$info['mes'] - 1] = (integer) $info['CANTIDAD'];
      }

      $sql = "SELECT mes, CONCAT(mes,'-',anno) as PERIODO, AVG(valor) as CANTIDAD FROM INDICADOR WHERE indicador = 'UPTIME_CANTIDAD_META_".$sistema."' AND area = 'TI' AND anno = ".$anno." AND valor <> -1 GROUP BY PERIODO ORDER BY mes;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $info) {
        $data['series'][1]['data'][$info['mes'] - 1] = 100 * (float) $info['CANTIDAD'];
      }


      if (array_sum(array_values($data['series'][0]['data'])) == -12) $data['series'][0]['data'] = array();
      if (array_sum(array_values($data['series'][1]['data'])) == -12) $data['series'][1]['data'] = array();
      if (array_sum(array_values($data['series'][2]['data'])) == -12) $data['series'][2]['data'] = array();

      $data['minY'] = 95;

      //PROMEDIO LOS DATOS PARA EL FINAL:
      $total = 0;
      $hits = 0;
      foreach ($data['series'][0]['data'] as $key => $value) {
        if (!is_null($value)) { 
          $hits++;
          $total = $total + $value;
        }
      }
      $data['series'][0]['data'][] = ($hits == 0) ? 0 : round($total / $hits,2);


      //PROMEDIO LOS DATOS PARA EL FINAL:
      $total = 0;
      $hits = 0;
      foreach ($data['series'][1]['data'] as $key => $value) {
        if (!is_null($value)) { 
          $hits++;
          $total = $total + $value;
        }
      }
      $data['series'][1]['data'][] = ($hits == 0) ? 0 : round($total / $hits,2);


      //SUMO LOS DATOS PARA EL FINAL:
      $total = 0;
      $hits = 0;
      foreach ($data['series'][2]['data'] as $key => $value) {
        if (!is_null($value)) { 
          $hits++;
          $total = $total + $value;
        }
      }
      $data['series'][2]['data'][] = ($hits == 0) ? 0 : round($total / $hits,2);

      $data['maxOppositeY'] = 2 * round($total,0);

      
      return new JsonResponse($data);
    }  


    /**
     * 
     *
     * @Route("/data/{mes}/{anno}/panel_2_1.html", name="panel_data_2_1")
     * @Method("GET")
     * @Template("BcTicCamPanelesBiBundle:Panel:panel_data_1_1_.html.twig")
     */
    public function panel_data_2_1_Action($mes,$anno)
    {

      $area = "LOGISTICA";

      $em = $this->getDoctrine()->getManager();

      $item = array();

      $sql = "SELECT indicador as tipo FROM VIEW_INDICADOR_FINANZAS;";
      //CREATE VIEW VIEW_INDICADOR_FINANZAS as SELECT DISTINCT(REPLACE(REPLACE(REPLACE(indicador,'REAL_',''),'PRESUPUESTO_',''),'PROYECTADO_','')) as indicador FROM INDICADOR WHERE indicador LIKE '%FINANZAS\_%' ORDER BY indicador ASC;
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();

      foreach ($stmt->fetchAll() as $info) {
        $item[$info['tipo']] = array('monto' => 0, 'presupuesto' => 0, 'monto_acumulado' => 0, 'presupuesto_acumulado' => 0, 'proyectado' => 0, 'presupuesto_anual' => 0) ;

        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'REAL_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['monto'] = $info_aux['monto'];
        }

        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'PRESUPUESTO_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['presupuesto'] = $info_aux['monto'];
        }

        //Acumulado - Todo hasta = o menor del mes/Año
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'REAL_".$info['tipo']."' AND mes <= ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['monto_acumulado'] = $info_aux['monto'];
        }

        //Acumulado - Todo hasta = o menor del mes/Año
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND area = '".$area."' AND indicador = 'PRESUPUESTO_".$info['tipo']."' AND mes <= ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['presupuesto_acumulado'] = $info_aux['monto'];
        }

        //Acumulado - Todo hasta 
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'PROYECTADO_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['proyectado'] = $info_aux['monto'];
        }

        //Acumulado 
        $sql_aux = "SELECT SUM(valor) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'PRESUPUESTO_".$info['tipo']."' AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['presupuesto_anual'] = $info_aux['monto'];
        }



      }


      return array(
        'data' => $item
        );
    }

    /**
     * 
     *
     * @Route("/data/{mes}/{anno}/panel_4_2.html", name="panel_data_4_2")
     * @Method("GET")
     * @Template("BcTicCamPanelesBiBundle:Panel:panel_data_4_2_.html.twig")
     */
    public function panel_data_4_2_Action($mes,$anno)
    {

      $area = "COMPRAS";

      $em = $this->getDoctrine()->getManager();

      $item = array();

      $sql = "SELECT subgerencias as tipo FROM VIEW_INDICADOR_SUBGERENCIAS WHERE area = '".$area."' ORDER BY tipo;";
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute();

      foreach ($stmt->fetchAll() as $info) {

        $item[$info['tipo']] = array('permanente' => 0, 'no_permanente' => 0, 'total' => 0, 'permanente_acumulado' => 0, 'no_permanente_acumulado' => 0, 'total_acumulado' => 0) ;

        //PERMANENTE
        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_PERMANENTE_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['permanente'] = $info_aux['monto'];
        }

        //NO_PERMANENTE
        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_NO_PERMANENTE_".$info['tipo']."' AND mes = ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['no_permanente'] = $info_aux['monto'];
        }

        $item[$info['tipo']]['total'] = $item[$info['tipo']]['permanente'] + $item[$info['tipo']]['no_permanente'];  

        //PERMANENTE
        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_PERMANENTE_".$info['tipo']."' AND mes <= ".$mes."  AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['permanente_acumulado'] = $info_aux['monto'];
        }

        //NO_PERMANENTE
        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_NO_PERMANENTE_".$info['tipo']."' AND mes <= ".$mes." AND anno = ".$anno." ";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['no_permanente_acumulado'] = $info_aux['monto'];
        }

        $item[$info['tipo']]['total_acumulado'] = $item[$info['tipo']]['permanente_acumulado'] + $item[$info['tipo']]['no_permanente_acumulado'];        

        //AHORA TENGO QUE PROYECTAR EN BASE EL AÑO ANTERIOR Y EL NUEVO:
        $item[$info['tipo']]['permanente_proyeccion_data'] = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0,12 => 0);

        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto, mes FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_PERMANENTE_".$info['tipo']."' AND anno = ".( $anno - 1 )." GROUP BY mes,anno";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['permanente_proyeccion_data'][$info_aux['mes']] = $info_aux['monto'];
        }

        //Ahora el año actual:
        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto, mes FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_PERMANENTE_".$info['tipo']."' AND mes <= ".$mes." AND anno = ".$anno." GROUP BY mes,anno";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['permanente_proyeccion_data'][$info_aux['mes']] = $info_aux['monto'];
        }

        //SUMO PROYECCION:
        $item[$info['tipo']]['permanente_proyeccion'] = array_sum(array_values($item[$info['tipo']]['permanente_proyeccion_data']));

        //AHORA TENGO QUE PROYECTAR EN BASE EL AÑO ANTERIOR Y EL NUEVO:
        $item[$info['tipo']]['no_permanente_proyeccion_data'] = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0,12 => 0);

        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto, mes FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_NO_PERMANENTE_".$info['tipo']."' AND anno = ".( $anno - 1 )." GROUP BY mes,anno";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['no_permanente_proyeccion_data'][$info_aux['mes']] = $info_aux['monto'];
        }

        //Ahora el año actual:
        $sql_aux = "SELECT IFNULL(SUM(valor),0) as monto, mes FROM INDICADOR WHERE area = '".$area."' AND indicador = 'CONSUMO_SUBGERENCIA_NO_PERMANENTE_".$info['tipo']."' AND mes <= ".$mes." AND anno = ".$anno." GROUP BY mes,anno";
        $stmt_aux = $em->getConnection()->prepare($sql_aux);
        $stmt_aux->execute();
        foreach ($stmt_aux->fetchAll() as $info_aux) {
          $item[$info['tipo']]['no_permanente_proyeccion_data'][$info_aux['mes']] = $info_aux['monto'];
        }

        //SUMO PROYECCION:
        $item[$info['tipo']]['no_permanente_proyeccion'] = array_sum(array_values($item[$info['tipo']]['no_permanente_proyeccion_data']));

      }

      return array(
        'data' => $item,
        'anno' => $anno,
        'mes' => $mes
        );
    }    
  

}
