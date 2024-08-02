<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/{id}', name: 'index', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS], defaults: ['id' => null])]
    public function index(?Category $category, CategoryRepository $repository, Request $request, EntityManagerInterface $em): Response
    {
        if (!$category) {
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie créé avec succès !');

            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/category/index.html.twig', [
            'categories' => $repository->findAll(),
            'categoryForm' => $form
        ]);

        // #[Route('/create', name: 'create', methods:['GET','POST'])]
        // public function create(Request $request, EntityManagerInterface $em): Response
        // {
        //     $category = new Category();
        //     $form = $this->createForm(CategoryType::class, $category);
        //     $form->handleRequest($request);

        //     if ($form->isSubmitted() && $form->isValid()) {

        //         $em->persist($category);
        //         $em->flush();

        //         $this->addFlash('success', 'Catégorie créé avec succès !');


        //         return $this->redirectToRoute('admin_category_index');
        //     }

        //     return $this->render('admin/category/create.html.twig', [
        //         'categoryForm' => $form,
        //     ]);
    }

    // #[Route('/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    // public function update(EntityManagerInterface $em, Request $request, Category $category): Response
    // {
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $em->flush();

    //         $this->addFlash('success', 'Catégorie modifié avec succès');

    //         return $this->redirectToRoute('admin_category_index');
    //     }

    //     return $this->render('admin/category/update.html.twig', [
    //         'categoryForm' => $form,
    //     ]);
    // }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, Category $category): Response
    {

        $em->remove($category);
        $em->flush();

        $this->addFlash('danger', 'Catégorie supprimé avec succès');

        return $this->redirectToRoute('admin_category_index');
    }
}
