<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Room Entity
 *
 * @property int $id
 * @property int $hotel_id
 * @property string $name
 * @property string $slug
 * @property float $area
 * @property int $num_bed
 * @property int $num_adult
 * @property int $num_children
 * @property string $thumbnail
 * @property string $media
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Hotel $hotel
 * @property \App\Model\Entity\PriceRoom[] $price_rooms
 * @property \App\Model\Entity\Combo[] $combos
 * @property \App\Model\Entity\Category[] $categories
 */
class Room extends Entity
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
