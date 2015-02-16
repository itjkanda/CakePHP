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
            /**
             * ページネート系は基本的にModelに紐づく処理なので
             * この処理もPostModelに書いて
             * PostModelから配列を返してもらうような処理にしましょう
             */
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

		if ($this->Auth->loggedIn()) {

                    /**
                     * Authコンポーネントが直接プライマリーキーを提供してくれる便利メソッドを持っているので
                     * それを使おうこれ以降のuserのidに関しては全て
                     * それとこのページはAuthでallowされて無いはずなので
                     * ログインチェックは不要です
                     * 後このuser_idってviewのどこで使ってるのかな？
                     * 
                     * $this->Session->read('Auth')['User']['user_id'];
                     * 
                     * これの書き方についてはちょっと調べて、考えてみます
                     */
			$userId = $this->Session->read('Auth')['User']['user_id'];
			$this->set('userId', $userId);

		}

		//  投稿時の処理
		if ($this->request->is('post')) {
                    /**
                     * 飛んできたクエリなどによってif文で制御わけるのはいいけど
                     * その結果indexのメソッドが肥大化してしまっています
                     * if文の中身をprivateメソッドにまとめたり
                     * 配列の生成をModelにやらせたりしてダイエットさせてください
                     * ただしsaveやリダイレクトの処理をprivateメソッドにさせるとindexをみただけでは何が行われているのかわからないため
                     * saveやリダイレクトなどのコアな処理はindexメソッド内部で行うこと
                     * 
                     */
			$data = $this->request->data;

			// sessionからユーザーidを拝借
			$data['Post']['user_id'] = $this->Session->read('Auth')['User']['user_id'];
			$this->Post->save($data);

			// 投稿の重複防止
                        /**
                         * リダイレクトしてここで処理が完了しているはずなので
                         * return をつけよう
                         * こうすることでこのコードを読んだ人が
                         * postの際の処理がここで完結していると一発でわかるようになります(redirectなど途中で処理が終わる際は全てreturn つけてあげましょう)
                         */
			$this->redirect('/posts/index');
                        

		}

		// 返信
		if ($this->request->query('res')) {

			// パラメータから該当するユーザー名とメッセージ、postIdを取得
			$repId = $this->request->query('res');
			$postData = $this->Post->getPostData($repId);

			$repMessage = '@' . $postData['User']['name'] . ' ' . $postData['Post']['message'];
			$this->set(compact('repMessage', 'repId'));

		}

		// 削除
		if ($this->request->query('delete') && $this->Auth->loggedIn()) {

			$deleteId = $this->request->query('delete');
			$postData = $this->Post->getPostData($deleteId);

			// 投稿者のIDと削除者のIDが一致していれば削除
			if ($postData['User']['user_id'] == $this->Session->read('Auth')['User']['user_id']) {

				$this->Post->delete($deleteId);
				$this->Session->setFlash('投稿を削除しました');
				$this->redirect(array('action' => 'index'));

			} else {

				echo '削除する権限がないんだお';

			}


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
                        
                        /**
                         * これpostDataでsetしてview側で展開してやればいいんじゃないかな
                         * $postData[Post][message]みたいな感じで
                         * 
                         * またpostDataにデータがなかった場合(デタラメなid打ち込まれた場合など)
                         * の処理を書かないとエラーになります
                         */
			$name = $postData['User']['name'];
			$message = $postData['Post']['message'];
			$date = $postData['Post']['created'];
			$picture = $postData['User']['picture'];
			$this->set(compact('name', 'message', 'date', 'picture'));

		}

	}

}
