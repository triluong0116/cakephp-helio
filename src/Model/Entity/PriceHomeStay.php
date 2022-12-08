<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PriceHomeStay Entity
 *
 * @property int $id
 * @property int $home_stay_id
 * @property int $type
 * @property string $description
 * @property int $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\HomeStay $home_stay
 */
class PriceHomeStay extends Entity
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
        'home_stay_id' => true,
        'type' => true,
        'description' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'home_stay' => true
    ];
}
