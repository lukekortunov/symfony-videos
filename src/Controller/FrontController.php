<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Category;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    
    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    
    
    /**
     * @Route("/video-list/{slug},{id}/{page}", defaults={"page" : "1"}, name="video_list")
     *
     * @param int $id
     * @param int $page
     * @param CategoryTreeFrontPage $categories
     *
     * @return Response
     */
    public function videoList(int $id, int $page,  CategoryTreeFrontPage $categories)
    {
        
        $categories->getCategoryListAndParent( $id );
    
        $videos = $this->getDoctrine()
                       ->getRepository(Video::class)
                       ->findAllPaginated($page);
        
        return $this->render('front/videolist.html.twig', [
            'subcategories' => $categories,
            'videos'        => $videos,
        ]);
        
    }
    
    
    /**
     * @Route("/video-details", name="video_details")
     */
    public function videoDetails()
    {
        return $this->render('front/video_details.html.twig');
    }
    
    
    /**
     * @Route("/search-results", name="search_results", methods={"POST"})
     */
    public function searchResults()
    {
        
        return $this->render( 'front/search_results.html.twig' );
        
    }
    
    
    /**
     * @Route("/pricing", name="pricing")
     */
    public function pricing()
    {
        
        return $this->render('front/pricing.html.twig');
        
    }
    
    
    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        
        return $this->render('front/register.html.twig');
        
    }
    
    
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        
        return $this->render('front/login.html.twig');
        
    }
    
    
    /**
     * @Route("/payment", name="payment")
     */
    public function payment()
    {
        
        return $this->render( 'front/payment.html.twig' );
        
    }
    
    
    /**
     * Render menu
     *
     * @return Response
     */
    public function mainCategories()
    {
        
        $parentCategories = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent' => null], ['name' => 'ASC']);
        
        return $this->render('front/_main_categories.html.twig', [
            'categories' => $parentCategories
        ]);
        
    }
    
}
