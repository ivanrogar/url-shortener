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
class MessagesItemsDestinationsItems extends ClassStructure
{
    /** @var string */
    public $to;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->to = Schema::string();
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->required = [
            'to',
        ];
    }

    /**
     * @param string $to
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}
