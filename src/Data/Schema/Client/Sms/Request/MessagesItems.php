<?php
// phpcs:ignorefile

/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace App\Data\Schema\Client\Sms\Request;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;


/**
 * phpcs:ignorefile
 * @SuppressWarnings(PHPMD)
 */
class MessagesItems extends ClassStructure
{
    /** @var string */
    public $from;

    /** @var MessagesItemsDestinationsItems[]|array */
    public $destinations;

    /** @var string */
    public $text;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->from = Schema::string();
        $properties->destinations = Schema::arr();
        $properties->destinations->items = MessagesItemsDestinationsItems::schema();
        $properties->destinations->minItems = 1;
        $properties->text = Schema::string();
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->required = [
            'from',
            'text',
        ];
    }

    /**
     * @param string $from
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param MessagesItemsDestinationsItems[]|array $destinations
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setDestinations($destinations)
    {
        $this->destinations = $destinations;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $text
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}
