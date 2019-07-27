<?php

namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Doctrine\DBAL\DBALException;

abstract class AbstractCategoryTree
{
    
    protected static $connection;
    
    protected $manager;
    
    protected $generator;
    
    public $categories;
    
    public $categoriesList;
    
    /**
     * AbstractCategoryTree constructor.
     *
     * @param EntityManagerInterface $manager
     * @param UrlGeneratorInterface $generator
     *
     * @throws DBALException
     */
    public function __construct(EntityManagerInterface $manager, UrlGeneratorInterface $generator)
    {
    
        $this->manager    = $manager;
        
        $this->generator  = $generator;
        
        $this->categories = $this->getCategories();
    
    }
    
    
    /**
     * @param array $categories
     *
     * @return mixed
     */
    abstract public function getCategoryList( array $categories );
    
    
    /**
     * @param int|null $parentID
     *
     * @return array
     */
    public function buildTree( int $parentID = null ): array
    {
        
        $subcategories = [];
        
        foreach( $this->categories as $cat ) {
    
            if( (int) $cat['parent_id'] === (int) $parentID ) {
                
                $children = $this->buildTree( $cat['id'] );
                
                if( $children ) {
                    
                    $cat['children'] = $children;
                    
                }
                
                $subcategories[] = $cat;
                
            }
            
        }
        
        return $subcategories;
    
    }
    
    
    /**
     * @return mixed[]
     * @throws DBALException
     */
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
