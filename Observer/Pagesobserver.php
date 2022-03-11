<?php
/**
 * KOTA FACTORY Limited.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * https://github.com/theiconnz/redirectpages/wiki
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to info@kota-factory.com so we can send you a copy immediately.
 *
 * @package     Theiconnz_Redirectpages
 * @copyright   Copyright (c) 2022 KOTA FACTORY Limited. (https://kota-factory.com)
 * @license     https://github.com/theiconnz/redirectpages/wiki End-user License Agreement
 */
namespace Theiconnz\Redirectpages\Observer;

use Theiconnz\Redirectpages\Model\RequestHandlerInterface as RequestHandlerInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Page;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\Event\Observer;

use Magento\Framework\UrlInterface;

/**
* Page Observer
*/
class Pagesobserver implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Kotafactory\Pages\Helper\Config
     */
    private $helper;

    /**
     * @var Page
     */
    private $pageRepository;


    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;
    /**
     * @param \Kotafactory\Pages\Helper\Config $helper
     * @param ActionFlag $actionFlag
     * @param UrlInterface $url
     * @param RequestHandlerInterface $requestHandler
     * @parm PageRepositoryInterface $pageRepository
     */
    public function __construct(
        \Theiconnz\Redirectpages\Helper\Config $helper,
        ActionFlag $actionFlag,
        UrlInterface $url,
        RequestHandlerInterface $requestHandler,
        PageRepositoryInterface $pageRepository
    ) {
        $this->actionFlag = $actionFlag;
        $this->url = $url;
        $this->helper = $helper;
        $this->requestHandler = $requestHandler;
        $this->pageRepository = $pageRepository;
    }
    /**
     * Redirect to pages
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->helper->isEnable()) {
            return;
        }

        $controller = $observer->getControllerAction();
        $request = $controller->getRequest();
        $response = $controller->getResponse();
        $requestPath = $request->getPathInfo();
        $currentUrl = $this->url->getUrl('*', ['_current' => true, '_use_rewrite' => true]);
        $pageidentity='';
        if($pageId = $request->getParam('page_id')) {
            if ($pageId) {
                $page = $this->pageRepository->getById($pageId);
                $pageidentity = $page->getIdentifier();
            }
        }
        $clientip = $this->helper->getClientIp();


        if($maintenancepage = $this->helper->getRedirectforMaintenance()){
            $redirectUrl = $this->url->getUrl($maintenancepage, ['_secure' => true]);
            if( !strstr($requestPath, $maintenancepage)) {
                if (!empty($pageidentity) && $pageidentity===$maintenancepage) {
                    return;
                } else {
                    if($currentUrl!=$redirectUrl) {
                        $ips = explode(",", $this->helper->getAllowedMaintenanceIps());
                        if (!in_array($clientip, $ips)) {
                            $this->requestHandler->execute($request, $response, $redirectUrl);
                        }
                    }
                }
            }
        }

        if($commingsoonpage = $this->helper->getRedirectforCommingSoon()){
            $redirectUrl = $this->url->getUrl($commingsoonpage, ['_secure' => true]);

            if( !strstr($requestPath, $commingsoonpage)) {
                if (!empty($pageidentity) && $pageidentity===$commingsoonpage) {
                    return;
                } else {
                    if($currentUrl!=$redirectUrl) {
                        $ips = explode(",", $this->helper->getAllowedCommingsoonIps());
                        if (!in_array($clientip, $ips)) {
                            $this->requestHandler->execute($request, $response, $redirectUrl);
                        }
                    }
                }
            }
        }
    }
}
