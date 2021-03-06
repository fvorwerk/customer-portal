<?php

namespace Oro\Bundle\CustomerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;

class Configuration implements ConfigurationInterface
{
    /**
     * @internal
     */
    const DEFAULT_REGISTRATION_INSTRUCTIONS_TEXT
        = 'To register for a new account, contact a sales representative at 1 (800) 555-0123';

    const USER_MENU_SHOW_ITEMS_ALL_AT_ONCE = 'all_at_once';
    const USER_MENU_SHOW_ITEMS_SUBITEMS_IN_POPUP = 'subitems_in_popup';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(OroCustomerExtension::ALIAS);

        SettingsBuilder::append(
            $rootNode,
            [
                'default_customer_owner' => ['type' => 'string', 'value' => 1],
                'anonymous_customer_group' => ['type' => 'integer', 'value' => null],
                'registration_allowed' => ['type' => 'boolean', 'value' => true],
                'confirmation_required' => ['type' => 'boolean', 'value' => true],
                'send_password_in_welcome_email' => ['type' => 'boolean', 'value' => false],
                'registration_instructions_enabled' => ['type' => 'boolean', 'value' => false],
                'registration_instructions_text' => [
                    'type' => 'textarea',
                    'value' => self::DEFAULT_REGISTRATION_INSTRUCTIONS_TEXT,
                ],
                'user_menu_show_items' => ['type' => 'string', 'value' => self::USER_MENU_SHOW_ITEMS_ALL_AT_ONCE],
            ]
        );

        return $treeBuilder;
    }
}
