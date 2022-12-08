<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SocketsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SocketsTable Test Case
 */
class SocketsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SocketsTable
     */
    public $Sockets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sockets',
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
        $config = TableRegistry::getTableLocator()->exists('Sockets') ? [] : ['className' => SocketsTable::class];
        $this->Sockets = TableRegistry::getTableLocator()->get('Sockets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sockets);

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
