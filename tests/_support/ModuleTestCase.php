<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use Tests\Support\Database\Seeds\MythSeeder;

/**
 * @internal
 */
final class ModuleTestCase extends CIUnitTestCase
{
    use \CodeIgniter\Test\DatabaseTestTrait;

    /**
     * Should the db be refreshed before test?
     *
     * @var bool
     */
    protected $refresh = true;

    /**
     * The seed file(s) used for all tests within this test case.
     * Should be fully-namespaced or relative to $basePath
     *
     * @var array|string
     */
    protected $seed = MythSeeder::class;

    /**
     * The namespace(s) to help us find the migration classes.
     * Empty is equivalent to running `spark migrate -all`.
     * Note that running "all" runs migrations in date order,
     * but specifying namespaces runs them in namespace order (then date)
     *
     * @var array|string|null
     */
    protected $namespace;
}
