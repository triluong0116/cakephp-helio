<?php

namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

class SluggableBehavior extends Behavior {

    protected $_defaultConfig = [
        'field' => 'title',
        'slug' => 'slug',
        'replacement' => '-',
        'maxLength' => null
    ];

    public function slug(Entity $entity) {
        $config = $this->getConfig();
        $value = $entity->get($config['field']);
        $slug = $this->slugify($value);
        $slug_unique = $this->_uniqueSlug($entity, $slug, '-');
        $entity->set($config['slug'], $slug_unique);
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options) {
        $this->slug($entity);
    }

    private function slugify($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
    
    
    /**
     * Returns a unique slug.
     *
     * @param \Cake\ORM\Entity $entity Entity.
     * @param string $slug Slug.
     * @param string $separator Separator.
     * @return string Unique slug.
     */
    protected function _uniqueSlug(Entity $entity, $slug, $separator)
    {
        /** @var string $primaryKey */
        $primaryKey = $this->_table->getPrimaryKey();
        $field = $this->_table->aliasField($this->getConfig('slug'));        
        $conditions = $this->_conditions($entity, $slug);

        $i = 0;
        $suffix = '';
        $length = $this->getConfig('maxLength');
        while ($this->_table->exists($conditions)) {
            $i++;
            $suffix = $separator . $i;
            if ($length && $length < mb_strlen($slug . $suffix)) {
                $slug = mb_substr($slug, 0, $length - mb_strlen($suffix));
            }
            $conditions[$field] = $slug . $suffix;
        }
        return $slug . $suffix;
    }
    
    /**
     * Builds the conditions
     *
     * @param \Cake\ORM\Entity $entity Entity.
     * @param string $slug Slug
     * @return array
     */
    protected function _conditions($entity, $slug)
    {
        /** @var string $primaryKey */
        $primaryKey = $this->_table->getPrimaryKey();
        $field = $this->_table->aliasField($this->getConfig('slug'));

        $conditions = [$field => $slug];        

        if ($id = $entity->{$primaryKey}) {
            $conditions['NOT'][$this->_table->aliasField($primaryKey)] = $id;
        }

        return $conditions;
    }

}
