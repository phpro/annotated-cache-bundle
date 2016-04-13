<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCacheBundle\DataCollector;

use Exception;
use Phpro\AnnotatedCache\Collector\ResultCollectorInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EvictResult;
use Phpro\AnnotatedCache\Interceptor\Result\HittableResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\TagsAwareResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\UpdateResult;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class CacheableResultsDataCollector
 *
 * @package Phpro\AnnotatedCacheBundle\DataCollector
 */
class CacheableResultsDataCollector extends DataCollector
{
    /**
     * @var ResultCollectorInterface
     */
    private $collector;

    public function __construct(ResultCollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    /**
     * @param Request        $request
     * @param Response       $response
     * @param Exception|null $exception
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $results = $this->collector->getResults();
        $this->data = array(
            'hits' => $results->countHits(),
            'miss' => $results->countMisses(),
            'results' => $results->toArray(),
            'cacheable_results' => $results->filterByType(HittableResultInterface::class)->toArray(),
            'cache_evict_results' => $results->filterByType(EvictResult::class)->toArray(),
            'cache_update_results' => $results->filterByType(UpdateResult::class)->toArray(),
        );
    }

    /**
     * @return int
     */
    public function getHits() : int
    {
        return $this->data['hits'];
    }

    /**
     * @return int
     */
    public function getMiss() : int
    {
        return $this->data['miss'];
    }

    /**
     * @return ResultInterface[]
     */
    public function getResults() : array
    {
        return $this->data['results'];
    }

    /**
     * @return HittableResultInterface[]
     */
    public function getCacheableResults() : array
    {
        return $this->data['cacheable_results'];
    }

    /**
     * @return TagsAwareResultInterface[]|ResultInterface[]
     */
    public function getCacheaEvictResults() : array
    {
        return $this->data['cache_evict_results'];
    }

    /**
     * @return ResultInterface[]
     */
    public function getCacheUpdateResults() : array
    {
        return $this->data['cache_update_results'];
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return 'phpro.annotated_cache';
    }
}
