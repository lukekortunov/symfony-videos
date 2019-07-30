<?php

namespace App\Tests\Utils;

use App\Twig\AppExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    
    protected $mockedCategoryTreeFrontPage;
    
    protected $mockedCategoryTreeAdminList;
    
    protected $mockedCategoryTreeAdminOptionsList;
    
    protected function setUp()
    {
        
        $kernel = self::bootKernel();
        
        $generator = $kernel->getContainer()->get('router');
        
        $testedClasses = [
            'CategoryTreeAdminList',
            'CategoryTreeAdminOptionsList',
            'CategoryTreeFrontPage',
        ];
        
        foreach( $testedClasses as $testedClass ) {
    
            $name = 'mocked' . $testedClass;
            
            
            $this->{$name} = $this->getMockBuilder('App\Utils\\'.$testedClass)
                                                      ->disableOriginalConstructor()
                                                      ->setMethods()
                                                      ->getMock();
    
            $this->{$name}->generator = $generator;
            
        }
        
    }
    
    
    /**
     * @dataProvider dataForCategoryTreeFrontPage
     *
     * @param string $string
     * @param array $array
     * @param int $id
     */
    public function testCategoryTreeFrontPage(string $string, array $array, int $id)
    {
    
        $this->mockedCategoryTreeFrontPage->categories = $array;
        $this->mockedCategoryTreeFrontPage->slugger    = new AppExtension;
        
        $mainParentId = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        
        $array = $this->mockedCategoryTreeFrontPage->buildTree($mainParentId);
        
        $result = $this->mockedCategoryTreeFrontPage->getCategoryList($array);
        
        $this->assertSame($string, $result);
    
    }
    
    
    public function dataForCategoryTreeFrontPage()
    {
        
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [
                    'id'        => 1,
                    'parent_id' => null,
                    'name'      => 'Electronics'
                ],
                [
                    'id'        => 6,
                    'parent_id' => 1,
                    'name'      => 'Computers',
                ],
                [
                    'id'        => 8,
                    'parent_id' => 6,
                    'name'      => 'Laptops',
                ],
                [
                    'id'        => 14,
                    'parent_id' => 8,
                    'name'      => 'HP',
                ],
            ],
            1
        ];
    
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [
                    'id'        => 1,
                    'parent_id' => null,
                    'name'      => 'Electronics'
                ],
                [
                    'id'        => 6,
                    'parent_id' => 1,
                    'name'      => 'Computers',
                ],
                [
                    'id'        => 8,
                    'parent_id' => 6,
                    'name'      => 'Laptops',
                ],
                [
                    'id'        => 14,
                    'parent_id' => 8,
                    'name'      => 'HP',
                ],
            ],
            6
        ];
    
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [
                    'id'        => 1,
                    'parent_id' => null,
                    'name'      => 'Electronics'
                ],
                [
                    'id'        => 6,
                    'parent_id' => 1,
                    'name'      => 'Computers',
                ],
                [
                    'id'        => 8,
                    'parent_id' => 6,
                    'name'      => 'Laptops',
                ],
                [
                    'id'        => 14,
                    'parent_id' => 8,
                    'name'      => 'HP',
                ],
            ],
            8
        ];
    
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [
                    'id'        => 1,
                    'parent_id' => null,
                    'name'      => 'Electronics'
                ],
                [
                    'id'        => 6,
                    'parent_id' => 1,
                    'name'      => 'Computers',
                ],
                [
                    'id'        => 8,
                    'parent_id' => 6,
                    'name'      => 'Laptops',
                ],
                [
                    'id'        => 14,
                    'parent_id' => 8,
                    'name'      => 'HP',
                ],
            ],
            14
        ];
        
    }
}
