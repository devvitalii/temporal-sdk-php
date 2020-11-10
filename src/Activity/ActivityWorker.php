<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Client\Activity;

use React\Promise\PromiseInterface;
use Temporal\Client\Internal\Meta\ReaderInterface;
use Temporal\Client\Transport\DispatcherInterface;
use Temporal\Client\Transport\Protocol\Command\RequestInterface;
use Temporal\Client\Transport\Router;
use Temporal\Client\Transport\RouterInterface;
use Temporal\Client\Worker\Declaration\Repository\ActivityRepositoryInterface;
use Temporal\Client\Worker\Declaration\Repository\ActivityRepositoryTrait;
use Temporal\Client\Worker\Worker;

/**
 * @noinspection PhpSuperClassIncompatibleWithInterfaceInspection
 */
class ActivityWorker implements ActivityRepositoryInterface, DispatcherInterface
{
    use ActivityRepositoryTrait;

    /**
     * @var ReaderInterface
     */
    private ReaderInterface $reader;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var Worker
     */
    private Worker $worker;

    /**
     * @param ReaderInterface $reader
     */
    public function __construct(Worker $worker, ReaderInterface $reader)
    {
        $this->reader = $reader;
        $this->worker = $worker;

        $this->bootActivityRepositoryTrait();

        $this->router = new Router();
        $this->router->add(new Router\InvokeActivity($this->activities));
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(RequestInterface $request, array $headers = []): PromiseInterface
    {
        return $this->router->dispatch($request, $headers);
    }

    /**
     * @return ReaderInterface
     */
    protected function getReader(): ReaderInterface
    {
        return $this->reader;
    }
}
