<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VinhmsbookingRoomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VinhmsbookingRoomsTable Test Case
 */
class VinhmsbookingRoomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VinhmsbookingRoomsTable
     */
    public $VinhmsbookingRooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vinhmsbooking_rooms',
        'app.vinhmsbookings',
        'app.vinhms_packages',
        'app.vinhms_rooms',
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
        $config = TableRegistry::getTableLocator()->exists('VinhmsbookingRooms') ? [] : ['className' => VinhmsbookingRoomsTable::class];
        $this->VinhmsbookingRooms = TableRegistry::getTableLocator()->get('VinhmsbookingRooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VinhmsbookingRooms);

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
