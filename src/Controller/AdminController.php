<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptionsList;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    
    /**
     * @Route("/", name="admin_main_page")
     */
    public function index()
    {
        return $this->render('admin/my_profile.html.twig' );
    }
    
    
    /**
     * @Route("/categories", name="admin_categories", methods={"GET", "POST"})
     *
     * @param CategoryTreeAdminList $categories
     * @param Request $request
     *
     * @return Response
     */
    public function categories(CategoryTreeAdminList $categories, Request $request)
    {
    
        $tree = $categories->buildTree();
        $cats = $categories->getCategoryList( $tree );
    
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        
        $is_invalid = null;
        
        if( $this->saveCategory( $category, $form, $request ) ) {
            return $this->redirectToRoute('admin_categories' );
        } elseif( $request->isMethod('post') ) {
            $is_invalid = ' is-invalid';
        }

        return $this->render('admin/categories.html.twig', [
            'categories' => $cats,
            'form'       => $form->createView(),
            'is_invalid' => $is_invalid
        ] );
        
    }
    
    
    /**
     * @Route("/edit-category/{id}", name="admin_category_edit", methods={"POST", "GET"})
     *
     * @param Category $category
     * @param Request  $request
     *
     * @return Response
     */
    public function editCategory( Category $category, Request $request )
    {
        
        $form = $this->createForm(CategoryType::class, $category);
        $is_invalid = null;
        
        if( $this->saveCategory( $category, $form, $request ) ) {
            return $this->redirectToRoute('admin_categories');
        } elseif ($request->isMethod('post')) {
            $is_invalid = ' is-invalid';
        }
        
        return $this->render( 'admin/edit_category.html.twig', [
            'category'   => $category,
            'form'       => $form->createView(),
            'is_invalid' => $is_invalid
        ] );
        
    }
    
    
    /**
     * @Route("/delete-category/{id}", name="admin_category_delete")
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteCategory( int $id )
    {
    
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        
        if( ! empty( $category ) ) {
    
            $manager = $this->getDoctrine()->getManager();
            $manager->remove( $category );
            $manager->flush();
            
        }
        
        return $this->redirectToRoute( 'admin_categories' );
    
    }
    
    
    /**
     * @Route("/videos", name="admin_videos")
     */
    public function videos()
    {
        return $this->render('admin/videos.html.twig' );
    }
    
    
    /**
     * @Route("/upload", name="admin_upload")
     */
    public function upload()
    {
        return $this->render('admin/upload_video.html.twig' );
    }
    
    
    /**
     * @Route("/users", name="admin_users")
     */
    public function users()
    {
        return $this->render('admin/users.html.twig' );
    }
    
    
    /**
     * Render list of categories wrapped in <option>...</option> for forms usage
     *
     * @param CategoryTreeAdminOptionsList $categories
     * @param Category $editedCategory
     *
     * @return Response
     */
    public function getAllCategories(CategoryTreeAdminOptionsList $categories, Category $editedCategory = null)
    {
        
        $categories->getCategoryList( $categories->buildTree() );
        
        return $this->render('admin/_all_categories.html.twig', [
            'categories'     => $categories,
            'editedCategory' => $editedCategory
        ]);

    }
    
    
    /**
     * Save category data to database
     *
     * @param Category $category
     * @param FormInterface $form
     * @param Request $request
     *
     * @return bool
     */
    private function saveCategory(Category $category, FormInterface $form, Request $request)
    {
    
        $form->handleRequest($request);
        
        if( $form->isSubmitted() && $form->isValid() ) {
            
            $repository = $this->getDoctrine()->getRepository(Category::class);
            $parent = $repository->find($request->request->get('category')['parent']);
            
            $category->setName( $request->request->get('category')['name'] );
            $category->setParent( $parent );
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist( $category );
            $manager->flush();
    
            return true;
            
        }
    
        return false;
        
    }

}
