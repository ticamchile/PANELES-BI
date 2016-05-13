<?php

namespace BcTic\Cam\PanelesBiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BcTic\Cam\PanelesBiBundle\Entity\MonitorServicio;
use BcTic\Cam\PanelesBiBundle\Form\MonitorServicioType;

/**
 * MonitorServicio controller.
 *
 * @Route("/monitor_servicio")
 */
class MonitorServicioController extends Controller
{

    /**
     * Lists all MonitorServicio entities.
     *
     * @Route("/index/{page}", name="monitor_servicio_index", defaults={ "page" = 1 })
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        //10 is the page size
        $entities = $em->getRepository('BcTicCamPanelesBiBundle:MonitorServicio')->findBy(
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
     * Creates a new MonitorServicio entity.
     *
     * @Route("/add", name="monitor_servicio_create")
     * @Method("POST")
     * @Template("BcTicCamPanelesBiBundle:MonitorServicio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MonitorServicio();
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

            return $this->redirect($this->generateUrl('monitor_servicio_index', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a MonitorServicio entity.
    *
    * @param MonitorServicio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MonitorServicio $entity)
    {
        $form = $this->createForm(new MonitorServicioType(), $entity, array(
            'action' => $this->generateUrl('monitor_servicio_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new MonitorServicio entity.
     *
     * @Route("/new", name="monitor_servicio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MonitorServicio();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MonitorServicio entity.
     *
     * @Route("/edit/{id}", name="monitor_servicio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:MonitorServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MonitorServicio entity.');
        }

        $editForm = $this->createEditForm($entity);


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
    * Creates a form to edit a MonitorServicio entity.
    *
    * @param MonitorServicio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MonitorServicio $entity)
    {
        $form = $this->createForm(new MonitorServicioType(), $entity, array(
            'action' => $this->generateUrl('monitor_servicio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing MonitorServicio entity.
     *
     * @Route("/update/{id}", name="monitor_servicio_update")
     * @Method("PUT")
     * @Template("BcTicCamPanelesBiBundle:MonitorServicio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:MonitorServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MonitorServicio entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se grabaron correctamente.'
            );

            return $this->redirect($this->generateUrl('monitor_servicio_index', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Finds and displays a MonitorServicio entity.
     *
     * @Route("/show/{id}", name="monitor_servicio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:MonitorServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MonitorServicio entity.');
        }

        $csrf = $this->get('form.csrf_provider');


        return array(
            'entity'      => $entity,
            'csrf' => $csrf,
        );
    }
    /**
     * Deletes a MonitorServicio entity.
     *
     * @Route("/delete/{id}/{token}", name="monitor_servicio_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id, $token)
    {

        $csrf = $this->get('form.csrf_provider');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BcTicCamPanelesBiBundle:MonitorServicio')->find($id);

        if (!$entity) {
              throw $this->createNotFoundException('Unable to find MonitorServicio entity.');
        }

        if ($csrf->isCsrfTokenValid('entity'.$entity->getId(), $token)) {
            $em->remove($entity);
            $em->flush();

             $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se borraron correctamente.'
            );
        }


        return $this->redirect($this->generateUrl('monitor_servicio_index'));
    }

}
