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
 * phpcs:ignorefile
 * @SuppressWarnings(PHPMD)
 */
class MessagesItems extends ClassStructure
{
    /** @var string */
    public $to;

    /** @var MessagesItemsStatus */
    public $status;

    /** @var string */
    public $messageId;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->to = Schema::string();
        $properties->status = MessagesItemsStatus::schema();
        $properties->messageId = Schema::string();
        $ownerSchema->type = Schema::OBJECT;
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

    /**
     * @param MessagesItemsStatus $status
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setStatus(MessagesItemsStatus $status)
    {
        $this->status = $status;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $messageId
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}
