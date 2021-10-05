<?php

declare(strict_types=1);


use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooterSubscriber implements EventSubscriberInterface
{

    /** @var SystemConfigService */
    private $systemConfigService;

    /** @var EntityRepositoryInterface */
    private $shopFinderService;


    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $shopFinderService
    )
    {
        $this->systemConfigService = $systemConfigService;
        $this->shopFinderService = $shopFinderService;
    }


    public static function getSubscribedEvents()
    {
        return [
          FooterPageletLoadedEvent::class => 'onFooterPageletLoaded',
        ];
    }
    public function onFooterPageletLoaded(FooterPageletLoadedEvent $event){

    }
}
