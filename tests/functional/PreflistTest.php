<?php

namespace Basho\Tests;

use Basho\Riak\Command;

/**
 * Class PreflistTest
 *
 * Functional tests related to Riak Preference lists
 *
 * @author Christopher Mancini <cmancini at basho d0t com>
 */
class PreflistTest extends TestCase
{
    public function testFetch()
    {
        // build a store object command, get the location of the newly minted object
        $location = (new Command\Builder\StoreObject(static::$riak))
            ->buildObject('some_data')
            ->buildBucket('users')
            ->build()
            ->execute()
            ->getLocation();

        // build a fetch command
        $command = (new Command\Builder\FetchPreflist(static::$riak))
            ->atLocation($location)
            ->build();

        try {
            $response = $command->execute();
            if ($response->getCode() == 400) {
                $this->markTestSkipped('preflists are not supported');
            } else {
                $this->assertEquals(200, $response->getCode());
                $this->assertNotEmpty($response->getRiakObject()->getData()->preflist);
                $this->assertObjectHasAttribute("partition", $response->getRiakObject()->getData()->preflist[0]);
                $this->assertObjectHasAttribute("node", $response->getRiakObject()->getData()->preflist[0]);
                $this->assertObjectHasAttribute("primary", $response->getRiakObject()->getData()->preflist[0]);
            }
        } catch (\Basho\Riak\Exception $e) {
            $this->markTestSkipped('preflists are not supported');
        }
    }
}
