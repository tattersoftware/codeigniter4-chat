<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tatter\Imposter\Entities\User;
use Tatter\Imposter\Factories\ImposterFactory;
use Tatter\Users\UserProvider;

/**
 * @internal
 */
abstract class ModuleTestCase extends CIUnitTestCase
{
    use DatabaseTestTrait;

    /**
     * Should the db be refreshed before test?
     *
     * @var bool
     */
    protected $refresh = true;

    /**
     * The namespace(s) to help us find the migration classes.
     * Empty is equivalent to running `spark migrate -all`.
     * Note that running "all" runs migrations in date order,
     * but specifying namespaces runs them in namespace order (then date)
     *
     * @var array|string|null
     */
    protected $namespace;

    /**
     * Initializes Imposter.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        UserProvider::addFactory(ImposterFactory::class, ImposterFactory::class);

        helper('auth');
    }

    protected function fakeUser(): User
    {
        $user     = ImposterFactory::fake();
        $user->id = ImposterFactory::add($user);

        return $user;
    }
}
