<?php
namespace App\Test\TestCase\Controller;

use App\Controller\HotelsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\HotelsController Test Case
 */
class HotelsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.hotels',
        'app.locations',
        'app.rooms',
        'app.price_hotels',
        'app.categories',
        'app.combos',
        'app.hotels_categories',
        'app.combos_hotels_relations'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
