<?php

namespace App\Controller\Api;

use App\Editor\User;
use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use function \json_decode;

class Users extends Controller {

	public function get(Request $request, Response $response): Response {
		return $request->param('id') ? $response->json(User::getByID(+$request->param('id'))?->properties) : $response->json($this->app->db()->getUsers());
	}

	public function post(Request $request, Response $response): Response {
		try {
			$requestData = json_decode($request->psr()->getBody()->getContents(), true);
			if (User::getByName($requestData['name']))
				return $response->status(Status::BAD_REQUEST)->json([
					'error' => [
						'message' => "Пользователь с именем {$requestData['name']} уже существует"
					]
				]);
			(new User($requestData))->save();
			return $response->json([
				'success' => [
					'message' => 'Пользователь был успешно создан'
				]
			]);
		} catch (\Exception $ex) {
			return $response->json([
				'error' => [
					'message' => $ex->getMessage()
				]
			]);
		}
	}

	public function put(Request $request, Response $response): Response {
		$user = $this->find($request);
		if (!$user)
			return $response->status(Status::NOT_FOUND)->json([
				'error' => [
					'message' => "Пользователь с ID {$request->param('id')} не найден"
				]
			]);
		$user->properties = array_merge($user->properties, json_decode($request->psr()->getBody()->getContents(), true));
		$user->save();
		return $response->json([
			'error' => [
				'message' => "Пользователь с ID {$request->param('id')} был успешно обновлён"
			]
		]);
	}

	public function delete(Request $request, Response $response): Response {
		$this->find($request)?->delete();
		return $response->json([
			'success' => [
				'message' => "Пользователь с ID {$request->param('id')} был успешно удалён"
			]
		]);
	}

	private function find(Request $request): ?User {
		return $request->param('id') ? User::getByID(+$request->param('id')) : null;
	}
}
