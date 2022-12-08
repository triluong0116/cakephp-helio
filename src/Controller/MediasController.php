<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Medias Controller
 *
 * @property \App\Model\Table\MediasTable $Medias
 *
 * @method \App\Model\Entity\Media[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MediasController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Upload');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['uploadForEditor']);
    }

    public function uploadAjax()
    {
        $this->autoRender = false;

        $response = ['success' => false, 'image' => ''];
        $data = $this->request->getData();
        if ($this->Auth->user()) {
            $imageObject = $this->Upload->uploadSingle($data['file']);
            $response['image'] = $imageObject;
            $response['success'] = true;
        } else {
            $response['message'] = 'Bạn chưa đăng nhập hoặc phiên đăng nhập đã hết hạn. Vui lòng đăng nhập để thực hiện chức năng này!';
        }
        $this->response->type('json');
        $this->response->body(json_encode($response, JSON_UNESCAPED_UNICODE));
        return $this->response;
    }

    public function uploadAjaxClone()
    {
        $this->autoRender = false;

        $response = ['success' => false, 'image' => ''];
        $data = $this->request->getData();
        $imageObject = $this->Upload->uploadSingle($data['file']);
        $response['image'] = $imageObject;
        $response['success'] = true;
        $this->response->type('json');
        $this->response->body(json_encode($response, JSON_UNESCAPED_UNICODE));
        return $this->response;
    }

    public function uploadForEditor()
    {
        $this->autoRender = false;

        $response = ['location' => ''];
        $data = $this->request->getData();
        if ($this->Auth->user()) {
            $imageObject = $this->Upload->uploadSingle($data['file']);
//            dd($imageObject);
            $response['location'] = $imageObject;
        } else {
            $response['message'] = 'Bạn chưa đăng nhập hoặc phiên đăng nhập đã hết hạn. Vui lòng đăng nhập để thực hiện chức năng này!';
        }
        $this->response->type('json');
        $this->response->body(json_encode($response, JSON_UNESCAPED_UNICODE));
        return $this->response;
    }

    public function deleteImageAjax()
    {
        $this->autoRender = false;

        $response = ['success' => true];

        $path = $this->request->getData('filePath');
        $file_location = WWW_ROOT . $path;
        if (file_exists($file_location)) {
            unlink($file_location);
        }
        $this->response->type('json');
        $this->response->body(json_encode($response, JSON_UNESCAPED_UNICODE));
        return $this->response;
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if ($this->Auth->user()) {
            return true;
        }

        $this->redirect(array('controller' => 'Users', 'action' => 'register'));
        return parent::isAuthorized($user);
    }
}
