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
class MessagesItemsStatus extends ClassStructure
{
    /** @var int */
    public $groupId;

    /** @var string */
    public $groupName;

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->groupId = Schema::integer();
        $properties->groupName = Schema::string();
        $properties->id = Schema::integer();
        $properties->name = Schema::string();
        $properties->description = Schema::string();
        $ownerSchema->type = Schema::OBJECT;
    }

    /**
     * @param int $groupId
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $groupName
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $id
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $name
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $description
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}
