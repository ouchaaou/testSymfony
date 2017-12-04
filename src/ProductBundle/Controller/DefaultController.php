<?php

namespace ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\ProductType;
use ProductBundle\Entity\Product;

class DefaultController extends Controller
{
    /**
     * @Route("/addproduct",name="addproduct")
     */
    public function indexAction(Request $request)
    {
    	$product = new Product();
        
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        $error="";
        if ($form->isSubmitted()) {
        		

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

           		 $mailbody=$product->getName()." Added on website";
             	  $message = \Swift_Message::newInstance();
                  $message->setSubject('New Product Added');
                  $message->setFrom('rajan1430@gmail.com');
                  $message->setTo("singhgp1430@gmail.com");
                  $message->setBody($mailbody);
                  $this->get('mailer')->send($message);

            $em->flush();
            return $this->redirectToRoute('viewproduct');
        	
        }

        return $this->render('ProductBundle:Default:add.html.twig',array('form' => $form->createView(),'error'=>$error,'title'=>'Add product'));
    }

     /**
     * @Route("/viewproduct",name="viewproduct")
     */
    public function viewAction()
    {
            
        $repository = $this->getDoctrine()->getRepository('ProductBundle:Product');

        $products = $repository->findAll();
            
        return $this->render(
           'ProductBundle:Default:index.html.twig',
            array('products' => $products)
        );
    }

    /**
     * @Route("/editproduct/{id}",name="editproduct")
     */
    public function editAction($id,Request $request)
    {

        $repository = $this->getDoctrine()->getRepository('ProductBundle:Product');

        $product = $repository->find($id);
       
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();       
            return $this->redirectToRoute('viewproduct');
        }

        return $this->render(
            'ProductBundle:Default:add.html.twig',
            array('form' => $form->createView(),'title'=>'Edit Product')
        );
    }

    /**
     * @Route("/deleteproduct/{id}",name="deleteproduct")
     */
     public function deleteAction(product $product)
      {
          if (!$product) {
              throw $this->createNotFoundException('No Product found');
          }

          $em = $this->getDoctrine()->getEntityManager();
          $em->remove($product);
          $em->flush();

          return $this->redirectToRoute('viewproduct');
      }  


}
