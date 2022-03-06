<?php

declare(strict_types=1);

namespace Messenger\People\Test;

use Generator;
use InvalidArgumentException;
use Messenger\People\Interpolator;
use PHPUnit\Framework\TestCase;

final class InterpolatorTest extends TestCase
{
    private Interpolator $interpolator;

    protected function setUp(): void
    {
        $this->interpolator = new Interpolator();
    }

    /**
     * @dataProvider getPositiveTestCases
     */
    public function testShouldReplaceVariables($str, $replacement, $options, $expected)
    {
        $result = $this->interpolator->interpolate($str, $replacement, $options);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider getNegativeTestCasesWithOptions
     */
    public function testShouldReturnExceptionWhenOptionsInvalid($str, $replacement, $options)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Options are not valid');

        $this->interpolator->interpolate($str, $replacement, $options);
    }

    /**
     * @dataProvider getNegativeTestCasesWithReplacement
     */
    public function testShouldReturnExceptionWhenReplacementInvalid($str, $replacement, $options)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Replacement is not valid');

        $this->interpolator->interpolate($str, $replacement, $options);
    }

    private function getPositiveTestCases(): Generator
    {
        // basic case
        yield [
            "Hi! I'm Choo Choo, but your name is way cooler, isn't it, {firstname}?",
            ['firstname' => 'John'],
            [Interpolator::LEFT_DELIMITER => '{', Interpolator::RIGHT_DELIMITER => '}'],
            "Hi! I'm Choo Choo, but your name is way cooler, isn't it, John?"
        ];

        // repeated variables
        yield [
            'Hi {firstname}. Are you {firstname}?',
            ['firstname' => 'John'],
            [Interpolator::LEFT_DELIMITER => '{', Interpolator::RIGHT_DELIMITER => '}'],
            'Hi John. Are you John?',
        ];

        // repeated and multiple variables
        yield [
            'Hi {firstname} {lastname}. Confirm your full name: {firstname} {lastname}',
            ['firstname' => 'John', 'lastname' => 'Smith'],
            [Interpolator::LEFT_DELIMITER => '{', Interpolator::RIGHT_DELIMITER => '}'],
            'Hi John Smith. Confirm your full name: John Smith',
        ];

        // new delimiters
        yield [
            'Hi [firstname] [lastname]. Confirm your full name: [firstname] [lastname]',
            ['firstname' => 'John', 'lastname' => 'Smith'],
            [Interpolator::LEFT_DELIMITER => '[', Interpolator::RIGHT_DELIMITER => ']'],
            'Hi John Smith. Confirm your full name: John Smith',
        ];

        // different delimiter
        yield [
            'Hi ^firstname$ ^lastname$. Confirm your full name: ^firstname$ ^lastname$',
            ['firstname' => 'John', 'lastname' => 'Smith'],
            [Interpolator::LEFT_DELIMITER => '^', Interpolator::RIGHT_DELIMITER => '$'],
            'Hi John Smith. Confirm your full name: John Smith',
        ];

        // a lot of variables included int
        yield [
            'Hi {firstname} {lastname}. Are you {age} old? is it  your {email} address? Is it your {phone}?',
            [
                'firstname' => 'John',
                'lastname' => 'Smith',
                'age' => 32,
                'email' => 'john@smith.email',
                'phone' => '+492123131313',
            ],
            [Interpolator::LEFT_DELIMITER => '{', Interpolator::RIGHT_DELIMITER => '}'],
            'Hi John Smith. Are you 32 old? is it  your john@smith.email address? Is it your +492123131313?'
        ];
    }

    private function getNegativeTestCasesWithOptions(): Generator
    {
        yield [
            'Hi {firstname}.',
            ['firstname' => 'John'],
            [Interpolator::LEFT_DELIMITER => '{', '}'],
        ];

        yield [
            'Hi {firstname}.',
            ['firstname' => 'John'],
            ['{', Interpolator::RIGHT_DELIMITER => '}'],
        ];

        yield [
            'Hi {firstname}.',
            ['firstname' => 'John'],
            [Interpolator::RIGHT_DELIMITER => '}'],
        ];

        yield [
            'Hi {firstname}.',
            ['firstname' => 'John'],
            [Interpolator::LEFT_DELIMITER => '}'],
        ];

        yield [
            'Hi {firstname}.',
            ['firstname' => 'John'],
            ['{', '}'],
        ];
    }

    private function getNegativeTestCasesWithReplacement(): Generator
    {
        yield [
            'Hi {firstname}.',
            ['firstname'],
            [Interpolator::LEFT_DELIMITER => '{', Interpolator::RIGHT_DELIMITER => '}'],
        ];

        yield [
            'Hi {firstname}.',
            [0 => 'John'],
            [Interpolator::LEFT_DELIMITER => '{', Interpolator::RIGHT_DELIMITER => '}'],
        ];
    }
}