<?php
/**
 * Created by PhpStorm.
 * User: mihaela.aldea
 * Date: 2015-05-19
 * Time: 17:42
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller
{
    public function indexAction()
    {
        return $this->render('catalog/index.html.twig');
    }

    public function listCategoriesAction()
    {
        $arguments = array('categories' => $this->getCategories());

        return $this->render('catalog/category/list.html.twig', $arguments);
    }

    public function showCategoryAction($categoryId)
    {
        $arguments = array('category' => $this->getCategory($categoryId));

        return $this->render('catalog/category/show.html.twig', $arguments);
    }

    public function editCategoryAction($categoryId)
    {
        $arguments = array(
            'category'         => $this->getCategory($categoryId),
            'parentCategories' => $this->getCategories(),
        );

        return $this->render('catalog/category/edit.html.twig', $arguments);
    }

    public function saveCategoryAction($categoryId)
    {
        $arguments = array('categoryId' => $categoryId);

        return $this->redirectToRoute('catalog_category_show', $arguments);
    }

    public function treeCategoriesAction(Request $request)
    {
        $arguments = array('categories' => $this->buildTree($this->getCategories()));
        if ($request->headers->get('Content-type') == "application/json") {
            return new JsonResponse($arguments);
        }

        return $this->render('catalog/category/tree.html.twig', $arguments);
    }

    public function phpInfoAction()
    {
        $phpinfo = phpinfo();

        return $this->render('catalog/category/info.html.twig', $phpinfo);
    }

    private function getCategories()
    {
        $entityManager      = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::REPOSITORY);
        $categories         = $categoryRepository->findAll();

        return $categories;
    }

    private function getCategory($categoryId)
    {
        $entityManager      = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::REPOSITORY);
        $category           = $categoryRepository->find($categoryId);
        if (!$category instanceof Category) {
            throw new \Exception("CategoryId $categoryId does not exist");
        }

        return $category;
    }

    private function buildTree(array $categories, $parentId = null)
    {
        $tree = array();
        foreach ($categories as $category) {
            $parentNode = !$parentId && !$category->getParentCategory();
            $childNode  = $parentId && $category->getParentCategory()
                && $category->getParentCategory()->getId() === $parentId;
            if ($parentNode || $childNode) {
                $children                 = $this->buildTree($categories, $category->getId());
                $tree[$category->getId()] = array(
                    'category' => $category,
                    'children' => $children
                );
            }
        }

        return $tree;
    }

} 