<?php
/**
 * Created by PhpStorm.
 * User: mihaela.aldea
 * Date: 2015-05-19
 * Time: 17:42
 */

namespace AppBundle\Controller;


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
        return $this->render('catalog/category/info.html.twig',$phpinfo);
    }

    private function getCategories()
    {
        return array(
            1 => array('id' => 1, 'label' => 'Phones', 'parent' => null),
            2 => array('id' => 2, 'label' => 'Computers', 'parent' => null),
            3 => array('id' => 3, 'label' => 'Tablets', 'parent' => null),
            4 => array(
                'id'     => 4,
                'label'  => 'Desktop',
                'parent' => array(
                    'id'    => 2,
                    'label' => 'Computers'
                )
            ),
            5 => array(
                'id'     => 5,
                'label'  => 'Laptop',
                'parent' => array(
                    'id'    => 2,
                    'label' => 'Computers'
                )
            ),
        );
    }

    private function getCategory($categoryId)
    {
        $categories = $this->getCategories();
        if (empty($categories[$categoryId])) {
            throw new \Exception("CategoryId $categoryId does not exist");
        }

        return $categories[$categoryId];
    }

    private function buildTree(array $categories, $parentId = null)
    {
        $tree = array();
        foreach ($categories as $category) {
            $parentNode = !$parentId && !$category['parent'];
            $childNode  = $parentId && $category['parent']
                && $category['parent']['id'] === $parentId;
            if ($parentNode || $childNode) {
                $category['children']  = $this->buildTree($categories, $category['id']);
                $tree[$category['id']] = $category;
            }
        }

        return $tree;
    }

} 