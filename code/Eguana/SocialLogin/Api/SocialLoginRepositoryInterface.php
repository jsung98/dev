<?php
/**
 * @author Eguana Team
 * @copyright Copyright (c) 2020 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Abbas Ali Butt
 * Date: 12/01/2023
 * Time: 6:11 PM
 */
namespace Eguana\SocialLogin\Api;

use Eguana\SocialLogin\Api\Data\SocialLoginInterface;

/**
 * Interface SocialLoginRepositoryInterface
 *
 */
interface SocialLoginRepositoryInterface
{
    /**
     * @param int $id
     * @return SocialLoginInterface
     */
    public function getById($id);

    /**
     * @param SocialLoginInterface $socialLogin
     * @return SocialLoginInterface
     */
    public function save(SocialLoginInterface $socialLogin);

    /**
     * @param SocialLoginInterface $socialLogin
     * @return void
     */
    public function delete(SocialLoginInterface $socialLogin);
}
