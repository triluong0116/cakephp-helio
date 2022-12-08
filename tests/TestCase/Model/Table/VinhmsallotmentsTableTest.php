<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VinhmsallotmentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VinhmsallotmentsTable Test Case
 */
class VinhmsallotmentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VinhmsallotmentsTable
     */
    public $Vinhmsallotments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vinhmsallotments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Vinhmsallotments') ? [] : ['className' => VinhmsallotmentsTable::class];
        $this->Vinhmsallotments = TableRegistry::getTableLocator()->get('Vinhmsallotments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Vinhmsallotments);

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
