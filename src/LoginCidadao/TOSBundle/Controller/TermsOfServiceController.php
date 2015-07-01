<?php

namespace LoginCidadao\TOSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LoginCidadao\TOSBundle\Model\TOSInterface;
use LoginCidadao\TOSBundle\Entity\TermsOfService;
use LoginCidadao\TOSBundle\Form\TermsOfServiceType;

class TermsOfServiceController extends Controller
{

    /**
     * @Route("/terms", name="tos_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        $termsRepo = $this->getDoctrine()->getRepository('LoginCidadaoTOSBundle:TermsOfService');
        $terms     = $termsRepo->findAll();
        $latest    = $termsRepo->findLatestTerms();

        return compact('terms', 'latest');
    }

    /**
     * @Route("/terms/{id}", name="tos_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $termsRepo = $this->getDoctrine()->getRepository('LoginCidadaoTOSBundle:TermsOfService');
        $terms     = $termsRepo->find($id);
        $form      = $this->getEditForm($terms);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($terms);
            $em->flush();
        }

        return compact('terms', 'form');
    }

    /**
     * @Route("/terms/new", name="tos_new")
     * @Template()
     */
    public function newAction()
    {
        $terms = new TermsOfService();
        $form  = $this->getCreateForm($terms);

        return compact('form');
    }

    /**
     * @Route("/terms", name="tos_create")
     * @Method("POST")
     * @Template("LoginCidadaoTOSBundle:TermsOfService:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $terms = new TermsOfService();
        $form  = $this->getCreateForm($terms);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $terms->setAuthor($this->getUser());
            $em->persist($terms);
            $em->flush();

            return $this->redirectToRoute('tos_list');
        }

        return compact('form');
    }

    public function getCreateForm(TOSInterface $terms)
    {
        $form = $this->createForm(new TermsOfServiceType(), $terms,
            array(
            'action' => $this->generateUrl('tos_create'),
            'method' => 'POST',
            'translation_domain' => 'LoginCidadaoTOSBundle'
            )
        );
        $form->add('submit', 'submit',
            array(
            'label' => 'tos.form.create.label', 'attr' => array('class' => 'btn-success')
        ));
        return $form;
    }

    public function getEditForm(TOSInterface $terms)
    {
        $form = $this->createForm(new TermsOfServiceType(), $terms,
            array(
            'action' => $this->generateUrl('tos_edit',
                array('id' => $terms->getId())),
            'method' => 'POST',
            )
        );
        $form->add('submit', 'submit',
            array(
            'label' => 'Save', 'attr' => array('class' => 'btn-success')
        ));
        return $form;
    }
}
