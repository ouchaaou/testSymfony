<?php

namespace AppBundle\Controller;


use AppBundle\Form\UserType;
use AppBundle\Form\EditType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\OtpBundle\Entity\Otpcode;


class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
           
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/edituser/{id}", name="edituser")
     */
    public function edituserAction($id,Request $request)
    {
        // 1) build the form
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $user = $repository->find($id);
        

        $form = $this->createForm(EditType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            if(isset($_POST['user']['password']) && $_POST['user']['password']!=='')
            {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }            
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('viewusers');
        }

        return $this->render(
            'registration/edituser.html.twig',
            array('form' => $form->createView(),'user'=>$user)
        );
    }


    /**
     * @Route("/verifymobno", name="verifymobno")
     */

    public function verifymobAction(Request $request)
    {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
        {

            $rndno=rand(10000,99999);
            $mobno=$_POST['mobileno'];
            $msg="Your Otp code is ".$rndno;

            $product = new Otpcode();
            $product->setCode($rndno);
            $product->setMobile($mobno);
            //$product->setDescription('Ergonomic and stylish!');
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($product);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            echo "success";
            exit();


                $parampro['uname'] = "otpforstaticking";
                $parampro['password'] = "OTP@1105520manage";
                $parampro['sender'] = "STKING";
                $parampro['receiver'] = $mobno;
                $parampro['route'] = "TA";
                $parampro['msgtype'] = "1";
                $parampro['sms'] = $msg;
                $sendsmspro = http_build_query($parampro);
            
                //$urlpro="http://manage.staticking.net/index.php/smsapi/httpapi/?".$sendsmspro;
    
                ///$ch=curl_init();
                //curl_setopt($ch, CURLOPT_URL, $urlpro); 
                ///curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                //$resultpro = curl_exec($ch);


        }
    }

    /**
     * @Route("/viewusersadmin", name="viewusersadmin")
     */
    public function viewusersAction()
    {
            
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $products = $repository->findBy(array('role'=>'customer'));
            
        //$repository->flush();

        return $this->render(
           'registration/viewusers.html.twig',
            array('products' => $products)
        );
    }

    /**
     * @Route("/viewsuppliers", name="viewsuppliers")
     */
    public function viewsuppliersAction()
    {
            
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $products = $repository->findBy(array('role'=>'supplier'));
            
        //$repository->flush();

        return $this->render(
           'registration/viewusers.html.twig',
            array('products' => $products)
        );
    }

    /**
     * @Route("/verifycode", name="verifycode")
     */
    public function verifycodeAction()
    {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
        {
            $code=$_POST['otpcode'];
            $mobno=$_POST['mobno'];


          $em = $this->getDoctrine();

          $product=$em->getRepository('AcmeOtpBundle:Otpcode');
          
          $query = $product->createQueryBuilder('p')
        ->where('p.code = :code and p.mobile=:mobile')
        ->setParameter('code', $code)
        ->setParameter('mobile', $mobno)
        //->orderBy('p.price', 'ASC')
        ->getQuery();      

        $products = $query->getOneOrNullResult();
        $proid= $products->getId();
        
        if(empty($products))
        {
            echo "Invalid Otp Code";
            exit();
        }
        else
        {
            $em=$this->getDoctrine()->getManager();
            $product2=$em->getRepository('AcmeOtpBundle:Otpcode')->find($proid);
            $em->remove($product2);
            $em->flush();
            echo "success";
            exit();
        }

        }

    }


}