<?php
namespace HDNET\Importr\Feature;

use HDNET\Importr\Processor\Target;
use HDNET\Importr\Service\PasswordHashService;

/**
 * Class HashPassword.
 */
class HashPassword
{
    /**
     * @var PasswordHashService
     */
    protected $hashService;

    /**
     * RenameFile constructor.
     *
     * @param PasswordHashService $hashService
     */
    public function __construct(PasswordHashService $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * @return void
     */
    public static function enable()
    {
        FeatureRegistry::enable('preProcess', Target::class);
    }

    /**
     * @param array $configuration
     * @param array $entry
     *
     * @return array
     */
    public function execute(array $configuration, array $entry)
    {
        if (isset($configuration['filter']['password']) && in_array($configuration['filter']['password'], $configuration['mapping'])) {
            $field = array_search($configuration['filter']['password'], $configuration['mapping']);
            $entry[$field] = $this->hashService->hash($entry[$field]);
        }

        return [$configuration, $entry];
    }
}