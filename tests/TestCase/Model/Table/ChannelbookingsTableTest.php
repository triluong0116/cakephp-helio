<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChannelbookingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChannelbookingsTable Test Case
 */
class ChannelbookingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChannelbookingsTable
     */
    public $Channelbookings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.channelbookings',
        'app.users',
        'app.sales',
        'app.accountants',
        'app.hotels',
        'app.channelbooking_rooms'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Channelbookings') ? [] : ['className' => ChannelbookingsTable::class];
        $this->Channelbookings = TableRegistry::getTableLocator()->get('Channelbookings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Channelbookings);

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
