<?php

namespace Luvaax\ThemeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * Default view for a node
     *
     * @Route("/cache/clear", name="luvaax_clear_cache")
     *
     * @param  Request $request
     * @param  mixed   $contentObject Object that match the given request
     *
     * @return Response
     */
    public function showAction(Request $request, $contentObject)
    {
        return $this->render('LuvaaxThemeBundle:default:node_details.html.twig', [
            'object' => $contentObject
        ]);
    }
}
