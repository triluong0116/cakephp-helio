<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LandtourPaymentFeesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LandtourPaymentFeesTable Test Case
 */
class LandtourPaymentFeesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LandtourPaymentFeesTable
     */
    public $LandtourPaymentFees;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.landtour_payment_fees'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LandtourPaymentFees') ? [] : ['className' => LandtourPaymentFeesTable::class];
        $this->LandtourPaymentFees = TableRegistry::getTableLocator()->get('LandtourPaymentFees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LandtourPaymentFees);

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
