<?php

declare(strict_types=1);

namespace Bcs\OSIBundle\Hook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Form;
use Contao\Widget;

use Terminal42\NotificationCenterBundle\NotificationCenter;

/**
 * Provides several function to access leads hooks and send notifications.
 */
#[AsHook('prepareFormData')]
class PrepareFormDataHook
{
    private $notificationCenter;
    /**
     * @param array<mixed>  $submittedData
     * @param array<mixed>  $labels
     * @param array<Widget> $fields
     */
    public function __construct(NotificationCenter $notificationCenter)
    {
        $this->notificationCenter = $notificationCenter;
    }
    
    public function __invoke(array &$submittedData, array $labels, array $fields, Form $form): void
    {
        echo "FINALLY!";
        die();
    }
}
