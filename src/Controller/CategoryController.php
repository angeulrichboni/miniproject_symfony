<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/add', name: 'app_category_add')]
    public function add(EntityManagerInterface $entityManager, Request $request)
    {
        $category = new Categorie();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Categorie ajouté avec succès');
            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/add.html.twig', [
            'addCategory' => $form->createView(),
        ]);
    }

    #[Route('/category/edit/{id}', name: 'app_category_edit')]
    public function edit(EntityManagerInterface $entityManager, int $id, Request $request) : Response
    {
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(CategoryType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('success', 'Categorie modifié avec succès');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/edit.html.twig', [
            'editCategory' => $form->createView(),
        ]);
    }

    #[Route('/category/delete/{id}', name: 'app_category_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response
    {
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);
        $entityManager->remove($categorie);
        $entityManager->flush();
        $this->addFlash('success', 'Categorie supprimé avec succès');
        return $this->redirectToRoute('app_category');
    }
}
