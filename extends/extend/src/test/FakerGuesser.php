<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\test;

use Faker\Factory;
use Faker\Generator;
use yii\base\BaseObject;

/**
 * Class FakerGuesser
 * @package lujie\extend\test
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FakerGuesser extends BaseObject
{
    /**
     * @var string
     */
    public $language = 'en_US';

    /**
     * @var Generator
     */
    public $generator;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->generator === null) {
            $this->generator = Factory::create(strtr($this->language, ['-' => '_']));
        }
    }

    /**
     * @param string $name
     * @param string $phpType
     * @param int|null $size
     * @param int|null $decimals
     * @param array|null $values
     * @return callable|null
     * @inheritdoc
     */
    public function guessFormat(string $name, string $phpType, ?int $size = null, ?int $decimals = 2, ?array $values = null): ?callable
    {
        $generator = $this->generator;
        $nameParts = explode('_', $name);
        $suffix = end($nameParts);
        if ($values) {
            return static function () use ($generator, $values) {
                return $generator->randomElement($values);
            };
        }
        if ($generator->getFormatter($suffix)) {
            return static function () use ($generator, $suffix) {
                return $generator->format($suffix);
            };
        }
        return match ($suffix) {
            'at' => static function () use ($generator) {
                return $generator->unixTime();
            },
            'country', 'destination', 'departure' => static function () use ($generator) {
                return $generator->countryCode();
            },
            'currency' => static function () use ($generator) {
                return $generator->currencyCode();
            },
            'language' => static function () use ($generator) {
                return $generator->languageCode();
            },
            'url' => str_contains($name, 'image')
                ? static function () use ($generator) {
                    return $generator->imageUrl();
                }
                : static function () use ($generator) {
                    return $generator->file();
                },
            'path' => static function () use ($generator) {
                return $generator->file();
            },
            'username' => static function () use ($generator) {
                return $generator->userName();
            },
            'name1' => static function () use ($generator) {
                return $generator->company();
            },
            'name2' => static function () use ($generator) {
                return $generator->firstName();
            },
            'name3' => static function () use ($generator) {
                return $generator->lastName();
            },
            'address1' => static function () use ($generator) {
                return $generator->streetAddress();
            },
            'address2' => static function () use ($generator) {
                return $generator->buildingNumber();
            },
            'postal_code' => static function () use ($generator) {
                return $generator->postcode();
            },
            'phone' => static function () use ($generator) {
                return $generator->phoneNumber();
            },
            default => match ($phpType) {
                'string' => $size <= 10
                    ? static function () use ($generator, $size) {
                        return $generator->lexify(str_repeat('?', $generator->numberBetween(1, $size)));
                    }
                    : static function () use ($generator, $size) {
                        return $generator->text($size);
                    },
                'integer' => static function () use ($generator, $size) {
                    $size = $size ?? 10;
                    if ($size <= 3) {
                        $max = 2 ** 7;
                    } else if ($size <= 5) {
                        $max = 2 ** 15;
                    } else if ($size <= 10) {
                        $max = 2 ** 31;
                    } else {
                        $max = 2 ** 63;
                    }
                    return $generator->numberBetween(0, $max);
                },
                'double' => static function () use ($generator, $size, $decimals) {
                    return $generator->randomFloat($decimals, 0, 10 ** ($size - $decimals));
                },
                'boolean' => static function () use ($generator) {
                    return $generator->lastName;
                },
                default => null,
            },
        };
    }

    /**
     * @param string $name
     * @param string $phpType
     * @param int|null $size
     * @return callable|null
     * @inheritdoc
     */
    public function guessInvalidFormat(string $name, string $phpType, ?int $size = null, ?array $values = null): ?callable
    {
        $generator = $this->generator;
        if ($values) {
            return static function () use ($generator, $phpType, $values) {
                $randomElement = $generator->randomElement($values);
                return match ($phpType) {
                    'string' => $generator->lexify(str_repeat('?', strlen($randomElement))),
                    'integer' => $randomElement * 17,
                    default => null,
                };
            };
        }
        return match ($phpType) {
            'string' => static function () use ($generator, $size) {
                return $generator->lexify(str_repeat('?', $generator->numberBetween($size, $size * 2)));
            },
            'integer', 'double', 'boolean' => static function () use ($generator, $size) {
                return $generator->lexify(str_repeat('?', $generator->numberBetween(1, $size)));
            },
            default => null,
        };
    }

    /**
     * @param array $attributeTypes
     * @param bool $valid
     * @return array
     * @inheritdoc
     */
    public function guessValues(array $attributeTypes, bool $valid = true): array
    {
        $values = [];
        foreach ($attributeTypes as $attribute => $type) {
            if (is_string($type[0])) {
                $format = $valid
                    ? $this->guessFormat($attribute, $type[0], $type[1] ?? null, $type['decimals'] ?? null, $type['values'] ?? null)
                    : $this->guessInvalidFormat($attribute, $type[0], $type[1] ?? null, $type['values'] ?? null);
                if (!$format === null) {
                    $values[$attribute] = $format();
                }
            } else {
                $isMulti = $type[0];
                if ($isMulti) {
                    $values[$attribute] = [
                        $this->guessValues($type[1], $valid),
                        $this->guessValues($type[1], $valid),
                    ];
                } else {
                    $values[$attribute] = $this->guessValues($type[1], $valid);
                }
            }
        }
        return $values;
    }
}
