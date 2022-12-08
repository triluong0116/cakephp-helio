<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HomeStay Entity
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $location_id
 * @property string $address
 * @property string $description
 * @property float $rating
 * @property string $email
 * @property string $fb_content
 * @property string $thumbnail
 * @property string $media
 * @property string $hotline
 * @property string $term
 * @property int $homestay_type
 * @property int $room_type
 * @property int $num_bed_room
 * @property int $num_guest
 * @property int $num_bed
 * @property int $num_bath_room
 * @property int $price_agency
 * @property int $price_customer
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Location $location
 * @property \App\Model\Entity\PriceHomeStay[] $price_home_stays
 * @property \App\Model\Entity\Category[] $categories
 */
class HomeStay extends Entity
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
