<?php declare(strict_types=1);

namespace Lmc\Matej\Model\Command;

/**
 * Save user-invoked interaction
 * TODO: add documentation to individual setters
 */
class Interaction extends AbstractCommand
{
    const RELEVANCE_LOW = 'low';
    const RELEVANCE_MEDIUM = 'medium';
    const RELEVANCE_HIGH = 'high';

    /** @var string */
    private $userId;
    /** @var int */
    private $count;
    /** @var string|null */
    private $scenario = null;
    /** @var float|null */
    private $rotationRate = null;
    /** @var int|null */
    private $rotationTime = null;
    /** @var bool|null */
    private $hardRotation = null;
    /** @var string|null */
    private $minRelevance = null;
    /** @var string|null */
    private $filter = null;

    /**
     * Save user-invoked interaction
     */
    public function __construct(string $userId, int $count, string $scenario)
    {
        $this->userId = $userId;
        $this->count = $count;
        $this->scenario = $scenario;
    }

    public function setRotationRate(float $rotationRate = null): self
    {
        // TODO: has to be between 0 and 1
        $this->rotationRate = $rotationRate;

        return $this;
    }

    public function setRotationTime(int $rotationTime = null): self
    {
        $this->rotationTime = $rotationTime;

        return $this;
    }

    public function setHardRotation(bool $hardRotation = null): self
    {
        $this->hardRotation = $hardRotation;

        return $this;
    }

    public function setMinRelevance(string $minRelevance = null): self
    {
        $this->minRelevance = $minRelevance;

        return $this;
    }

    public function setFilter(string $filter = null): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function getCommandType(): string
    {
        return 'interaction';
    }

    public function getCommandParameters(): array
    {
        $payload = [
            'user_id' => $this->userId,
            'count' => $this->count,
            'scenario' => $this->scenario,
        ];

        $this->rotationRate === null ?: $payload['rotation_rate'] = $this->rotationRate;
        $this->rotationTime === null ?: $payload['rotation_time'] = $this->rotationTime;
        $this->hardRotation === null ?: $payload['hard_rotation'] = $this->hardRotation;
        $this->minRelevance === null ?: $payload['min_relevance'] = $this->minRelevance;
        $this->filter === null ?: $payload['filter'] = $this->filter;

        return $payload;
    }

    /**
     * Save user-invoked interaction
     */
    public static function create(string $userId, int $count, string $scenario): self
    {
        return new static($userId, $count, $scenario);
    }
}
