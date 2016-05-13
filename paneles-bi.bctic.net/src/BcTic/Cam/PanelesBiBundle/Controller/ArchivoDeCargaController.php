<?php

namespace BcTic\Cam\PanelesBiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BcTic\Cam\PanelesBiBundle\Entity\ArchivoDeCarga;
use BcTic\Cam\PanelesBiBundle\Form\ArchivoDeCargaType;

/**
 * ArchivoDeCarga controller.
 *
 * @Route("/archivo_de_carga")
 */
class ArchivoDeCargaController extends Controller
{

    /**
     * @Route("/{id}/download.php", name="archivo_de_carga_download", defaults={ "id" = -1 })
     * @Method("GET")
     * @Template()
     */
    public function downloadAction($id)
    {

      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('BcTicCamPanelesBiBundle:ArchivoDeCarga')->find($id);

      if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArchivoDeCarga entity.');
      }

      $filename = $entity->getUploadRootDir().$entity->getCreatedAt().'-'.$entity->getPath();

      // Generate response
      $response = new Response();
      // Set headers
      $response->headers->set('Cache-Control', 'private');
      $response->headers->set('Content-type', mime_content_type($filename));
      $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($entity->getPath()) . '";');
      $response->headers->set('Content-length', filesize($filename));
      // Send headers before outputting anything
      $response->sendHeaders();
      $response->setContent(readfile($filename));

      return $response;

    }        


    /**
     * Lists all ArchivoDeCarga entities.
     *
     * @Route("/index/{page}", name="archivo_de_carga_index", defaults={ "page" = 1 })
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        //10 is the page size
        $entities = $em->getRepository('BcTicCamPanelesBiBundle:ArchivoDeCarga')->findBy(
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
     * Creates a new ArchivoDeCarga entity.
     *
     * @Route("/add", name="archivo_de_carga_create")
     * @Method("POST")
     * @Template("BcTicCamPanelesBiBundle:ArchivoDeCarga:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ArchivoDeCarga();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);

            $entity->upload();

            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
              'Los datos se grabaron correctamente.'
            );

            return $this->redirect($this->generateUrl('archivo_de_carga_index', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a ArchivoDeCarga entity.
    *
    * @param ArchivoDeCarga $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ArchivoDeCarga $entity)
    {
        $form = $this->createForm(new ArchivoDeCargaType(), $entity, array(
            'action' => $this->generateUrl('archivo_de_carga_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new ArchivoDeCarga entity.
     *
     * @Route("/new", name="archivo_de_carga_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ArchivoDeCarga();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to edit a ArchivoDeCarga entity.
    *
    * @param ArchivoDeCarga $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArchivoDeCarga $entity)
    {
        $form = $this->createForm(new ArchivoDeCargaType(), $entity, array(
            'action' => $this->generateUrl('archivo_de_carga_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Finds and displays a ArchivoDeCarga entity.
     *
     * @Route("/show/{id}", name="archivo_de_carga_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BcTicCamPanelesBiBundle:ArchivoDeCarga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArchivoDeCarga entity.');
        }

        $csrf = $this->get('form.csrf_provider');


        return array(
            'entity'      => $entity,
            'csrf' => $csrf,
        );
    }


}
