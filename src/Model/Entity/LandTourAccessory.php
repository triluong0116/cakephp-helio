<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LandTourAccessory Entity
 *
 * @property int $id
 * @property int $land_tour_id
 * @property string $name
 * @property int $child_price
 * @property int $adult_price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\LandTour $land_tour
 * @property \App\Model\Entity\BookingLandtourAccessory[] $booking_landtour_accessories
 */
class LandTourAccessory extends Entity
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
