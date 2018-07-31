<?php
/**
 * Created by PhpStorm.
 * User: Mindaugas
 * Date: 07/28/18
 * Time: 20:50
 */

namespace AppBundle\Services;

use Github\Client;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class GithubIssues
 * @package AppBundle\Services
 */
class GithubIssues
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $githubSecret;

    /**
     * GithubIssues constructor.
     * @param Session $session
     * @param Client $client
     * @param $githubSecret
     */
    public function __construct(Session $session, Client $client, $githubSecret)
    {
        $this->session = $session;
        $this->client = $client;
        $this->githubSecret = $githubSecret;
    }

    /**
     * @param $repo
     * @param $id
     * @return mixed
     */
    public function getIssue($repo, $id)
    {
        $this->client->authenticate($this->session->get('oAuthToken'), $this->githubSecret, 'http_token' );
        $issue = $this->client->api('issue')->show($this->session->get('nickname'), $repo, $id);
        if ($issue['comments'] > 0) {
            $issue['comments_array'] = $this->client->api('issue')->comments()->all($this->session->get('nickname'), $repo, $id);
        }

        return $issue;
    }

    /**
     * @param $state
     * @return array
     */
    public function getIssues($state)
    {
        $newItems = [];
        $issues = [];
        $this->client->authenticate($this->session->get('oAuthToken'), $this->githubSecret, 'http_token' );
        $repositories = $this->client->api('user')->repositories($this->session->get('nickname'));
        foreach ($repositories as $key => $repository) {
            $items = $this->client->api('issue')->all($this->session->get('nickname'), $repository['name'], array('state' => $state));
            foreach ($items as $k => $item) {
                $item['repository'] = $repository['name'];
                $newItems[] = $item;
            }
            $issues += $newItems;
        }

        return $issues;
    }
}