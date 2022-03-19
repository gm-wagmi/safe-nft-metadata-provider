<?php

/*
 * This file is part of the Safe NFT Metadata Provider package.
 *
 * (c) Marco Lipparini <developer@liarco.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Contract;

use App\Service\CollectionManager;
use App\TotalSupplyProvider\CachedTotalSupplyProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @author Marco Lipparini <developer@liarco.net>
 */
abstract class AbstractNftController extends AbstractController
{
    public function __construct(
        protected readonly CollectionManager $collectionManager,
        protected readonly CachedTotalSupplyProvider $cachedTotalSupplyProvider,
        protected readonly UrlGeneratorInterface $urlGenerator,
        protected readonly CacheInterface $cache,
    ) {
    }

    protected function isValidTokenId(int $tokenId): bool
    {
        $isRevealed = $this->getParameter('app.collection_is_revealed');
        $firstIndex = $this->getParameter('app.collection_first_index');
        $lastMintedIndex = $firstIndex + $this->cachedTotalSupplyProvider->getTotalSupply() - 1;

        print "FIRST INDEX $firstIndex"
        print "LAST INDEX $lastMintedIndex"

        return $isRevealed && $tokenId >= $firstIndex && $tokenId <= $lastMintedIndex;
    }

    protected function getDefaultCacheExpiration(): int
    {
        return (int) $this->getParameter('app.cache_expiration');
    }
}
