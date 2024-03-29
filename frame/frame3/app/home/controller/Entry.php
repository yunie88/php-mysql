<?php
/**
 * Created by PhpStorm.
 * User: mazhenyu
 * Date: 28/07/2017
 * Time: 14:51
 */

namespace app\home\controller;

use houdunwang\core\Controller;
use houdunwang\model\Model;
use houdunwang\view\View;
use system\model\Arc;


class Entry extends Controller {
	public function index() {

		if ( IS_POST ) {
			//执行上传
			$data = $this->upload();
			p($data);
		}

		return View::make();
	}


	private function upload(){
		//创建上传目录
		$dir = 'upload/' . date( 'ymd' );
		is_dir( $dir ) || mkdir( $dir, 0777, true );
		//设置上传目录
		$storage = new \Upload\Storage\FileSystem( $dir );
		$file    = new \Upload\File( 'upload', $storage );
		//设置上传文件名字唯一
		// Optionally you can rename the file on upload
		$new_filename = uniqid();
		$file->setName( $new_filename );

		//设置上传类型和大小
		// Validate file upload
		// MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
		$file->addValidations( array(
			// Ensure file is of type "image/png"
			new \Upload\Validation\Mimetype( ['image/png','image/gif','image/jpeg']),

			//You can also add multi mimetype validation
			//new \Upload\Validation\Mimetype(array('image/png', 'image/gif'))

			// Ensure file is no larger than 5M (use "B", "K", M", or "G")
			new \Upload\Validation\Size( '2M' )
		) );

		//组合数组
		// Access data about the file that has been uploaded
		$data = array(
			'name'       => $file->getNameWithExtension(),
			'extension'  => $file->getExtension(),
			'mime'       => $file->getMimetype(),
			'size'       => $file->getSize(),
			'md5'        => $file->getMd5(),
			'dimensions' => $file->getDimensions()
		);


		// Try to upload file
		try {
			// Success!
			$file->upload();
			return $data;
		} catch ( \Exception $e ) {
			// Fail!
			$errors = $file->getErrors();
			p($errors);
			exit;
		}
	}

}