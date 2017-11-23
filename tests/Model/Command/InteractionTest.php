<?php declare(strict_types=1);

namespace Lmc\Matej\Model\Command;

use PHPUnit\Framework\TestCase;

class InteractionTest extends TestCase
{
    /** @var string */
    private $userId;
    /** @var int */
    private $count;
    /** @var string */
    private $scenario;

    public function setUp(): void
    {
        $this->userId = 'user-' . md5(uniqid());
        $this->count = rand();
        $this->scenario = 'scenario-' . md5(uniqid());
    }

    /**
     * @test
     */
    public function shouldGenerateCorrectSignature(): void
    {
        // Sample data
        $rotationRate = (float) rand();
        $rotationTime = rand(11111108000, 2524608000); // 2524608000 is 2050
        $filter = md5(uniqid());

        /**
         * Test plain constructors
         */
        $this->assertInteractionObject(new Interaction($this->userId, $this->count, $this->scenario));
        $this->assertInteractionObject($this->createBaseCommand());

        /**
         * Test optional arguments
         */
        $command = $this->createBaseCommand()->setRotationRate($rotationRate);
        $this->assertInteractionObject($command, 'rotation_rate', $rotationRate);

        $command = $this->createBaseCommand()->setRotationTime($rotationTime);
        $this->assertInteractionObject($command, 'rotation_time', $rotationTime);

        $command = $this->createBaseCommand()->setHardRotation(true);
        $this->assertInteractionObject($command, 'hard_rotation', true);

        $command = $this->createBaseCommand()->setHardRotation(false);
        $this->assertInteractionObject($command, 'hard_rotation', false);

        $command = $this->createBaseCommand()->setMinRelevance(Interaction::RELEVANCE_LOW);
        $this->assertInteractionObject($command, 'min_relevance', Interaction::RELEVANCE_LOW);

        $command = $this->createBaseCommand()->setMinRelevance(Interaction::RELEVANCE_MEDIUM);
        $this->assertInteractionObject($command, 'min_relevance', Interaction::RELEVANCE_MEDIUM);

        $command = $this->createBaseCommand()->setMinRelevance(Interaction::RELEVANCE_HIGH);
        $this->assertInteractionObject($command, 'min_relevance', Interaction::RELEVANCE_HIGH);

        $command = $this->createBaseCommand()->setFilter($filter);
        $this->assertInteractionObject($command, 'filter', $filter);
    }

    private function createBaseCommand(): Interaction
    {
        return Interaction::create($this->userId, $this->count, $this->scenario);
    }

    /**
     * Execute asserts against user merge object
     * @param object $object
     * @param mixed $optionalValue
     */
    private function assertInteractionObject($object, string $optionalArgument = null, $optionalValue = null): void
    {
        $expected = [
            'type' => 'interaction',
            'parameters' => [
                'user_id' => $this->userId,
                'count' => $this->count,
                'scenario' => $this->scenario,
            ],
        ];

        if ($optionalArgument !== null) {
            $expected['parameters'][$optionalArgument] = $optionalValue;
        }

        $this->assertInstanceOf(Interaction::class, $object);
        $this->assertSame($expected, $object->jsonSerialize());
    }
}
