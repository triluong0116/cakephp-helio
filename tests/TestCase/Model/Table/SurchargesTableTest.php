<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SurchargesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SurchargesTable Test Case
 */
class SurchargesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SurchargesTable
     */
    public $Surcharges;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.surcharges',
        'app.hotel_surcharges'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Surcharges') ? [] : ['className' => SurchargesTable::class];
        $this->Surcharges = TableRegistry::getTableLocator()->get('Surcharges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Surcharges);

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
}
