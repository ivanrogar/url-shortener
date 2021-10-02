<?php
// phpcs:ignorefile

/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace App\Data\Schema\Api\Shorten;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

/**
 * URL shorten response schemaphpcs:ignorefile
 * @SuppressWarnings(PHPMD)
 */
class ShortenResponse extends ClassStructure
{
    /** @var string */
    public $shortUrl;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->shortUrl = Schema::string();
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->schema = "http://json-schema.org/draft-07/schema#";
        $ownerSchema->title = "URL shorten response schema";
        $ownerSchema->required = array(
            self::names()->shortUrl,
        );
        $ownerSchema->id = "shorten.response.schema.json";
    }

    /**
     * @param string $shortUrl
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}
