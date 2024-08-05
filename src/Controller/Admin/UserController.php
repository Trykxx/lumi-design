<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_SUPER_ADMIN')]
#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractController
{
    #[Route('/creer-admin', name: 'new')]
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Assign the ROLE_ADMIN role
            $user->setRoles(['ROLE_ADMIN']);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur administrateur créé avec succès.');

            return $this->redirectToRoute('admin_dashboard_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'adminUserForm' => $form
        ]);
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, UserRepository $repository): Response
    {
        $users = $repository->PaginateUsersWithRoleUser($request->query->getInt('page', 1));

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/detail/{id}', name: 'show', methods: ['GET'])]
    public function show(UserRepository $repository, int $id): Response
    {
        $user = $repository->findOneById($id);

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/modifier/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(EntityManagerInterface $em, Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('plainPassword')->getData();
            if ($password) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            }

            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/update.html.twig', [
            'userForm' => $form,
        ]);
    }
}
