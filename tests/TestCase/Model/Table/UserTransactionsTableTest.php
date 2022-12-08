<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserTransactionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserTransactionsTable Test Case
 */
class UserTransactionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserTransactionsTable
     */
    public $UserTransactions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_transactions',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UserTransactions') ? [] : ['className' => UserTransactionsTable::class];
        $this->UserTransactions = TableRegistry::getTableLocator()->get('UserTransactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserTransactions);

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
