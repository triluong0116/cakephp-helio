<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CombosRoomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CombosRoomsTable Test Case
 */
class CombosRoomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CombosRoomsTable
     */
    public $CombosRooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.combos_rooms',
        'app.combos',
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
        $config = TableRegistry::getTableLocator()->exists('CombosRooms') ? [] : ['className' => CombosRoomsTable::class];
        $this->CombosRooms = TableRegistry::getTableLocator()->get('CombosRooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CombosRooms);

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
