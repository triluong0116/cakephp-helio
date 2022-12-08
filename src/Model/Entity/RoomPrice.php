<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RoomPrice Entity
 *
 * @property int $id
 * @property int|null $room_id
 * @property \Cake\I18n\FrozenDate|null $room_day
 * @property int|null $price
 * @property int|null $available
 * @property int|null $type
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Room $room
 */
class RoomPrice extends Entity
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
        '*' => true,
        'id' => false
    ];
}
