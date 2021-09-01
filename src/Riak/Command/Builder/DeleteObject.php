<?php

namespace Basho\Riak\Command\Builder;

use Basho\Riak\Command;

/**
 * Used to delete a KV object from Riak
 *
 * <code>
 * $command = (new Command\Builder\DeleteObject($riak))
 * ->buildLocation('username', 'users')
 * ->build();
 *
 * $response = $command->execute();
 * </code>
 *
 * @author Christopher Mancini <cmancini at basho d0t com>
 */
class DeleteObject extends Command\Builder implements Command\BuilderInterface
{
    use ObjectTrait;
    use LocationTrait;

    /**
     * {@inheritdoc}
     *
     * @return Command\RiakObject\Delete;
     */
    public function build()
    {
        $this->validate();

        return new Command\RiakObject\Delete($this);
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        $this->required('Location');
    }
}
