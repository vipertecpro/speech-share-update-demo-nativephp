<?php

namespace App\Layouts;

use Native\Mobile\Edge\Layouts\Builders\Tab;
use Native\Mobile\Edge\Layouts\Builders\TabBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;

/**
 * The two tabs: the example apps and the plugin playgrounds. Each screen
 * supplies its own top bar.
 */
class TabsLayout extends NativeLayout
{
    public function tabBar(NativeComponent $screen): ?TabBar
    {
        return TabBar::make()
            ->activeColor(theme('primary'))
            ->add(Tab::link('Demos', '/', ios: 'square.grid.2x2.fill', android: 'apps'))
            ->add(Tab::link('Playgrounds', '/playgrounds', ios: 'slider.horizontal.3', android: 'tune'));
    }
}
