<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VinhmsbookingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VinhmsbookingsTable Test Case
 */
class VinhmsbookingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VinhmsbookingsTable
     */
    public $Vinhmsbookings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vinhmsbookings',
        'app.vinhmsbooking_group_infors',
        'app.vinhmsbooking_rooms'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Vinhmsbookings') ? [] : ['className' => VinhmsbookingsTable::class];
        $this->Vinhmsbookings = TableRegistry::getTableLocator()->get('Vinhmsbookings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Vinhmsbookings);

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
