<?php

namespace App\Controller;

use stdClass;
use Exception;
use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Editor\Type;
use App\Editor\Prop;
use function json_decode;
use function is_array;
use const JSON_THROW_ON_ERROR;

// TODO
class Import extends Controller {

	private const CONFIG = [
		'photoFrames' => [
			'frames' => [
				'file' => [
					'name' => 'file',
					'type' => 'file',
					// <директория картинок>;<как хранить картинки в БД и JSON>;<как отображать картинки на клиенте>
					'format' => '/media/photoFrames;{name};{name}.png'
				]
			]
		],
		// TODO: Доделать форматирование по умолчанию для оставшихся, проверить загрузку дат
		'stickers' => [
			'halloween' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/halloween;{name}'
				]
			],
			'cats' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/cats;{name}'
				]
			],
			'owls' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/cats;{name}'
				]
			],
			'penguins' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/penguins;{name}'
				]
			],
			'snowman' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/snowman;{name}'
				]
			],
			'leafs' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/leafs;{name}'
				]
			],
			'vip' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/vip;{name}'
				]
			],
			'bottles' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/bottles;{name}'
				]
			],
			'snowflakes' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/snowflakes;{name}'
				]
			],
			'clouds' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/clouds;{name}'
				]
			],
			'suns' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/suns;{name}'
				]
			],
			'hearts' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/hearts;{name}'
				]
			],
		],
		'collections' => [
			'types' => [
				'start' => [
					'name' => 'start',
					'type' => 'date',
					'format' => 'M-D'
				],
				'finish' => [
					'name' => 'finish',
					'type' => 'date',
					'format' => 'M-D'
				]
			],
			'winter' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'spring' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'summer' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'fall' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'new_year' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'valentines' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'november' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'birthday' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'mystic' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'honeymoon' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'sweet_tenderness' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'romantic_evening' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'indomitable_passion' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'holiday_romance' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
			'radiance_luxury' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name}'
				]
			],
		],
		'gifts' => [
			'events' => [
				'list' => [
					'dateStart' => [
						'name' => 'dateStart',
						'type' => 'date',
						'format' => 'D.M'
					],
					'dateFinish' => [
						'name' => 'dateFinish',
						'type' => 'date',
						'format' => 'D.M'
					]
				]
			]
		],
		'wedding' => [
			'bottles' => [
				'name' => [
					'name' => 'name',
					'type' => 'file',
					'format' => '/media/wedding/bottles;{name}'
				]
			],
			'rings' => [
				'name' => [
					'name' => 'name',
					'type' => 'file',
					'format' => '/media/wedding/rings;{name}'
				]
			],
			'tables' => [
				'file' => [
					'name' => 'file',
					'type' => 'file',
					'format' => '/media/wedding/tables;{name}'
				]
			],
			'bouquets' => [
				'name' => [
					'name' => 'name',
					'type' => 'file',
					'format' => '/media/wedding/bouquets;{name}'
				]
			],
		],
	];

	public function post(Request $request, Response $response): Response {
		try {
			$data = json_decode($request->psr()->getBody()->getContents(), false, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
			$this->app->db()->truncateData();
			unset($data->newGifts); // TODO: Временно исключаем
			$this->app->db()->mysqli()->query('SET sql_mode=\'NO_AUTO_VALUE_ON_ZERO\'');
			foreach ($data as $typeName => $typeDescriptor)
				$this->createType($typeName, $typeDescriptor, null, self::CONFIG[$typeName]);
			return $response->json([
				'success' => [
					'message' => 'Данные импортированы успешно'
				]
			]);
		} catch (Exception $ex) {
			return $response->json([
				'error' => [
					'message' => $ex->getMessage()
				]
			])->status(Status::BAD_REQUEST);
		}
	}

	private function createType(string $name, stdClass | array $descriptor, ?Type $parent, array $config): void {
		$type = new Type($name);
		if ($parent)
			$type->setParent($parent);
		$type->save();
		if (is_array($descriptor) && array_is_list($descriptor)) {
			$this->addEntities($type, $descriptor, $config ?? []);
		} else {
			foreach ($descriptor as $key => $value) {
				if ($value instanceof stdClass || is_array($value)) {
					$this->createType($key, $value, $type, $config[$key] ?? []);
				} else {
					$type->setProperty($key, $value);
				}
			}
			$type->save();
		}
	}

	private function addEntities(Type $type, array $entityArray, array $config = []): void {
		foreach ($entityArray as $entity) {
			foreach ($entity as $propName => $propValue) {
				if (!$type->getPropByName($propName)) {
					$type->addProp(new Prop($propName, match ($config[$propName] ? $config[$propName]['type'] : gettype($propValue)) {
						'boolean' => Prop::TYPE_BOOLEAN,
						'integer', 'double', 'float' => Prop::TYPE_NUMBER,
						'string' => Prop::TYPE_STRING,
						'array', 'object' => Prop::TYPE_JSON,
						'date' => Prop::TYPE_DATE,
						'file' => Prop::TYPE_FILE,
						default => Prop::TYPE_STRING
					}, false, $config[$propName] ? $config[$propName]['format'] : null));
					$type->save();
				}
			}
		}
		$insertColumns = array_map(fn (Prop $prop): string => $prop->getNameEscaped(), $type->getProps());
		$insertValues = [];
		foreach ($entityArray as $entity) {
			$insertValue = [];
			foreach ($insertColumns as $colName) {
				$insertValue[] = match (gettype($entity->{$colName})) {
					'boolean' => +$entity->{$colName},
					'integer', 'double', 'float' => $entity->{$colName},
					'string' => '\''.$this->app->db()->escape($entity->{$colName}).'\'',
					'array', 'object' => '\''.json_encode($entity->{$colName}, JSON_UNESCAPED_UNICODE).'\'',
					'NULL' => 'NULL'
				};
			}
			$insertValues[] = $insertValue;
		}
		$insertColumns = join(
			',',
			array_map(
				fn (string $name): string => "`{$name}`",
				$insertColumns
			)
		);
		$insertValues = join(
			',',
			array_map(
				fn (array $entity): string => '('.join(',', $entity).')',
				$insertValues
			)
		);
		$query = "INSERT INTO `e_{$type->getID()}` ({$insertColumns}) VALUES {$insertValues}";
		$this->app->db()->mysqli()->query($query);
	}
}
