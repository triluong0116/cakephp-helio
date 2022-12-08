<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LandTourSurcharge Entity
 *
 * @property int $id
 * @property int $land_tour_id
 * @property int $surcharge_type
 * @property int $price
 * @property string|null $options
 *
 * @property \App\Model\Entity\LandTour $land_tour
 */
class LandTourSurcharge extends Entity
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
        'land_tour_id' => true,
        'surcharge_type' => true,
        'price' => true,
        'options' => true,
        'land_tour' => true
    ];
}
