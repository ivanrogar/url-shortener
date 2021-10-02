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
 * URL shorten redirect schema
 * @SuppressWarnings(PHPMD)
 */
class ShortenRedirectResponse extends ClassStructure
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
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->schema = "http://json-schema.org/draft-07/schema#";
        $ownerSchema->title = "URL shorten redirect schema";
        $ownerSchema->required = array(
            self::names()->url,
        );
        $ownerSchema->id = "shorten.redirect.schema.json";
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
