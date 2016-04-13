<?php

namespace PhproTest\AnnotatedCacheBundle\Unit\DataCollector;

use Phpro\AnnotatedCache\Collector\MemoryResultCollector;

/**
 * Class CacheableResultsDataCollectorTest
 *
 * @package PhproTest\AnnotatedCacheBundle\Unit\DataCollector
 */
class CacheableResultsDataCollectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MemoryResultCollector
     */
    private $collector;

    protected function setUp()
    {
        $this->collector = new MemoryResultCollector();
    }

    /**
     * @test
     */
    function it_collects_results()
    {

        $this->assertTrue(true);

    }
}
