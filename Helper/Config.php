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
namespace Theiconnz\Redirectpages\Helper;


use Magento\Store\Model\ScopeInterface;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLE = 'pageredirect/general/enable';
    const XML_PATH_ENABLE_MAINTENANCE = 'pageredirect/general/enablemaintenance';
    const XML_PATH_ENABLE_coming_SOON = 'pageredirect/general/enablecomingsoon';
    const XML_PATH_ENABLE_REDIR_MAIN = 'pageredirect/general/redirect_for_maintenance';
    const XML_PATH_ENABLE_ALLOWDIP_MAIN = 'pageredirect/general/maintenance_allowed_ip';
    const XML_PATH_ENABLE_REDIR_comingSOON = 'pageredirect/general/redirect_for_comingsoon';
    const XML_PATH_ENABLE_ALLOWDIP_comingSOON = 'pageredirect/general/comingsoon_allowed_ip';

    /**
     * Receive magento config value
     *
     * @param string      $path full path, eg: "pr_base/general/enabled"
     * @param string|int  $scopeCode store view code or website code
     * @param string|null $scopeType
     * @return mixed
     */
    public function getConfig($path, $scopeCode = null, $scopeType = null)
    {
        if ($scopeType === null) {
            $scopeType = ScopeInterface::SCOPE_STORE;
        }
        return $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
    }

    /**
     * Receive if module is enabled
     *
     * @param null $scopeCode
     * @param null $scopeType
     * @return int
     */
    public function isEnable($scopeCode = null, $scopeType = null): int
    {
        return (int) $this->getConfig(self::XML_PATH_ENABLE, $scopeCode, $scopeType);
    }

    /**
     * Receive if maintenance enable
     *
     * @param null $scopeCode
     * @param null $scopeType
     * @return int
     */
    public function isEnableMaintenance($scopeCode = null, $scopeType = null): int
    {
        if (!$this->isEnable($scopeCode, $scopeType)){
            return 0;
        }
        return (int) $this->getConfig(self::XML_PATH_ENABLE_MAINTENANCE, $scopeCode, $scopeType);
    }

    /**
     * Receive if coming soon enable
     *
     * @param null $scopeCode
     * @param null $scopeType
     * @return int
     */
    public function isEnablecomingSoon($scopeCode = null, $scopeType = null): int
    {
        if (!$this->isEnable($scopeCode, $scopeType)){
            return 0;
        }
        return (int) $this->getConfig(self::XML_PATH_ENABLE_coming_SOON, $scopeCode, $scopeType);
    }

    /**
     * Get Redirect page for Maintenance
     *
     * @param null $scopeCode
     * @param null $scopeType
     * @return mix
     */
    public function getRedirectforMaintenance($scopeCode = null, $scopeType = null)
    {
        if(!$this->isEnableMaintenance($scopeCode, $scopeType)){
            return false;
        }
        return $this->getConfig(self::XML_PATH_ENABLE_REDIR_MAIN, $scopeCode, $scopeType);
    }

    /**
     * Get Redirect page for coming soon
     *
     * @param null $scopeCode
     * @param null $scopeType
     * @return mix
     */
    public function getRedirectforcomingSoon($scopeCode = null, $scopeType = null)
    {
        if(!$this->isEnablecomingSoon($scopeCode, $scopeType)){
            return false;
        }
        return $this->getConfig(self::XML_PATH_ENABLE_REDIR_comingSOON, $scopeCode, $scopeType);
    }

    /**
     * Get all allowed ip addresses for Maintenance page
     *
     * @param null $scopeCode
     * @param null $scopeType
     * @return mix
     */
    public function getAllowedMaintenanceIps($scopeCode = null, $scopeType = null)
    {
        return $this->getConfig(self::XML_PATH_ENABLE_ALLOWDIP_MAIN, $scopeCode, $scopeType);
    }


    /**
     * Get all allowed ip addresses for coming soon
     *
     * @param null $scopeCode
     * @param null $scopeType
     * @return mix
     */
    public function getAllowedcomingsoonIps($scopeCode = null, $scopeType = null)
    {
        return $this->getConfig(self::XML_PATH_ENABLE_ALLOWDIP_comingSOON, $scopeCode, $scopeType);
    }

    /**
     * Get client IP address
     *
     * @return mix
     */
    public function getClientIp(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){

            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];

        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){

            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        }else{

            $ip = $_SERVER['REMOTE_ADDR'];

        }

        return $ip;
    }
}
