<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChannelbookingRoomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChannelbookingRoomsTable Test Case
 */
class ChannelbookingRoomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChannelbookingRoomsTable
     */
    public $ChannelbookingRooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.channelbooking_rooms',
        'app.channelbookings',
        'app.channelrooms',
        'app.channelrateplanes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ChannelbookingRooms') ? [] : ['className' => ChannelbookingRoomsTable::class];
        $this->ChannelbookingRooms = TableRegistry::getTableLocator()->get('ChannelbookingRooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ChannelbookingRooms);

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
