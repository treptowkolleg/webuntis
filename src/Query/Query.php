<?php
declare(strict_types=1);

namespace Webuntis\Query;

use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Exceptions\QueryException;
use Webuntis\Repositories\Repository;

/**
 * returns the right Repository according to the given String. 
 * The right Model that is assigned to that string is injectied into the Repository.
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Query {

    /**
     * @var Repository[]
     */
    private static $cachedRepositories = [];

    /**
     * Query constructor.
     */
    public function __construct() {
        $config = new YAMLConfiguration();

        $this->models = $config->getModels();
        $this->repositories = $config->getRepositories();
    }

    /**
     * gets the right repository to the right model
     * @param string $className
     * @return Repository
     * @throws QueryException
     */
    public function get(string $className) : object 
    {
        if($className == 'User') {
            if (!isset(static::$cachedRepositories[$className])) {
                static::$cachedRepositories[$className] = new $this->repositories[$className]();
            }
            return static::$cachedRepositories[$className];
        }
        if (isset($this->models[$className])) {
            if (isset($this->repositories[$className])) {
                $name = $className;
            } else {
                $name = 'Default';
            }
            if (!isset(static::$cachedRepositories[$className])) {
                static::$cachedRepositories[$className] = new $this->repositories[$name]($this->models[$className]);
            }
            return static::$cachedRepositories[$className];
        }
        throw new QueryException('Model ' . $className . ' not found');
    }
}