<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserSharesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserSharesTable Test Case
 */
class UserSharesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserSharesTable
     */
    public $UserShares;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_shares',
        'app.users',
        'app.objects'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UserShares') ? [] : ['className' => UserSharesTable::class];
        $this->UserShares = TableRegistry::getTableLocator()->get('UserShares', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserShares);

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
