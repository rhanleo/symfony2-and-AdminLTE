<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\crud;
use AppBundle\Form\crudType;


/**
 * crud controller.
 *
 * @Route("/crud")
 */
class crudController extends Controller
{

    /**
     * Lists all crud entities.
     *
     * @Route("/", name="crud")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:crud')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new crud entity.
     *
     * @Route("/", name="crud_create")
     * @Method("POST")
     * @Template("AppBundle:crud:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new crud();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a crud entity.
     *
     * @param crud $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(crud $entity)
    {
        $form = $this->createForm(new crudType(), $entity, array(
            'action' => $this->generateUrl('crud_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new crud entity.
     *
     * @Route("/new", name="crud_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new crud();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a crud entity.
     *
     * @Route("/{id}", name="crud_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:crud')->find($id);

        if (!$entity) {
            
            throw $this->createNotFoundException('Unable to find crud entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing crud entity.
     *
     * @Route("/{id}/edit", name="crud_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:crud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find crud entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a crud entity.
    *
    * @param crud $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(crud $entity)
    {
        $form = $this->createForm(new crudType(), $entity, array(
            'action' => $this->generateUrl('crud_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing crud entity.
     *
     * @Route("/{id}", name="crud_update")
     * @Method("PUT")
     * @Template("AppBundle:crud:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:crud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find crud entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('crud_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a crud entity.
     *
     * @Route("/{id}", name="crud_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:crud')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find crud entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud'));
    }

    /**
     * Creates a form to delete a crud entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crud_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
