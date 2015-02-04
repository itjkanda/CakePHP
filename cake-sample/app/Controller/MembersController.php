<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class MembersController extends AppController {

	var $components = array('Session');

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

	public function beforeFilter() {

	}

	public function index() {

	}

	// 会員登録系
	public function join() {
		$this->set('data', $this->Session->read('data'));
		// postされた場合
		if ($this->request->is('post')) {

			$data = $this->request->data;
			// モデルにデータをセット
			$this->Member->set($this->request->data);
			// 書き直し用にsessionに保存
			$this->Session->write('data', $data);

			// バリデーション
			if ($this->Member->validates()) {
				// 変数をsessionにセット
				$this->Session->write('data', $data);
				$this->redirect('/join/check');
			} else {
			}
		}
	}

	public function check() {

		// sessionからデータをセット
		$this->set('data', $this->Session->read('data'));
		if ($this->request->is('post')) {
			$this->redirect('/join/complete');
		}

	}

	public function complete() {

		if ($this->Session->check('data')) {

			$this->request->data = $this->Session->read('data');
			$this->Member->save($this->request->data);

		}
		// 画像のアップロード


	}

}
