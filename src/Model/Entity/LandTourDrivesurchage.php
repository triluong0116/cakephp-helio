<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LandTourDrivesurchage Entity
 *
 * @property int $id
 * @property int $land_tour_id
 * @property string $name
 * @property int $price_adult
 * @property int $price_crowd
 *
 * @property \App\Model\Entity\LandTour $land_tour
 */
class LandTourDrivesurchage extends Entity
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
