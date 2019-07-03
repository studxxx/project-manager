<?php

declare(strict_types=1);

namespace App\Widget\Work\Members\Member;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StatusWidget extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('member_status', [$this, 'status'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    /**
     * @param Environment $twig
     * @param string $status
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function status(Environment $twig, string $status): string
    {
        return $twig->render('widget/work/members/member/status.html.twig', [
            'status' => $status
        ]);
    }
}
