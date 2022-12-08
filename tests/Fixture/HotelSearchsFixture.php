<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HotelSearchsFixture
 *
 */
class HotelSearchsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'NAME' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'location_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'room_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'price_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'room_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'single_day' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'WEEKDAY' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'weekend' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'day_name' => ['type' => 'string', 'length' => 9, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'price_day' => ['type' => 'biginteger', 'length' => 13, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'price_type' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_options' => [
            'engine' => null,
            'collation' => null
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
                'NAME' => 'Lorem ipsum dolor sit amet',
                'location_name' => 'Lorem ipsum dolor sit amet',
                'room_id' => 1,
                'price_id' => 1,
                'room_name' => 'Lorem ipsum dolor sit amet',
                'single_day' => '2019-12-26',
                'WEEKDAY' => 'Lorem ipsum dolor sit amet',
                'weekend' => 'Lorem ipsum dolor sit amet',
                'day_name' => 'Lorem i',
                'price_day' => 1,
                'price_type' => 1
            ],
        ];
        parent::init();
    }
}
