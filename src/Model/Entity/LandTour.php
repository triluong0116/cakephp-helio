<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LandTour Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string $caption
 * @property string $description
 * @property int $price
 * @property int $trippal_price
 * @property int $customer_price
 * @property float $promote
 * @property int $departure_id
 * @property int $destination_id
 * @property int $days
 * @property float $rating
 * @property string $thumbnail
 * @property string $media
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property int $status
 * @property string $term
 * @property string $organizer
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Departure $departure
 * @property \App\Model\Entity\Destination $destination
 */
class LandTour extends Entity
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
