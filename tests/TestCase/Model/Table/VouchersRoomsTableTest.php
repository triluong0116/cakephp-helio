<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VouchersRoomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VouchersRoomsTable Test Case
 */
class VouchersRoomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VouchersRoomsTable
     */
    public $VouchersRooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vouchers_rooms',
        'app.vouchers',
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
        $config = TableRegistry::getTableLocator()->exists('VouchersRooms') ? [] : ['className' => VouchersRoomsTable::class];
        $this->VouchersRooms = TableRegistry::getTableLocator()->get('VouchersRooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VouchersRooms);

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
