<?php
    namespace AppBundle\Controller;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use AppBundle\Form\LoginForm;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


    class SecurityController extends Controller
    {

        public function loginAction(Request $request)
        {
            $authUtils = $this->get('security.authentication_utils');

            // get the login error if there is one
            $error = $authUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authUtils->getLastUsername();

            return $this->render('security/login.html.twig', array(
                'last_username' => $lastUsername,
                'error'         => $error,
            ));
        }
    }
