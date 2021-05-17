<?php
namespace App\Controller;

use App\Entity\Images;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ImageController extends AbstractController
{

    /**
     * @Route("/image", name="image")
     */
    public function image()
    {

        $imageRepo = $this->getDoctrine()->getRepository(Images::class);
        $image = $imageRepo->findAll();



        return $this->render('user/image.html.twig', [
            "image" => $image
        ]);
    }

}
