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
 * URL shorten request schema
 * @SuppressWarnings(PHPMD)
 */
class ShortenRequest extends ClassStructure
{
    /** @var string */
    public $url;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->url = Schema::string();
        $properties->url->pattern = "((http|https)://)(www.)?[a-zA-Z0-9@:%._\\+~#?&/=]{2,256}\\.[a-z]{2,6}\\b([-a-zA-Z0-9@:%._\\+~#?&/=]*)";
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->schema = "http://json-schema.org/draft-07/schema#";
        $ownerSchema->title = "URL shorten request schema";
        $ownerSchema->required = array(
            self::names()->url,
        );
        $ownerSchema->id = "shorten.request.schema.json";
    }

    /**
     * @param string $url
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}
