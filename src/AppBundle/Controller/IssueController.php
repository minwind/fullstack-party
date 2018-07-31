<?php
/**
 * Created by PhpStorm.
 * User: Mindaugas
 * Date: 07/27/18
 * Time: 17:41
 */

namespace AppBundle\Controller;


use AppBundle\Form\IssueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IssueController extends Controller
{
    /**
     * @Route("/issues/{page}", name="issues", defaults={"page": 1})
     *
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $page)
    {
        $state = 'open';
        $form = $this->createForm(IssueType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $state = $data['choiceField'];
        }

        $paginator = $this->get('knp_paginator');
        $issues = $this->get('github.issues')->getIssues($state);
        return $this->render('@App/issue/list.html.twig', [
            'list' => $paginator->paginate(
                $issues,
                $page,
                20
            ),
            'closedIssue' => count($this->get('github.issues')->getIssues('closed')),
            'openIssue' => count($this->get('github.issues')->getIssues('open')),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/issue-view/{repo}/{number}", name="issue_view")
     *
     * @param $repo
     * @param $number
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($repo, $number)
    {
        return $this->render('@App/issue/view.html.twig', [
                'issue' => $this->get('github.issues')->getIssue($repo, $number),
            ]
        );
    }

}