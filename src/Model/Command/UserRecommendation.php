<?php declare(strict_types=1);

namespace Lmc\Matej\Model\Command;

/**
 * Deliver personalized recommendations for the given user.
 */
class UserRecommendation extends AbstractCommand
{
    const MIN_RELEVANCE_LOW = 'low';
    const MIN_RELEVANCE_MEDIUM = 'medium';
    const MIN_RELEVANCE_HIGH = 'high';

    /** @var string */
    private $userId;
    /** @var int */
    private $count;
    /** @var string */
    private $scenario;
    /** @var float */
    private $rotationRate;
    /** @var int */
    private $rotationTime;
    /** @var bool */
    private $hardRotation;
    /** @var string */
    private $minimalRelevance = self::MIN_RELEVANCE_LOW;
    /** @var string */
    private $filter = 'valid_to >= NOW';

    private function __construct(
        string $userId,
        int $count,
        string $scenario,
        float $rotationRate,
        int $rotationTime,
        bool $hardRotation
    ) {
        $this->userId = $userId; // TODO: assert format
        $this->count = $count; // TODO: assert greater than 0
        $this->scenario = $scenario; // TODO: assert format
        $this->rotationRate = $rotationRate; // TODO: assert values is between 0 and 1
        $this->rotationTime = $rotationTime; // TODO: assert valid timestamp
        $this->hardRotation = $hardRotation;
    }

    /**
     * @param string $userId
     * @param int $count Number of requested recommendations. The real number of recommended items could be lower or
     * even zero when there are no items relevant for the user.
     * @param string $scenario Name of the recommendations application.
     * @param float $rotationRate How much should the item be penalized for being recommended again in the near future.
     * Set from 0.0 for no rotation (same items will be recommended) up to 1.0 (same items should not be recommended).
     * @param int $rotationTime Specify for how long will the item's rotationRate be taken in account and so the item
     * is penalized for recommendations.
     * @param bool $hardRotation Even with rotation rate 1.0 user could still obtain the same recommendations in some
     * edge cases. To prevent this, enable hard rotation - recommended items are then excluded until rotation time is
     * expired.
     * @return UserRecommendation
     */
    public static function create(
        string $userId,
        int $count,
        string $scenario,
        float $rotationRate,
        int $rotationTime,
        bool $hardRotation
    ): self {
        return new static($userId, $count, $scenario, $rotationRate, $rotationTime, $hardRotation);
    }

    /**
     * Overwrite default filter.
     */
    public function setFilter(string $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Define threshold of how much relevant must the recommended items be to be returned.
     */
    public function setMinimalRelevance(string $minimalRelevance)
    {
        // TODO: assert one of MIN_RELEVANCE_*
        $this->minimalRelevance = $minimalRelevance;
    }


    public function getCommandType(): string
    {
        return 'interaction';
    }

    public function getCommandParameters(): array
    {
        return [
            'user_id' => $this->userId,
            'count' => $this->count,
            'scenario' => $this->scenario,
            'rotation_rate' => $this->rotationRate,
            'rotation_time' => $this->rotationTime,
            'hard_rotation' => $this->hardRotation,
            'min_relevance' => $this->minimalRelevance,
            'filter' => $this->filter,
        ];
    }
}
