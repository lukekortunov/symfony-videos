<?php

namespace App\Utils;

use App\Twig\AppExtension;
use App\Utils\AbstractClasses\AbstractCategoryTree;

class CategoryTreeAdminOptionsList extends AbstractCategoryTree
{
    
    /** @var AppExtension */
    public $slugify;
    
    /**
     * Generate markup of categories list by provided multi-level array of \App\Entity\Category
     *
     * @param array $categories
     * @param int   $repeat
     *
     * @return array
     */
    public function getCategoryList( array $categories, int $repeat = 0 ): array
    {
        
        foreach ( $categories as $category ) {
            
            $this->categoriesList[] = [
                'name' => str_repeat('-', $repeat).$category['name'],
                'id'   => $category['id']
            ];

            if(!empty($category['children'])) {

                $repeat = $repeat + 2;
                $this->getCategoryList($category['children'], $repeat);
                $repeat = $repeat - 2;

            }

        }

        return $this->categoriesList;
        
    }
    
}
