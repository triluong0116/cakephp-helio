<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PriceRoom Entity
 *
 * @property int $id
 * @property int $room_id
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property int $price
 * @property int $created
 * @property int $modified
 *
 * @property \App\Model\Entity\Room $room
 */
class PriceRoom extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'room_id' => true,
        'start_date' => true,
        'end_date' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'room' => true
    ];
}
