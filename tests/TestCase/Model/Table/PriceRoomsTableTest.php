<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PriceRoomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PriceRoomsTable Test Case
 */
class PriceRoomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PriceRoomsTable
     */
    public $PriceRooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.price_rooms',
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
        $config = TableRegistry::getTableLocator()->exists('PriceRooms') ? [] : ['className' => PriceRoomsTable::class];
        $this->PriceRooms = TableRegistry::getTableLocator()->get('PriceRooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PriceRooms);

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
