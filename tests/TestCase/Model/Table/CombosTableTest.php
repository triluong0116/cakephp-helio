<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CombosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CombosTable Test Case
 */
class CombosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CombosTable
     */
    public $Combos;

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
        $config = TableRegistry::getTableLocator()->exists('Combos') ? [] : ['className' => CombosTable::class];
        $this->Combos = TableRegistry::getTableLocator()->get('Combos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Combos);

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
