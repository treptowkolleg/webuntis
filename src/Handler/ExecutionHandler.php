<?php
declare(strict_types=1);

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace Webuntis\Handler;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Repositories\Repository;
use Webuntis\Handler\Interfaces\ExecutionHandlerInterface;
use Webuntis\CacheBuilder\CacheBuilder;

/**
 * Class ExecutionHandler
 * @package Webuntis\handler
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class ExecutionHandler implements ExecutionHandlerInterface {

    /**
     * @var object
     */
    private $cache;

    public function __construct() 
    {
        $cacheBuilder = new CacheBuilder();
        $this->cache = $cacheBuilder->create();
    }

    /**
     * executes the given command with the right instance, model etc.
     * @param Repository $repository
     * @param array $params
     * @return AbstractModel[]
     */
    public function execute(Repository $repository, array $params) : array 
    {
        $model = $repository->getModel();
        $interfaces = class_implements($model);
        if ($this->cache && $this->cache->contains($model::METHOD) && isset($interfaces[CachableModelInterface::class])) {
            $data = $this->cache->fetch($model::METHOD);
        } else {
            $result = $repository->getInstance()->getClient()->execute($model::METHOD, $params);
            $data = $repository->parse($result);

            if ($this->cache && isset($interfaces[CachableModelInterface::class])) {
                if ($model::CACHE_LIFE_TIME) {
                    $this->cache->save($model::METHOD, $data, $model::CACHE_LIFE_TIME);
                } else {
                    $this->cache->save($model::METHOD, $data);
                }
            }
        }
        return $data;
    }
}