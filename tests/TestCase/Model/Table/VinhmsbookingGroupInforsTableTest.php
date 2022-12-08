<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VinhmsbookingGroupInforsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VinhmsbookingGroupInforsTable Test Case
 */
class VinhmsbookingGroupInforsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VinhmsbookingGroupInforsTable
     */
    public $VinhmsbookingGroupInfors;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vinhmsbooking_group_infors',
        'app.vinhmsbookings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('VinhmsbookingGroupInfors') ? [] : ['className' => VinhmsbookingGroupInforsTable::class];
        $this->VinhmsbookingGroupInfors = TableRegistry::getTableLocator()->get('VinhmsbookingGroupInfors', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VinhmsbookingGroupInfors);

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
