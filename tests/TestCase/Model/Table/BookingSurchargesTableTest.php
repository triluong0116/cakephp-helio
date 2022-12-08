<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookingSurchargesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookingSurchargesTable Test Case
 */
class BookingSurchargesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BookingSurchargesTable
     */
    public $BookingSurcharges;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.booking_surcharges',
        'app.bookings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BookingSurcharges') ? [] : ['className' => BookingSurchargesTable::class];
        $this->BookingSurcharges = TableRegistry::getTableLocator()->get('BookingSurcharges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BookingSurcharges);

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
