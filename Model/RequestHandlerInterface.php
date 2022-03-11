<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Theiconnz\Redirectpages\Model;

use Magento\Framework\App\PlainTextRequestInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;
use Magento\Framework\Exception\InputException;

/**
 * Request handler interface (sugar service for avoiding boilerplate code)
 *
 * Validate reCAPTCHA data in request, set message and redirect if validation was failed
 *
 * @api
 */
interface RequestHandlerInterface
{
    /**
     * Validate reCAPTCHA data in request, set message and redirect if validation was failed
     *
     * @param RequestInterface|PlainTextRequestInterface $request
     * @param HttpResponseInterface $response
     * @param string $redirectOnFailureUrl
     * @return void
     * @throws InputException
     */
    public function execute(
        RequestInterface $request,
        HttpResponseInterface $response,
        string $redirectOnFailureUrl
    ): void;
}
