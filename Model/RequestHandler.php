<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Theiconnz\Redirectpages\Model;

use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * @inheritdoc
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @var ActionFlag
     */
    private $actionFlag;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param MessageManagerInterface $messageManager
     * @param ActionFlag $actionFlag
     * @param LoggerInterface $logger
     */
    public function __construct(
        MessageManagerInterface $messageManager,
        ActionFlag $actionFlag,
        LoggerInterface $logger
    ) {
        $this->messageManager = $messageManager;
        $this->actionFlag = $actionFlag;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(
        RequestInterface $request,
        HttpResponseInterface $response,
        string $redirectOnFailureUrl
    ): void {
        $this->processError($response, $redirectOnFailureUrl);
    }

    /**
     * Process errors from reCAPTCHA response.
     *
     * @param HttpResponseInterface $response
     * @param string $redirectOnFailureUrl
     */
    private function processError(
        HttpResponseInterface $response,
        string $redirectOnFailureUrl
    ): void {
        $response->setRedirect($redirectOnFailureUrl);
    }
}
