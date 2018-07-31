<?php
/**
 * Created by PhpStorm.
 * User: Mindaugas
 * Date: 07/28/18
 * Time: 00:07
 */

namespace AppBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use AppBundle\Entity\User;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;

/**
 * Class UserProvider
 * @package AppBundle\Security
 */
class UserProvider extends OAuthUserProvider
{
    protected $session, $doctrine, $admins;

    public function __construct($session, $doctrine, $service_container)
    {
        $this->session = $session;
        $this->doctrine = $doctrine;
        $this->container = $service_container;
    }

    public function loadUserByUsername($username)
    {
        $qb = $this->doctrine->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.githubId = :gid')
            ->setParameter('gid', $username)
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();

        if (count($result)) {
            return $result[0];
        } else {
            return new User();
        }
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $githubId = $response->getUsername();
        $email = $response->getEmail();
        $nickname = $response->getNickname();
        $name = $response->getRealName();
        $oAuthToken = $response->getOAuthToken()->getAccessToken();

        //set data in session
        $this->session->set('email', $email);
        $this->session->set('nickname', $nickname);
        $this->session->set('name', $name);
        $this->session->set('oAuthToken', $oAuthToken);

        $qb = $this->doctrine->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.githubId = :gid')
            ->setParameter('gid', $githubId)
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();

        if (!count($result)) {
            $user = new User();
            $user->setGithubId($githubId);
            $user->setName($name);
            $user->setNickname($nickname);
            $user->setEmail($email);
            $user->setGithubId($githubId);
            $user->setRole('ROLE_USER');
            $user->setGithubAccessToken($oAuthToken);

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
        } else {
            $user = $result[0];
        }

        $this->session->set('id', $user->getId());

        return $this->loadUserByUsername($response->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'AppBundle\\Entity\\User';
    }
}