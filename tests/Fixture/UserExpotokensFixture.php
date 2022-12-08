<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserExpotokensFixture
 *
 */
class UserExpotokensFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'clientId' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8mb4_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'expo_push_token' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8mb4_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_bin'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'clientId' => 'Lorem ipsum dolor sit amet',
                'user_id' => 1,
                'expo_push_token' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-06-08 11:05:13',
                'modified' => '2022-06-08 11:05:13'
            ],
        ];
        parent::init();
    }
}
