<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\User;
use App\Form\RegisterType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserController extends AbstractController
{

    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $registerForm = $this->createForm(RegisterType::class, $user);
       // $images = 'favicon.png';

        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            //gestion des images :
            //récuperer les images trasmises
            $images = $registerForm->get('images')->getData();
            //on boucle les images avec foreach
            foreach ($images as $image){
                //generer un nouveau nom de fichier
                $fichier =md5(uniqid()) . '.' .$image->guessExtension();

                //copier le fichier dans le dossier public/uploads

                $image->move(
                  $this->getParameter('images_directory'),
                  $fichier
                );

                //On stock l'image dans la base de données (son nom)
                $img = new Images();
                $img ->setName($fichier);
                $user->addImage($img);



            }



            //hasher le mdp OBLIGATOIRE
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();

        }

        return $this->render("user/register.html.twig", [
            "registerForm" => $registerForm->createView(),
        //    'images' => $images

        ]);
    }







    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {

        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->joindre();



        return $this->render('user/profil.html.twig', [
            "user" => $user
        ]);
    }

    /**
     * @Route("profil/{id}/edit", name="profil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Images();
                $img->setName($fichier);
                $user->addImage($img);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/user/login", name="login")
     */
    public function login()
    {

        //$this->addFlash('success', 'Vous êtes connecté');


        return $this->render('user/login.html.twig', []);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        //symfony gère entierement cette route
    }

    public function accountInfo()
    {
        // allow any authenticated user - we don't care if they just
        // logged in, or are logged in via a remember me cookie
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        // ...
    }

    public function resetPassword()
    {
        // require the user to log in during *this* session
        // if they were only logged in via a remember me cookie, they
        // will be redirected to the login page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // ...
    }


    /**
     * @Route("/user/pass/modifier", name="users_pass_modifier")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            // On vérifie si les 2 mots de passe sont identiques
            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('profil');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('user/editpass.html.twig');
    }



}
