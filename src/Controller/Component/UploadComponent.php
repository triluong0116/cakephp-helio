<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

/**
 * Upload component
 */
class UploadComponent extends Component
{

    public function uploadSingle($data, $isCreateThumb = true)
    {
        $path = '';
        if (!empty($data)) {
            $dir = WWW_ROOT . 'files' . DS . 'uploads';
            $filename = trim($data['name']);
            $file_tmp_name = $data['tmp_name'];

            $path = '';
            if (is_uploaded_file($file_tmp_name)) {
                $infoUpload = getimagesize($file_tmp_name);
                if ($infoUpload) {
                    $mimeType = $infoUpload['mime'];
                    switch ($mimeType) {
                        case 'image/jpeg':
                        case 'image/gif':
                        case  'image/png':
                        case 'image/jpg':
                        {
                            $file_name = pathinfo($filename);
                            $newfilename = $this->to_slug($file_name['filename']);
                            $newname = $newfilename . '.' . $file_name['extension'];
                            $uploaded = $dir . DS . time() . '-' . $newname;
                            $path = 'files/uploads/' . time() . '-' . $newname;
                            move_uploaded_file($file_tmp_name, $uploaded);
                            chmod($uploaded, 0644);
                            $info = getimagesize($uploaded);
                            $image = null;
                            if ($info['mime'] == 'image/jpeg') {
                                $image = imagecreatefromjpeg($uploaded);
                            } elseif ($info['mime'] == 'image/gif') {
                                $image = imagecreatefromgif($uploaded);
                            } elseif ($info['mime'] == 'image/png') {
                                $image = imagecreatefrompng($uploaded);
                            } elseif ($info['mime'] == 'image/jpg') {
                                $image = imagecreatefromjpeg($uploaded);
                            }
                            if ($image != null) {
                                imagejpeg($image, $uploaded, 50);
                            }

                            if ($isCreateThumb) {
                                $this->createThumb($uploaded);
                            }
                            break;
                        }
                        default:
                        {
                            break;
                        }
                    }
                }
            }
        }
        $res = $path;
        return $res;
    }

    public function uploadFile($data)
    {
        $path = '';
        if (!empty($data)) {
            $dir = WWW_ROOT . 'files' . DS . 'uploads';
            $filename = trim($data['name']);
            $file_tmp_name = $data['tmp_name'];

            if (is_uploaded_file($file_tmp_name)) {
                $file_name = pathinfo($filename);
                $newfilename = $this->to_slug($file_name['filename']);
                $newname = $newfilename . '.' . $file_name['extension'];
                $uploaded = $dir . DS . time() . '-' . $newname;
                $path = 'files/uploads/' . time() . '-' . $newname;
                move_uploaded_file($file_tmp_name, $uploaded);
            }
        }
        $res = $path;
        return $res;
    }

    private function to_slug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/', 'a', $str);
        $str = preg_replace('/(??|??|???|???|???|??|???|???|???|???|???)/', 'e', $str);
        $str = preg_replace('/(??|??|???|???|??)/', 'i', $str);
        $str = preg_replace('/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/', 'o', $str);
        $str = preg_replace('/(??|??|???|???|??|??|???|???|???|???|???)/', 'u', $str);
        $str = preg_replace('/(???|??|???|???|???)/', 'y', $str);
        $str = preg_replace('/(??)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    private function createThumb($file)
    {
        $target_dir = WWW_ROOT . "files" . DS . "uploads";
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $file_name = pathinfo($file, PATHINFO_FILENAME);

        $target_file = $file_name . '_thumb.' . $extension;
        $old_file = $file;
        $thumb_file = $target_dir . DS . $target_file;

        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($old_file);
        $checkFileType = in_array($detectedType, $allowedTypes);
        if ($checkFileType) {
            $image = new \App\Utility\ImageResize();
            $image->load($old_file);
            $image->resizeToWidth(250);
            $image->save($thumb_file, IMAGETYPE_JPEG, 75, 0644);

        }
    }

}
