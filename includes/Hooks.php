<?php

namespace MediaWiki\Extension\NostrLogin;

use ContextSource;
use MediaWiki\Hook\SkinTemplateNavigation__UniversalHook;
use SkinTemplate;

class Hooks implements
    SkinTemplateNavigation__UniversalHook
{
    /**
     * Handler for SkinTemplateNavigation__UniversalHook.
     * Add a "Dark mode" item to the personal links (usually at the top),
     *   if DarkModeTogglePosition is set to 'personal'.
     *
     * @param SkinTemplate $skin
     * @param array &$links
     * @return void This hook must not abort, it must return no value
     * @phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
     */
    public function onSkinTemplateNavigation__Universal($skin, &$links): void
    {

        $insertUrls = [
            'nostrlogin' => [
                'text' => $skin->msg('nostrlogin-login-button')->text(),
                'href' => './Special:NostrLogin'
            ],
        ];

        // Adjust placement based on whether user is logged in or out.
        if (array_key_exists('mytalk', $links['user-menu'])) {
            $after = 'mytalk';
        } elseif (array_key_exists('anontalk', $links['user-menu'])) {
            $after = 'anontalk';
        } else {
            // Fallback to showing at the end.
            $after = false;
            $links['user-menu'] += $insertUrls;
        }

        if ($after) {
            $links['user-menu'] = wfArrayInsertAfter($links['user-menu'], $insertUrls, $after);
        }
    }

}