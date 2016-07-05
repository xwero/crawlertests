<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class WebCrawlerControllerTest extends WebTestCase
{
    protected $client;
    // creates a new client for every test
    protected function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // for some reason first adds the twig path (AppBundle:WebCrawler:index)
        $this->assertContains('Welcome to the WebCrawler:index page', $crawler->first('h1')->text());
    }

    public function testTaskFormAllFieldsPresent()
    {
        $crawler = $this->client->request('GET', '/task');
        // find by css selector
        $this->assertEquals(1, $crawler->filter('#form_task')->count());
        // you could check the date selects if you want
        $this->assertEquals(1, $crawler->filter('#form_dueDate')->count());
        $this->assertEquals(1, $crawler->filter('#form_save')->count());
    }

    public function testTaskFormSubmitWithoutInput()
    {
        $crawler = $this->client->request('GET', '/task');
        // find by attribute name
        $form = $crawler->selectButton('form[save]')->form();

        $values = $form->getPhpValues();

        $crawlerSubmit = $this->client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $errorElement = $crawlerSubmit->filter('li');
        // count the errors
        $this->assertEquals(1, $errorElement->count());
        // check the message
        $this->assertContains('This value should not be blank.', $errorElement->first()->text());
    }

    public function testTaskFormSubmitIsLegal()
    {
        $crawler = $this->client->request('GET', '/task');

        $form = $crawler->selectButton('form[save]')->form();

        $form->setValues(array('form' => array(
            'task' => 'value',
        )));

        $this->client->submit($form);

        $crawlerSubmit = $this->client->followRedirect();

        $this->assertEquals(1, $crawlerSubmit->filter('h1')->count());

        $this->assertContains('Your task was saved', $crawlerSubmit->first('h1')->text());
    }

}
