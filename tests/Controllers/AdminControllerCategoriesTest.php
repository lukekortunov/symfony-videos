<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerCategoriesTest extends WebTestCase
{
    /** @var KernelBrowser */
    public $client;
    
    
    public function setUp()
    {
        
        parent::setUp();
    
        $this->client = static::createClient();
        
    }
    
    
    public function testTextOnPage()
    {
    
        
        $crawler = $this->client->request('GET', '/admin/categories');
    
        $this->assertSame('Categories list', $crawler->filter('h2')->text());
        $this->assertContains('edit', $this->client->getResponse()->getContent());
        $this->assertContains('delete', $this->client->getResponse()->getContent());
    
    }
    
}
