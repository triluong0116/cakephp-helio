<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DepositLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DepositLogsTable Test Case
 */
class DepositLogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DepositLogsTable
     */
    public $DepositLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.deposit_logs',
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
        $config = TableRegistry::getTableLocator()->exists('DepositLogs') ? [] : ['className' => DepositLogsTable::class];
        $this->DepositLogs = TableRegistry::getTableLocator()->get('DepositLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DepositLogs);

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
