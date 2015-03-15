<?php

/*
Copyright 2014 Basho Technologies, Inc.

Licensed to the Apache Software Foundation (ASF) under one or more contributor license agreements.  See the NOTICE file
distributed with this work for additional information regarding copyright ownership.  The ASF licenses this file
to you under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance
with the License.  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an
"AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the License for the
specific language governing permissions and limitations under the License.
*/

namespace Basho\Riak;

use Basho\Riak\Command\Builder;

/**
 * Class Command
 *
 * The command class is used to build a read or write command to be executed against a Riak node.
 *
 * @author Christopher Mancini <cmancini at basho d0t com>
 */
abstract class Command
{
    /**
     * Request method
     *
     * This can be GET, POST, PUT, or DELETE
     *
     * @see http://docs.basho.com/riak/latest/dev/references/http/
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * Command parameters
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Command request headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * @var Bucket|null
     */
    protected $bucket = NULL;

    /**
     * @var Command\Response|null
     */
    protected $response = NULL;

    /**
     * @var \Basho\Riak|null
     */
    protected $riak = NULL;

    /**
     * Request headers
     *
     * <code>
     * $headers = ['Content-Type: application/json; charset=utf-8'];
     * </code>
     *
     * @var array
     */
    protected $requestHeaders = [];

    public function __construct(Builder $builder)
    {
        $this->riak = $builder->getConnection();
        $this->parameters = $builder->getParameters();
        $this->headers = $builder->getHeaders();
    }

    /**
     * Executes the command against the API
     *
     * @return Command\Response
     */
    public function execute()
    {
        return $this->riak->execute($this);
    }

    /**
     * Gets the request that was issued to the API by this Command.
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->riak->getLastRequest();
    }

    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param $key string
     * @return mixed
     */
    public function getParameter($key)
    {
        return $this->parameters[$key];
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $key string
     *
     * @return mixed
     */
    public function getHeader($key)
    {
        return $this->headers[$key];
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Command has parameters?
     *
     * @return bool
     */
    public function hasParameters()
    {
        return (bool)count($this->parameters);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getResponse()
    {
        return $this->response;
    }

    abstract public function setResponse($statusCode, $responseHeaders = [], $responseBody = '');

    abstract public function getData();

    abstract public function getEncodedData();
}