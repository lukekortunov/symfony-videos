<?php

namespace App\Tests\Controllers;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerCategoriesTest extends WebTestCase
{
    /** @var KernelBrowser */
    public $client;
    
    /** @var EntityManagerInterface */
    public $manager;
    
    
    public function setUp()
    {
        
        parent::setUp();
    
        $this->client = static::createClient();
        $this->client->disableReboot();
        
        $this->manager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->manager->beginTransaction();
        $this->manager->getConnection()->setAutoCommit(false);
        
    }
    
    
    public function tearDown()
    {
        
        parent::tearDown();
        
        $this->manager->rollback();
        $this->manager->close();
        $this->manager = null;
        
    }
    
    
    public function testTextOnPage()
    {
        
        $crawler = $this->client->request('GET', '/admin/categories');
    
        $this->assertSame('Categories list', $crawler->filter('h2')->text());
        $this->assertContains('edit', $this->client->getResponse()->getContent());
        $this->assertContains('delete', $this->client->getResponse()->getContent());
    
    }
    
    
    public function testNumberOfItems()
    {
    
        $crawler = $this->client->request('GET', '/admin/categories');
        $this->assertCount(56, $crawler->filter('option'));
        
    }
    
    
    public function testNewCategory()
    {
        
        $crawler = $this->client->request('GET', '/admin/categories');
        
        $form = $crawler->selectButton('Add')->form([
            'category[parent]' => 1,
            'category[name]'   => 'Other stuff'
        ]);
        
        $this->client->submit($form);
        
        $category = $this->manager->getRepository(Category::class)->findOneBy([ 'name' => 'Other stuff' ]);
        
        $this->assertNotNull( $category );
        
        $this->assertSame( 'Other stuff', $category->getName() );
        
    }
    
    
    public function testEditCategory()
    {
        
        $crawler = $this->client->request('GET', '/admin/edit-category/1');
        
        $form = $crawler->selectButton('Save')->form([
            'category[parent]' => 0,
            'category[name]'   => 'Bad',
        ]);
        
        $this->client->submit($form);
        
        $category = $this->manager->getRepository(Category::class)->find(1);
        
        $this->assertNotNull( $category );
        
        $this->assertSame( 'Bad', $category->getName() );
        
    }
    
    
    public function testDeleteCategory()
    {
        
        $crawler = $this->client->request( 'GET', '/admin/delete-category/1' );
        
        $category = $this->manager->getRepository(Category::class)->find(1);
        
        $this->assertNull($category);
        
    }
    
}
