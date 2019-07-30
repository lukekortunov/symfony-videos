<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Entity\Category;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Exception;

class VideoFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        
        foreach( $this->getVideoData() as [$title, $path, $categoryID] ) {
            
            try {
                
                $duration = random_int( 10, 300 );
                
                $category = $manager->getRepository(Category::class)->find($categoryID);
    
                $video = new Video;
                $video->setTitle($title);
                $video->setPath('https://player.vimeo.com/video/'.$path);
                $video->setCategory($category);
                $video->setDuration($duration);
    
                $manager->persist($video);
                
            } catch (Exception $e) {
            
                // Log exception
            
            }

        }
        
        $manager->flush();
        
    }
    
    
    private function getVideoData()
    {
        
        return [
            ['Movies 1', 289729765, 4],
            ['Movies 2', 289729765, 4],
            ['Movies 3', 289729765, 4],
            ['Movies 4', 289729765, 4],
            ['Movies 5', 289729765, 4],
            ['Movies 6', 289729765, 4],
            ['Movies 7', 289729765, 4],
            ['Movies 8', 289729765, 4],
            ['Movies 9', 289729765, 4],
            
            ['Movies 1', 289729765, 17],
            ['Movies 1', 289729765, 17],
            ['Movies 1', 289729765, 17],
            
            ['Movies 1', 289729765, 19],
            ['Movies 1', 289729765, 19],
            
            ['Movies 1', 289729765, 20],
        ];
        
    }
    
}
