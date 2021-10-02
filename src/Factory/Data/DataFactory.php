<?php

declare(strict_types=1);

namespace App\Factory\Data;

use App\Data\Schema\Api\Shorten\ShortenRedirectResponse;
use App\Data\Schema\Api\Shorten\ShortenRequest;
use App\Data\Schema\Api\Shorten\ShortenResponse;
use App\Exception\Factory\CreateException;
use App\Exception\Factory\InvalidOutputTypeException;
use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\Structure\ClassStructure;

class DataFactory
{
    public const TYPE_SHORTEN_REQUEST = 'shorten_request';
    public const TYPE_SHORTEN_RESPONSE = 'shorten_response';
    public const TYPE_SHORTEN_REDIRECT_RESPONSE = 'shorten_redirect_response';

    /**
     * @throws CreateException
     */
    public function create(string $type, array $data): ClassStructure
    {
        try {
            $objectData = \json_decode(\json_encode($data));

            return match ($type) {
                self::TYPE_SHORTEN_REQUEST => ShortenRequest::import($objectData),
                self::TYPE_SHORTEN_RESPONSE => ShortenResponse::import($objectData),
                self::TYPE_SHORTEN_REDIRECT_RESPONSE => ShortenRedirectResponse::import($objectData),
                default => throw new InvalidOutputTypeException(),
            };
        } catch (Exception $exception) {
            throw new CreateException($exception->getMessage(), 0, $exception);
        }
    }
}
