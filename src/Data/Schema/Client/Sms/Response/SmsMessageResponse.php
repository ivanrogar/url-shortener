<?php
// phpcs:ignorefile

/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace App\Data\Schema\Client\Sms\Response;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

/**
 * SMS response schema
 * @SuppressWarnings(PHPMD)
 */
class SmsMessageResponse extends ClassStructure
{
    /** @var string */
    public $bulkId;

    /** @var MessagesItems[]|array */
    public $messages;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->bulkId = Schema::string();
        $properties->messages = Schema::arr();
        $properties->messages->items = MessagesItems::schema();
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->schema = "http://json-schema.org/draft-07/schema#";
        $ownerSchema->title = "SMS response schema";
        $ownerSchema->id = "sms.message.response.schema.json";
    }

    /**
     * @param string $bulkId
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setBulkId($bulkId)
    {
        $this->bulkId = $bulkId;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

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
