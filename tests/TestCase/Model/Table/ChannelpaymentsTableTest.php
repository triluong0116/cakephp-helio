<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChannelpaymentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChannelpaymentsTable Test Case
 */
class ChannelpaymentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChannelpaymentsTable
     */
    public $Channelpayments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.channelpayments',
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
        $config = TableRegistry::getTableLocator()->exists('Channelpayments') ? [] : ['className' => ChannelpaymentsTable::class];
        $this->Channelpayments = TableRegistry::getTableLocator()->get('Channelpayments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Channelpayments);

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
