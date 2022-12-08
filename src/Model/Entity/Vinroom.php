<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Vinroom Entity
 *
 * @property int $id
 * @property string $vin_code
 * @property int $trippal_price
 * @property int $customer_price
 * @property int $hotel_id
 *
 * @property \App\Model\Entity\Hotel $hotel
 */
class Vinroom extends Entity
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
