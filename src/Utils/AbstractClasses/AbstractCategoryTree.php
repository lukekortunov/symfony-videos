<?php

namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractCategoryTree
{
    
    protected static $connection;
    
    private $manager;
    
    private $generator;
    
    public $categories;
    
    public function __construct(EntityManagerInterface $manager, UrlGeneratorInterface $generator)
    {
    
        $this->manager    = $manager;
        
        $this->generator  = $generator;
        
        $this->categories = $this->getCategories();
    
    }
    
    abstract public function getCategoryList( array $categories );
    
    private function getCategories()
    {
        
        if(self::$connection) return self::$connection;
        
        $conn = $this->manager->getConnection();
        
        $sql = "SELECT * FROM categories";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        
        return self::$connection = $stmt->fetchAll();
        
    }
    
}
