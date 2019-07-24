<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


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
     */
    public function categories()
    {
        return $this->render('admin/categories.html.twig' );
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
