<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-leads-optin-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2025, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @author     Christopher Bölter
 * @author     Carsten Götzinger
 * @license    LGPL-3.0-or-later
 */

namespace Bcs\OSI\Hook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Form;
use Contao\Widget;

use Bcs\Backend\SendFormEmail;

use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FormFieldModel;
use Contao\FrontendUser;
use Contao\Input;
use Contao\MemberModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use DateTime;

use Terminal42\NotificationCenterBundle\NotificationCenter;
/**
 * Provides several function to access leads hooks and send notifications.
 */
#[AsHook('prepareFormData')]
class PrepareFormDataHook
{

    public function __construct(
        private readonly NotificationCenter $notificationCenter,
        private readonly Connection $db,
        private readonly StringParser $stringParser,
    ) {
    }
    /**
     * @param array<mixed>  $submittedData
     * @param array<mixed>  $labels
     * @param array<Widget> $fields
     */
    public function __invoke(array &$answers, array $labels, array $fields, Form $test): void
    {
      
    }
}
