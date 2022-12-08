<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FanpagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FanpagesTable Test Case
 */
class FanpagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FanpagesTable
     */
    public $Fanpages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.fanpages',
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
        $config = TableRegistry::getTableLocator()->exists('Fanpages') ? [] : ['className' => FanpagesTable::class];
        $this->Fanpages = TableRegistry::getTableLocator()->get('Fanpages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Fanpages);

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
