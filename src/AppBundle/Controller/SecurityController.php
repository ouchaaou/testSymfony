<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class SecurityController extends Controller
{
    /**
     * @Route("/", name="login")
     */
       public function loginAction(Request $request)
        {
       

        $authenticationUtils = $this->get('security.authentication_utils');
        //print_r($authenticationUtils);
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //print_r($error);
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        //$lastpass = $authenticationUtils->getLastPassword();

        

        //echo  $lastUsername;
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));


    }

    public function loginCheckAction()
    {
        
            //$session=$this->getUser()->getUsername();
            return $this->redirectToRoute('home');
            //print_r($session);
        
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        //session_destroy();

        return $this->redirectToRoute('login');


    }

}