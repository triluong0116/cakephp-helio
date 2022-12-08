<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WithdrawsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WithdrawsTable Test Case
 */
class WithdrawsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WithdrawsTable
     */
    public $Withdraws;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.withdraws',
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
        $config = TableRegistry::getTableLocator()->exists('Withdraws') ? [] : ['className' => WithdrawsTable::class];
        $this->Withdraws = TableRegistry::getTableLocator()->get('Withdraws', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Withdraws);

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
