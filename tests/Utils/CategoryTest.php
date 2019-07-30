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
    
    
    /**
     * @dataProvider dataForAdminCategoryTreeOptionsList
     *
     * @param array $structuredCategories
     * @param array $categories
     */
    public function testAdminCategoryTreeOptionsList(array $structuredCategories, array $categories )
    {
    
        $this->mockedCategoryTreeAdminOptionsList->categories = $categories;
        $categories = $this->mockedCategoryTreeAdminOptionsList->buildTree();
        $this->assertSame($structuredCategories, $this->mockedCategoryTreeAdminOptionsList->getCategoryList($categories));
        
    }
    
    
    /**
     * @dataProvider dataForAdminCategoryTree
     *
     * @param string $string
     * @param array $array
     */
    public function testAdminCategoryTree(string $string, array $array)
    {
        
        $this->mockedCategoryTreeAdminList->categories = $array;
        
        $array = $this->mockedCategoryTreeAdminList->buildTree();
        
        $result = $this->mockedCategoryTreeAdminList->getCategoryList($array);
        
        $this->assertSame($string, $result);
        
    }
    
    
    public function dataForCategoryTreeFrontPage()
    {
        
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [ 'id' => 1, 'parent_id' => null, 'name' => 'Electronics' ],
                [ 'id' => 6, 'parent_id' => 1, 'name' => 'Computers', ],
                [ 'id' => 8, 'parent_id' => 6, 'name' => 'Laptops', ],
                [ 'id' => 14, 'parent_id' => 8, 'name' => 'HP', ],
            ],
            1
        ];
    
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [ 'id' => 1, 'parent_id' => null, 'name' => 'Electronics' ],
                [ 'id' => 6, 'parent_id' => 1, 'name' => 'Computers', ],
                [ 'id' => 8, 'parent_id' => 6, 'name' => 'Laptops', ],
                [ 'id' => 14, 'parent_id' => 8, 'name' => 'HP', ],
            ],
            6
        ];
    
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [ 'id' => 1, 'parent_id' => null, 'name' => 'Electronics' ],
                [ 'id' => 6, 'parent_id' => 1, 'name' => 'Computers', ],
                [ 'id' => 8, 'parent_id' => 6, 'name' => 'Laptops', ],
                [ 'id' => 14, 'parent_id' => 8, 'name' => 'HP', ],
            ],
            8
        ];
    
        yield [
            '<ul class="mr-5"><li><a href="/video-list/computers,6">Computers</a><ul class="mr-5"><li><a href="/video-list/laptops,8">Laptops</a><ul class="mr-5"><li><a href="/video-list/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                [ 'id' => 1, 'parent_id' => null, 'name' => 'Electronics' ],
                [ 'id' => 6, 'parent_id' => 1, 'name' => 'Computers', ],
                [ 'id' => 8, 'parent_id' => 6, 'name' => 'Laptops', ],
                [ 'id' => 14, 'parent_id' => 8, 'name' => 'HP', ],
            ],
            14
        ];
        
    }
    
    
    public function dataForAdminCategoryTreeOptionsList()
    {
        
        yield [
            [
                [ 'name' => 'Electronics', 'id' => 1 ],
                [ 'name' => '--Computers', 'id' => 6 ],
                [ 'name' => '----Laptops', 'id' => 8 ],
                [ 'name' => '------HP', 'id' => 14 ],
            ],
            [
                [ 'id' => 1, 'parent_id' => null, 'name' => 'Electronics' ],
                [ 'id' => 6, 'parent_id' => 1, 'name' => 'Computers', ],
                [ 'id' => 8, 'parent_id' => 6, 'name' => 'Laptops', ],
                [ 'id' => 14, 'parent_id' => 8, 'name' => 'HP', ],
            ],
        ];
        
    }
    
    
    public function dataForAdminCategoryTree()
    {
    
        yield [
            '<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>Toys <a href="/admin/edit-category/2">edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/delete-category/2">delete</a></li></ul>',
            [['id' => 2, 'parent_id' => null, 'name' => 'Toys']]
        ];
    
    }
    
}
