<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Api\Project;
use App\Entity\Api\Task;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface/*, QueryItemExtensionInterface*/
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

//    public function applyToItem(
//        QueryBuilder $queryBuilder,
//        QueryNameGeneratorInterface $queryNameGenerator,
//        string $resourceClass,
//        array $identifiers,
//        string $operationName = null,
//        array $context = []
//    ) {
//        $this->addWhere($queryBuilder, $resourceClass);
//    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($resourceClass == Project::class) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
            $queryBuilder->setParameter('current_user', $user->getId());
        } elseif ($resourceClass == Task::class) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder
                ->innerJoin($rootAlias.'.project', 'p')
                ->where('p.user = :current_user')
                ->setParameter('current_user', $user->getId());
        }
    }
}
