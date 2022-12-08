<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VinroomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VinroomsTable Test Case
 */
class VinroomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VinroomsTable
     */
    public $Vinrooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vinrooms',
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
        $config = TableRegistry::getTableLocator()->exists('Vinrooms') ? [] : ['className' => VinroomsTable::class];
        $this->Vinrooms = TableRegistry::getTableLocator()->get('Vinrooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Vinrooms);

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
