<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    
    /** @var Generator */
    private $faker;
    
    /** @var integer - Better provide {number} % 11 === 0 */
    private $total = 55;
    
    /** @var array */
    private $categories = [];
    
    /** @var array */
    private $subCategories = [];
    
    
    public function load(ObjectManager $manager)
    {
        
        $this->faker = Factory::create();
        
        $this->generateUniqWords();
        
        $this->loadMainCategories($manager);
        
        $this->loadSubCategories($manager);
        
    }
    
    
    private function generateUniqWords()
    {
        
        $main  = (int) $this->total / 11;
        $words = [];
        
        for( $i = 0; $i < $this->total; $i++ ) {
            $words[] = $this->faker->unique()->word;
        }
        
        $words = array_map( function( string $word ) {
            return ucfirst( $word );
        }, $words );
    
        $this->categories    = array_slice( $words, 0, $main );
        $this->subCategories = array_slice( $words, $main );
        
    }
    
    
    private function loadMainCategories(ObjectManager $manager)
    {
    
        foreach( $this->categories as $cat ) {
            
            $category = new Category;
            $category->setName( $cat );
            $manager->persist( $category );
        
        }
    
        $manager->flush();
        
    }
    
    
    private function loadSubCategories(ObjectManager $manager)
    {
        
        $repository = $manager->getRepository(Category::class);
        $categories = $repository->findAll();
        
        
        foreach( $this->subCategories as $cat ) {
            
            /** @var Category $parent */
            $parent = $this->faker->randomElement( $categories );
            // $string = (string) uniqid();
            $string = $cat;
            
            $category = new Category;
            $category->setName( $string );
            $category->setParent( $parent );
            $manager->persist($category);
            
        }
    
        $manager->flush();
        
    }

}
