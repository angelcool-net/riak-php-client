<?php

namespace Basho\Riak\Command\RiakObject;

use Basho\Riak\Command;
use Basho\Riak\CommandInterface;

/**
 * Fetches a Riak Kv Object
 *
 * @author Christopher Mancini <cmancini at basho d0t com>
 */
class Fetch extends Command\RiakObject implements CommandInterface
{
    public function __construct(Command\Builder\FetchObject $builder)
    {
        parent::__construct($builder);

        $this->bucket = $builder->getBucket();
        $this->location = $builder->getLocation();
        $this->decodeAsAssociative = $builder->getDecodeAsAssociative();
    }
}
