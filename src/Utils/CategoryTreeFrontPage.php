<?php

namespace App\Utils;

use App\Twig\AppExtension;
use App\Utils\AbstractClasses\AbstractCategoryTree;

class CategoryTreeFrontPage extends AbstractCategoryTree
{
    
    /** @var AppExtension */
    public $slugify;
    
    /** @var int */
    public $mainParentID;
    
    /** @var string */
    public $mainParentName;
    
    /** @var string */
    public $currentCategoryName;
    
    
    /**
     * Find top-level category by provided ID of \App\Entity\Category
     *
     * @param int $id
     *
     * @return array
     */
    public function getMainParent( int $id )
    {
        
        $key = array_search( $id, array_column( $this->categories, 'id' ) );
        
        if( $this->categories[$key]['parent_id'] !== null ) {
            return $this->getMainParent( $this->categories[$key]['parent_id'] );
        }
        
        return [
            'id'    => $this->categories[$key]['id'],
            'name'  => $this->categories[$key]['name'],
        ];
        
    }
    
    
    /**
     * Obtain and format multi-level array of \App\Entity\Category by provided top-level category ID
     *
     * @param int $id
     *
     * @return string
     */
    public function getCategoryListAndParent( int $id ): string
    {
        
        $parentData = $this->getMainParent( $id );
    
        $this->mainParentID   = $parentData['id'];
        $this->mainParentName = $parentData['name'];
        
        $key = array_search( $id, array_column( $this->categories, 'id' ) );
        
        $this->currentCategoryName = $this->categories[$key]['name'];
        
        $categoriesArray = $this->buildTree( $this->mainParentID );
        
        return $this->getCategoryList( $categoriesArray );
        
    }
    
    
    /**
     * Generate markup of categories list by provided multi-level array of \App\Entity\Category
     *
     * @param array $categories
     *
     * @return string
     */
    public function getCategoryList( array $categories ): string
    {
    
        $this->slugify = new AppExtension;
        
        $this->categoriesList .= '<ul class="mr-5">';
        
        foreach ( $categories as $category ) {
            
            $id   = $category['id'];
            $name = $category['name'];
            $link = $this->generator->generate('video_list', [
                'slug' => $this->slugify->slugify($name),
                'id'   => $id
            ]);
            
            $this->categoriesList .= '<li>';
            $this->categoriesList .= '<a href="'.$link.'">'.$name.'</a>';
            
            if( ! empty( $category['children'] ) ) {
                $this->getCategoryList( $category['children'] );
            }
            
            $this->categoriesList .= '</li>';
            
        }
        
        $this->categoriesList .= '</ul>';
        
        return $this->categoriesList;
        
    }
    
}
