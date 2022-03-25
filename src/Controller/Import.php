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
		'stickers' => [
			'halloween' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/halloween;{name};{name}.png'
				]
			],
			'cats' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/cats;{name};{name}.png'
				]
			],
			'owls' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/cats;{name};{name}.png'
				]
			],
			'penguins' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/penguins;{name};{name}.png'
				]
			],
			'snowman' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/snowman;{name};{name}.png'
				]
			],
			'leafs' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/leafs;{name};{name}.png'
				]
			],
			'vip' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/vip;{name};{name}.png'
				]
			],
			'bottles' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/bottles;{name};{name}.png'
				]
			],
			'snowflakes' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/snowflakes;{name};{name}.png'
				]
			],
			'clouds' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/clouds;{name};{name}.png'
				]
			],
			'suns' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/suns;{name};{name}.png'
				]
			],
			'hearts' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/stickers/hearts;{name};{name}.png'
				]
			],
		],
		'collections' => [
			'types' => [
				'start' => [
					'name' => 'start',
					'type' => 'date',
					'format' => 'm-d'
				],
				'finish' => [
					'name' => 'finish',
					'type' => 'date',
					'format' => 'm-d'
				]
			],
			'winter' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'spring' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'summer' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'fall' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'new_year' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'valentines' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'november' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'birthday' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'mystic' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'honeymoon' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'sweet_tenderness' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'romantic_evening' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'indomitable_passion' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'holiday_romance' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
			'radiance_luxury' => [
				'image' => [
					'name' => 'image',
					'type' => 'file',
					'format' => '/media/collections;{name};{name}.png'
				]
			],
		],
		'gifts' => [
			'events' => [
				'list' => [
					'dateStart' => [
						'name' => 'dateStart',
						'type' => 'date',
						'format' => 'd.m'
					],
					'dateFinish' => [
						'name' => 'dateFinish',
						'type' => 'date',
						'format' => 'd.m'
					]
				]
			]
		],
		'wedding' => [
			'bottles' => [
				'name' => [
					'name' => 'name',
					'type' => 'file',
					'format' => '/media/wedding/bottles;{name};{name}.png'
				]
			],
			'rings' => [
				'name' => [
					'name' => 'name',
					'type' => 'file',
					'format' => '/media/wedding/rings;{name};{name}.png'
				]
			],
			'tables' => [
				'file' => [
					'name' => 'file',
					'type' => 'file',
					'format' => '/media/wedding/tables;{name};{name}.png'
				]
			],
			'bouquets' => [
				'name' => [
					'name' => 'name',
					'type' => 'file',
					'format' => '/media/wedding/bouquets;{name};{name}.png'
				]
			],
			'garters' => [
				'name' => [
					'type' => 'file',
					'format' => '/media/wedding/garters;{name};{name}.png'
				]
			],
			'emcees' => [
				'name' => [
					'type' => 'file',
					'format' => '/media/wedding/emcees;{name};{name}.png'
				]
			],
			'brideSuites' => [
				'name' => [
					'type' => 'file',
					'format' => '/media/wedding/brideSuites;{name};{name}.png'
				]
			],
			'groomSuites' => [
				'name' => [
					'type' => 'file',
					'format' => '/media/wedding/groomSuites;{name};{name}.png'
				]
			],
		],
		'gifts' => [
			'events' => [
				'list' => [
					'dateStart' => [
						'type' => 'date',
						'format' => 'd.m'
					],
					'dateFinish' => [
						'type' => 'date',
						'format' => 'd.m'
					]
				]
			]
		]
	];

	public function post(Request $request, Response $response): Response {
		try {
			$data = json_decode($request->psr()->getBody()->getContents(), false, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
			$this->app->db()->truncateData();
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

	private function createType(string $name, stdClass | array $descriptor, ?Type $parent, ?array $config): void {
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
				if (@$config[$colName]['type'] === 'date' && @$config[$colName]['format']) {
					$date = date_create_from_format($config[$colName]['format'], $entity->{$colName});
					$insertValue[] = $date ? '\''.date('Y-m-d', $date->getTimestamp()).'\'' : 'NULL';
				} else {
					$insertValue[] = match (gettype($entity->{$colName})) {
						'boolean' => +$entity->{$colName},
						'integer', 'double', 'float' => $entity->{$colName},
						'string' => '\''.$this->app->db()->escape($entity->{$colName}).'\'',
						'array', 'object' => '\''.json_encode($entity->{$colName}, JSON_UNESCAPED_UNICODE).'\'',
						'NULL' => 'NULL'
					};
				}
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
