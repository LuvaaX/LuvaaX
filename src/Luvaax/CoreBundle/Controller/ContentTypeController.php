<?php

namespace Luvaax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Luvaax\CoreBundle\Form\Type\ContentTypeType;
use Luvaax\CoreBundle\Model\ContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ContentTypeController extends Controller
{
    /**
     * Create new content type
     *
     * @param  Request $request
     *
     * @return Response
     * @Route("/admin/content-type/new", name="luvaax.new_content_type")
     */
    public function newAction(Request $request)
    {
        $contentType = new ContentType();
        $form = $this->createForm(ContentTypeType::class, $contentType);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('luvaax.core.entity_generator')->createContentType($contentType);
        }

        return $this->render('CoreBundle:form:content_type.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
