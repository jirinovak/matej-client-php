<?php declare(strict_types=1);

namespace Lmc\Matej\Model\Command;

/**
 * Sort given item ids for user-based recommendations
 */
class Sorting extends AbstractCommand
{
    /** @var string */
    private $userId;
    /** @var string[] */
    private $itemIds = [];

    /**
     * Sort given item ids for user-based recommendations
     */
    public function __construct(string $userId, array $itemIds)
    {
        $this->userId = $userId;
        $this->itemIds = $itemIds;
    }

    public function getCommandType(): string
    {
        return 'sorting';
    }

    public function getCommandParameters(): array
    {
        return [
            'user_id' => $this->userId,
            'item_ids' => $this->itemIds,
        ];
    }

    /**
     * Sort given item ids for user-based recommendations
     */
    public static function create(string $userId, array $itemIds): self
    {
        return new static($userId, $itemIds);
    }
}
