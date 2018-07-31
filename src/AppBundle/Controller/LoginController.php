<?php
/**
 * Created by PhpStorm.
 * User: Mindaugas
 * Date: 07/27/18
 * Time: 17:58
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LoginController
 * @package AppBundle\Controller
 */
class LoginController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if ($this->get('session')->get('oAuthToken')) {
           return  $this->redirectToRoute('issues');
        }
        return $this->render('@App/security/index.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        $this->get('session')->remove('user_id');
        $this->get('session')->remove('oAuthToken');
        $this->get('session')->remove('email');
        $this->get('session')->remove('name');
    }
}