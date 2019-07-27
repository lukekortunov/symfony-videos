<?php

namespace App\Utils;

use App\Twig\AppExtension;
use App\Utils\AbstractClasses\AbstractCategoryTree;

class CategoryTreeAdminList extends AbstractCategoryTree
{
    
    /** @var AppExtension */
    public $slugify;
    
    
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
        
        $this->categoriesList .= '<ul class="fa-ul text-left">';
        
        foreach ( $categories as $category ) {
            
            $id   = $category['id'];
            $name = $category['name'];
    
            $editLink   = $this->generator->generate( 'admin_category_edit', [ 'id' => $id ] );
            $deleteLink = $this->generator->generate( 'admin_category_delete', [ 'id' => $id ] );
            
            $this->categoriesList .= '<li>';
            $this->categoriesList .= '<i class="fa-li fa fa-arrow-right"></i>'.$name;
            $this->categoriesList .= ' <a href="'.$editLink.'">edit</a>';
            $this->categoriesList .= ' <a onclick="return confirm(\'Are you sure?\');" href="'.$deleteLink.'">delete</a>';
            
            if( ! empty( $category['children'] ) ) {
                $this->getCategoryList( $category['children'] );
            }
            
            $this->categoriesList .= '</li>';
            
        }
        
        $this->categoriesList .= '</ul>';
        
        return $this->categoriesList;
        
    }
    
}
