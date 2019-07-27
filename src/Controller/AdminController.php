<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\CategoryTreeAdminList;
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
     * @Route("/categories", name="admin_categories")
     *
     * @param CategoryTreeAdminList $categories
     *
     * @return Response
     */
    public function categories(CategoryTreeAdminList $categories)
    {
    
        $tree = $categories->buildTree();
        $cats = $categories->getCategoryList( $tree );

        return $this->render('admin/categories.html.twig', [
            'categories' => $cats
        ] );
        
    }
    
    
    /**
     * @Route("/edit-category", name="admin_category_edit")
     */
    public function editCategory()
    {
        return $this->render( 'admin/edit_category.html.twig' );
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
    
}
