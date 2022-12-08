<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\CombosController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Api\CombosController Test Case
 */
class CombosControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.combos',
        'app.departures',
        'app.destinations',
        'app.bookings',
        'app.rooms',
        'app.hotels',
        'app.combos_rooms',
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
