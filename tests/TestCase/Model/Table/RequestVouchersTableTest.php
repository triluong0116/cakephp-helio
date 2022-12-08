<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RequestVouchersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RequestVouchersTable Test Case
 */
class RequestVouchersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RequestVouchersTable
     */
    public $RequestVouchers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.request_vouchers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RequestVouchers') ? [] : ['className' => RequestVouchersTable::class];
        $this->RequestVouchers = TableRegistry::getTableLocator()->get('RequestVouchers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RequestVouchers);

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
