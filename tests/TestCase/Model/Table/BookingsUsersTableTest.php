<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookingsUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookingsUsersTable Test Case
 */
class BookingsUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BookingsUsersTable
     */
    public $BookingsUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bookings_users',
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
        $config = TableRegistry::getTableLocator()->exists('BookingsUsers') ? [] : ['className' => BookingsUsersTable::class];
        $this->BookingsUsers = TableRegistry::getTableLocator()->get('BookingsUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BookingsUsers);

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
