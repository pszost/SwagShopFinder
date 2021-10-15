<?php declare(strict_types=1);

namespace SwagShopFinder\Core\Api;

use Faker\Factory;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Country\Exception\CountryNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class DemoDataController extends AbstractController
{
    /**
     * @var EntityRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $shopfinderRepository;

    /**
     * DemoDataController constructor.
     * @param EntityRepositoryInterface $countryRepository
     * @param EntityRepositoryInterface $shopfinderRepository
     */
    public function __construct(EntityRepositoryInterface $countryRepository, EntityRepositoryInterface $shopfinderRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->shopfinderRepository = $shopfinderRepository;
    }

    /**
     * @Route("/api/v{version}/_action/swag-shop-finder/generate", name="api.custom.swag_shop_finder.generate", methods={"POST"})
     * @param Context $context
     * @return Response
     * @throws CountryNotFoundException
     * @throws InconsistentCriteriaIdsException
     */
    public function generate(Context $context) : Response
    {
        $faker = Factory::create();
        $country = $this->getActiveCountry($context);

        $data = [];
        foreach (range(0, 50) as $i) {
            $data[] = [
                'id' => Uuid::randomHex(),
                'active' => true,
                'name' => $faker->name,
                'street' => $faker->streetAddress,
                'post_code' => $faker->postcode,
                'city' => $faker->city,
                'telephone' => $faker->phoneNumber,
                'countryId' => $country->getId()
            ];
        }
        $this->shopfinderRepository->create($data, $context);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Context $context
     * @return CountryEntity
     * @throws CountryNotFoundException
     * @throws InconsistentCriteriaIdsException
     */
    private function getActiveCountry(Context $context): CountryEntity
    {
        $criteria = New Criteria();
        $criteria->addFilter(new EqualsFilter('active', '1'));
        $criteria->setLimit(1);

        $country = $this->countryRepository->search($criteria, $context)->getEntities()->first();
        if ($country == null) {
            throw new CountryNotFoundException('');
        }

        return $country;
    }
}
