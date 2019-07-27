<?php

namespace App\Utils;

use App\Utils\AbstractClasses\AbstractCategoryTree;

class CategoryTreeFrontPage extends AbstractCategoryTree
{
    
    public $mainParentID;
    
    public $mainParentName;
    
    public $currentCategoryName;
    
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
    
    
    public function getCategoryList( array $categories )
    {
    
        $this->categoriesList .= '<ul class="mr-5">';
        
        foreach ( $categories as $category ) {
            
            $id   = $category['id'];
            $name = $category['name'];
            $link = $this->generator->generate('video_list', ['slug' => $name, 'id' => $id]);
            
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
