<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChannelrateplanesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChannelrateplanesTable Test Case
 */
class ChannelrateplanesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChannelrateplanesTable
     */
    public $Channelrateplanes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.channelrateplanes',
        'app.channel_rooms'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Channelrateplanes') ? [] : ['className' => ChannelrateplanesTable::class];
        $this->Channelrateplanes = TableRegistry::getTableLocator()->get('Channelrateplanes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Channelrateplanes);

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
