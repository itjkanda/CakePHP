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
		$this->set('postData', $this->getData());

		// 投稿時の処理
		if ($this->request->is('post')) {

			$data = $this->request->data;

			// sessionからユーザーidを拝借
			$data['Post']['user_id'] = $this->Session->read('user_id');

			$this->Post->save($data);

			// 投稿の重複防止
			$this->redirect('/posts/index');

		}

		// 返信
		if ($this->request->query('res')) {

			// パラメータから該当するユーザー名とメッセージ、postIdを取得
			$repId = $this->request->query('res');
			$postData = $this->Post->find('first',
				array(
					'fields' => array('Post.message', 'User.name'),
					'conditions' => array('Post.post_id' => $repId)
				)
			);

			$repMessage = '@' . $postData['User']['name'] . ' ' . $postData['Post']['message'];
			$this->set(compact('repMessage', 'repId'));

		}

		// 削除
		if ($this->request->query('delete')) {

			$deleteId = $this->request->query('delete');
			$this->Post->delete($deleteId);
			$this->Session->setFlash('投稿を削除しました');
			$this->redirect(array('action' => 'index'));

		}

	}

	public function view() {

		if ($this->request->query('id')) {

			$postId = $this->request->query('id');
			$postData = $this->Post->find('first',
				array(
					'fields' => 'User.name, User.picture, Post.created, Post.message',
					'conditions' => array(
						'Post.post_id' => $postId
					)
				)
			);

			$name = $postData['User']['name'];
			$message = $postData['Post']['message'];
			$date = $postData['Post']['created'];
			$picture = $postData['User']['picture'];
			$this->set(compact('name', 'message', 'date', 'picture'));

		}

	}

}
