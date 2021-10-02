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
 * Send SMS schema
 * @SuppressWarnings(PHPMD)
 */
class SmsMessageRequest extends ClassStructure
{
    /** @var MessagesItems[]|array */
    public $messages;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->messages = Schema::arr();
        $properties->messages->items = MessagesItems::schema();
        $properties->messages->minItems = 1;
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->schema = "http://json-schema.org/draft-07/schema#";
        $ownerSchema->title = "Send SMS schema";
        $ownerSchema->id = "send.sms.message.schema.json";
    }

    /**
     * @param MessagesItems[]|array $messages
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}
