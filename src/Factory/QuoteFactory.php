<?php

namespace App\Factory;

use App\Entity\Category;
use App\Entity\Quote;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Quote>
 *
 * @method static      Quote|Proxy createOne(array $attributes = [])
 * @method static      Quote[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static      Quote|Proxy find(object|array|mixed $criteria)
 * @method static      Quote|Proxy findOrCreate(array $attributes)
 * @method static      Quote|Proxy first(string $sortedField = 'id')
 * @method static      Quote|Proxy last(string $sortedField = 'id')
 * @method static      Quote|Proxy random(array $attributes = [])
 * @method static      Quote|Proxy randomOrCreate(array $attributes = [])
 * @method static      Quote[]|Proxy[] all()
 * @method static      Quote[]|Proxy[] findBy(array $attributes)
 * @method static      Quote[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static      Quote[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static      QuoteRepository|RepositoryProxy repository()
 * @method Quote|Proxy create(array|callable $attributes = [])
 */
final class QuoteFactory extends ModelFactory
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function getDefaults(): array
    {
        $author = UserFactory::createOne();

        return [
            'content' => self::faker()->text(),
            'meta' => self::faker()->text(30),
            'category' => $this->em->getRepository(Category::class)->findOneBy(['id' => rand(1, 20)]),
            'author' => $author,
            'dateCreation' => self::faker()->dateTime,
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Quote $quote) {})
        ;
    }

    protected static function getClass(): string
    {
        return Quote::class;
    }
}
