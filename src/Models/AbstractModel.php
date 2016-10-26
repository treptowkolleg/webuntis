<?php
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

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\ModelInterface;

/**
 * Abstract Class Model
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
abstract class Model implements ModelInterface {
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    const METHOD = '';

    /**
     * Model constructor.
     * @param array $data
     */
    public function __construct(array $data) {
        $this->parse($data);
    }

    /**
     * returns the id
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * set the id
     * @param $id
     * @return Model $this
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * serializes the object and returns an array with the objects values
     * @return array
     */
    abstract function serialize();

    /**
     * parses the given data from the json rpc api to the right format for the object
     * @param $data
     */
    abstract protected function parse($data);
}