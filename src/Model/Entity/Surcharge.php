<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Surcharge Entity
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $type
 * @property int|null $is_auto
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\HotelSurcharge[] $hotel_surcharges
 */
class Surcharge extends Entity
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
