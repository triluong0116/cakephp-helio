<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RoomPricesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RoomPricesTable Test Case
 */
class RoomPricesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RoomPricesTable
     */
    public $RoomPrices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.room_prices',
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
        $config = TableRegistry::getTableLocator()->exists('RoomPrices') ? [] : ['className' => RoomPricesTable::class];
        $this->RoomPrices = TableRegistry::getTableLocator()->get('RoomPrices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RoomPrices);

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
