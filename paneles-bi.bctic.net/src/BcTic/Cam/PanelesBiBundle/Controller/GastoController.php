<?php

namespace BcTic\Cam\PanelesBiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BcTic\Cam\PanelesBiBundle\Entity\Gasto;
use BcTic\Cam\PanelesBiBundle\Form\GastoType;

/**
 * Gasto controller.
 *
 * @Route("/gasto")
 */
class GastoController extends Controller
{

    /**
     * Lists all Gasto entities.
     *
     * @Route("/index/{page}", name="gasto_index", defaults={ "page" = 1 })
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        //10 is the page size
        $entities = $em->getRepository('BcTicCamPanelesBiBundle:Gasto')->findBy(
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
     * Creates a new Gasto entity.
     *
     * @Route("/add", name="gasto_create")
     * @Method("POST")
     * @Template("BcTicCamPanelesBiBundle:Gasto:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Gasto();
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

            return $this->redirect($this->generateUrl('gasto_index', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Gasto entity.
    *
    * @param Gasto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Gasto $entity)
    {
        $form = $this->createForm(new GastoType(), $entity, array(
            'action' => $this->generateUrl('gasto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new Gasto entity.
     *
     * @Route("/new", name="gasto_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Gasto();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Gasto entity.
     *
     * @Route("/edit/{id}", name="gasto_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Gasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gasto entity.');
        }

        $editForm = $this->createEditForm($entity);


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
    * Creates a form to edit a Gasto entity.
    *
    * @param Gasto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Gasto $entity)
    {
        $form = $this->createForm(new GastoType(), $entity, array(
            'action' => $this->generateUrl('gasto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Gasto entity.
     *
     * @Route("/update/{id}", name="gasto_update")
     * @Method("PUT")
     * @Template("BcTicCamPanelesBiBundle:Gasto:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Gasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gasto entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se grabaron correctamente.'
            );

            return $this->redirect($this->generateUrl('gasto_index', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Finds and displays a Gasto entity.
     *
     * @Route("/show/{id}", name="gasto_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Gasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gasto entity.');
        }

        $csrf = $this->get('form.csrf_provider');


        return array(
            'entity'      => $entity,
            'csrf' => $csrf,
        );
    }
    /**
     * Deletes a Gasto entity.
     *
     * @Route("/delete/{id}/{token}", name="gasto_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id, $token)
    {

        $csrf = $this->get('form.csrf_provider');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BcTicCamPanelesBiBundle:Gasto')->find($id);

        if (!$entity) {
              throw $this->createNotFoundException('Unable to find Gasto entity.');
        }

        if ($csrf->isCsrfTokenValid('entity'.$entity->getId(), $token)) {
            $em->remove($entity);
            $em->flush();

             $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se borraron correctamente.'
            );
        }


        return $this->redirect($this->generateUrl('gasto_index'));
    }

}
