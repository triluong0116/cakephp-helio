<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CombosRoom Entity
 *
 * @property int $id
 * @property int $combo_id
 * @property int $room_id
 *
 * @property \App\Model\Entity\Combo $combo
 * @property \App\Model\Entity\Room $room
 */
class CombosRoom extends Entity
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
        'combo_id' => true,
        'room_id' => true
    ];
}
