<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VinpaymentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VinpaymentsTable Test Case
 */
class VinpaymentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VinpaymentsTable
     */
    public $Vinpayments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vinpayments',
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
        $config = TableRegistry::getTableLocator()->exists('Vinpayments') ? [] : ['className' => VinpaymentsTable::class];
        $this->Vinpayments = TableRegistry::getTableLocator()->get('Vinpayments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Vinpayments);

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
