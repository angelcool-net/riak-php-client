<?php

/*
Copyright 2015 Basho Technologies, Inc.

Licensed to the Apache Software Foundation (ASF) under one or more contributor license agreements.  See the NOTICE file
distributed with this work for additional information regarding copyright ownership.  The ASF licenses this file
to you under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance
with the License.  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an
"AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the License for the
specific language governing permissions and limitations under the License.
*/

namespace Basho\Tests\Riak;

use Basho\Riak\Api;
use Basho\Riak\Object;
use Basho\Tests\TestCase;

/**
 * Class ObjectTest
 *
 * Test set for the HTTP Header <-> Secondary Index translator
 *
 * @author Alex Moore <amoore at basho d0t com>
 */
class SecondaryIndexHeaderTest extends TestCase
{
    public function testExtractIndexes()
    {
        $headers = ['x-riak-index-foo_bin' => 'bar, baz', 'x-riak-index-foo_int' => '42, 50'];
        $translator = new Api\Translators\SecondaryIndexHeader();

        $indexes = $translator->extractIndexes($headers);

        $this->assertNotEmpty($indexes);
        $this->assertEquals(2, count($indexes));
        $this->assertEquals(['bar', 'baz'], $indexes["foo_bin"]);
        $this->assertEquals([42, 50], $indexes["foo_int"]);
    }

    public function testExtractIndexesNoHeaders()
    {
        $headers = [];
        $translator = new Api\Translators\SecondaryIndexHeader();

        $indexes = $translator->extractIndexes($headers);

        $this->assertNotNull($indexes);
        $this->assertEmpty($indexes);
    }

    public function testCreateHeaders()
    {
        $indexes = ['foo_bin' => ['bar', 'baz'], 'foo_int' => [42, 50]];
        $translator = new Api\Translators\SecondaryIndexHeader();

        $headers = $translator->createHeaders($indexes);

        $this->assertEquals(4, count($headers));
        $this->assertEquals(['x-riak-index-foo_bin', 'bar'], $headers[0]);
        $this->assertEquals(['x-riak-index-foo_bin', 'baz'], $headers[1]);
        $this->assertEquals(['x-riak-index-foo_int', '42'], $headers[2]);
        $this->assertEquals(['x-riak-index-foo_int', '50'], $headers[3]);
    }
}