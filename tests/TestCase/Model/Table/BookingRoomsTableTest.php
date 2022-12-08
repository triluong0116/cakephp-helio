<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookingRoomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookingRoomsTable Test Case
 */
class BookingRoomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BookingRoomsTable
     */
    public $BookingRooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.booking_rooms',
        'app.bookings',
        'app.rooms'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BookingRooms') ? [] : ['className' => BookingRoomsTable::class];
        $this->BookingRooms = TableRegistry::getTableLocator()->get('BookingRooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BookingRooms);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
