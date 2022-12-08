<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookingLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookingLogsTable Test Case
 */
class BookingLogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BookingLogsTable
     */
    public $BookingLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.booking_logs',
        'app.users',
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
        $config = TableRegistry::getTableLocator()->exists('BookingLogs') ? [] : ['className' => BookingLogsTable::class];
        $this->BookingLogs = TableRegistry::getTableLocator()->get('BookingLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BookingLogs);

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
