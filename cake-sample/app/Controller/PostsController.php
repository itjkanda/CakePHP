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
class PostsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Post', 'User');

	public $paginate = array(
		'limit' => 5,
		'order' => array(
			'Post.created' => 'DESC'
		)
	);

	public function getData() {
		$data = $this->paginate('Post',
			array(
				'Post.post_id not' => null
			)
		);
		return $data;
	}

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	public function index() {

		// 投稿データの取得

		// paginateコンポーネント使わない版
		// $postData = $this->Post->find('all',
		// 	array(
		// 		'order' => array('Post.post_id DESC')
		// 	)
		// );

		// paginateコンポーネント使う版
		$this->set('postData', $this->getData());

		// 投稿時の処理
		if ($this->request->is('post')) {

			// データの取得
			$data = $this->request->data;

			// 送信データにsessionからユーザーidを拝借
			$data['Post']['user_id'] = $this->Session->read('user_id');

			$this->Post->save($data);

			// 投稿の重複防止
			$this->redirect('/posts/index');

		}

	}

}
