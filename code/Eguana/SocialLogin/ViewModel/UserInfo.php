<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Abbas Ali Butt
 * Date: 13/01/2023
 * Time: 3:58 PM
 */

namespace Eguana\SocialLogin\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\UrlInterface;
use Eguana\SocialLogin\Model\SocialLoginHandler as SocialLoginModel;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;

class UserInfo implements ArgumentInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlInterface
     */
    private $getUrl;

    /**
     * @var SocialLoginModel
     */
    private $socialLoginModel;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Context
     */
    private $context;

    /**
     * GetUrl constructor.
     * @param UrlInterface $getUrl
     * @param RequestInterface $request
     * @param SocialLoginModel $socialLoginModel
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UrlInterface    $getUrl,
        RequestInterface $request,
        SocialLoginModel $socialLoginModel,
        StoreManagerInterface $storeManager
    )
    {
        $this->getUrl = $getUrl;
        $this->socialLoginModel = $socialLoginModel;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * Get current store id
     *
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Get current store code
     *
     * @return string
     */
    public function getCurrentStoreCode()
    {
        return $this->storeManager->getStore()->getCode();
    }

    /**
     * Get current store name
     *
     * @return string
     */
    public function getCurrentStoreName()
    {
        return $this->storeManager->getStore()->getName();
    }

    /**
     * Check current store status
     *
     * @return bool
     */
    public function isCurrentStoreActive()
    {
        return $this->storeManager->getStore()->isActive();
    }

    /**
     * Get current store URL
     *
     * @return string
     */
    public function getCurrentStoreUrl()
    {
        return $this->storeManager->getStore()->getCurrentUrl();
    }

    /**
     * Get current website ID
     *
     * @return int
     */
    public function getCurrentWebsiteId()
    {
        return $this->storeManager->getStore()->getWebsiteId();
    }

    /**
     * getUrl()
     */
    public function getUrl()
    {
        $socialData = $this->request->getParams();
        $type = isset($socialData["type"])?$socialData["type"]:'';
        $name = isset($socialData["name"])?$socialData["name"]:'';

        $email = '';

        $url = $this->getUrl->getUrl('customer/account/create',
            [
                'type'=> $type,
                'name'=> $name,
                'email'=> $email,
            ]);


        return $url;
    }

    /**
     * get name
     */
    public function getName(){
        $socialData = $this->request->getParams();
        if($socialData==null){
            return null;
        }
        else{
            if (array_key_exists('name', $socialData)) {
                return $socialData["name"];
            }
            else{
                return null;
            }
        }
    }

    /**
     * @param $name
     * @return array
     */
    public function getFirstLastName($name)
    {
        if(preg_match('/[\x{3130}-\x{318F}\x{AC00}-\x{D7AF}]/u', $name))
        {
            $arrName = preg_split('//u', $name, -1, PREG_SPLIT_NO_EMPTY);

            if (sizeof($arrName) >= 1) {
                $lastName = $arrName[0];
                $firstName = '';
                for($i = 1; $i < sizeof($arrName); $i++) {
                    $firstName .= $arrName[$i];
                }
            }else {
                $firstName = trim($name, $arrName[0]);
                $lastName = $arrName[0];
            }
            return [
                'first' => $firstName,
                'last' => $lastName

            ];

        }
        else{
            $arrParts = explode(' ', $name);
            $deleteLast = array_pop($arrParts);

            $arrName = array(implode(' ', $arrParts), $deleteLast);

            $firstName = $arrName[0];
            $lastName = $arrName[1];


            return [
                'first' => $firstName,
                'last' => $lastName
            ];

        }

    }
    /**
     * get Gender
     */
    public function getGender(){
        $socialData = $this->request->getParams();
        if($socialData==null){
            return null;
        }
        else{
            if (array_key_exists('gender', $socialData)) {
                return $socialData["gender"];
            }
            else{
                return null;
            }
        }
    }

    /**
     * get Data of Birth
     */

    public function getBirthday(){
        $socialData = $this->request->getParams();
        if($socialData==null){
            return null;
        }
        else{
            if (array_key_exists('birthday', $socialData)) {
                return $socialData["birthday"];
            }
            else{
                return null;
            }
        }
    }
    /**
     * get Data of Birth
     */

    public function getBirthyear(){
        $socialData = $this->request->getParams();
        if($socialData==null){
            return null;
        }
        else{
            if (array_key_exists('birthyear', $socialData)) {
                return $socialData["birthyear"];
            }
            else{
                return null;
            }
        }
    }

    /**
     * get Phone
     */

    public function getPhoneNumber(){
        $socialData = $this->request->getParams();
        if($socialData==null){
            return null;
        }
        else{
            if (array_key_exists('phone_number', $socialData)) {
                $phone_number2 = preg_replace("/\s+/", "", $socialData["phone_number"]);
                $phone_number =  str_replace(array('+82','-','+92',' '), array('0','','0',''),$phone_number2);
                return $phone_number;
            }
            else{
                return null;
            }
        }
    }
    /**
     * get email
     */
    public function getEmail(){
        $socialData = $this->request->getParams();
        if($socialData==null){
            return null;
        }
        else {
            if (array_key_exists('email', $socialData)) {
                return $socialData["email"];
            } else {
                return null;
            }
        }
    }

    /**
     * get Social Media Type
     */
    public function getMediaType(){
        $socialData = $this->request->getParams();
        if($socialData==null){
            return null;
        }
        else {
            if (array_key_exists('socialmedia_type', $socialData)) {
                return $socialData["socialmedia_type"];
            } else {
                return null;
            }
        }
    }


}
