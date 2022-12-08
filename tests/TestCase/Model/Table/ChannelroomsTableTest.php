<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChannelroomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChannelroomsTable Test Case
 */
class ChannelroomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChannelroomsTable
     */
    public $Channelrooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.channelrooms',
        'app.hotels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Channelrooms') ? [] : ['className' => ChannelroomsTable::class];
        $this->Channelrooms = TableRegistry::getTableLocator()->get('Channelrooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Channelrooms);

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
