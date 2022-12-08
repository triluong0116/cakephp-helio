<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Hotel Entity
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $location_id
 * @property string $thumbnail
 * @property string $album
 * @property float $rating
 * @property string $map
 * @property string $media
 * @property string $hotline
 * @property string $term
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Location $location
 * @property \App\Model\Entity\Room[] $rooms
 * @property \App\Model\Entity\Category[] $categories
 */
class Hotel extends Entity
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

    protected $_virtual = ['medias'];

    protected function _getMedias()
    {
        return $this->media && !is_array($this->media) ? json_decode($this->media) : [];
    }
}
