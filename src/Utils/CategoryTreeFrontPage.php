<?php

namespace App\Utils;

use App\Utils\AbstractClasses\AbstractCategoryTree;

class CategoryTreeFrontPage extends AbstractCategoryTree
{
    
    public function getCategoryList( array $categories )
    {
    
        $this->categoriesList .= '<ul class="mr-5">';
        
        foreach ( $categories as $category ) {
            
            $id   = $category['id'];
            $name = $category['name'];
            $link = $this->generator->generate('video_list', ['slug' => $name, 'id' => $id]);
            
            $this->categoriesList .= '<li>';
            $this->categoriesList .= '<a href="'.$link.'">'.$name.'</a>';
            
            if( ! empty( $category['child'] ) ) {
                $this->getCategoryList( $category['child'] );
            }
            
            $this->categoriesList .= '</li>';
            
        }
        
        $this->categoriesList .= '</ul>';
        
        return $this->categoriesList;
        
    }
    
}
