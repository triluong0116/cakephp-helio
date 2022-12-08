<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PromotesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PromotesTable Test Case
 */
class PromotesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PromotesTable
     */
    public $Promotes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.promotes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Promotes') ? [] : ['className' => PromotesTable::class];
        $this->Promotes = TableRegistry::getTableLocator()->get('Promotes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Promotes);

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
