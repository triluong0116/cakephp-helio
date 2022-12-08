<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CombosHotel Entity
 *
 * @property int $id
 * @property int $combo_id
 * @property int $hotel_id
 * @property int $days_attended
 *
 * @property \App\Model\Entity\Combo $combo
 * @property \App\Model\Entity\Hotel $hotel
 */
class CombosHotel extends Entity
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
        'hotel_id' => true,
        'days_attended' => true,
        'combo' => true,
        'hotel' => true
    ];
}
