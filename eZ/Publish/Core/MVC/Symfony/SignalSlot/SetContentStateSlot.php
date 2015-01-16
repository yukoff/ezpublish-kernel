<?php
/**
 * File containing the Legacy\SetContentStateSlot class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace eZ\Publish\Core\MVC\Symfony\SignalSlot;

use eZ\Publish\Core\SignalSlot\Signal;

/**
 * A slot handling SetContentStateSignal.
 */
class SetContentStateSlot extends AbstractSlot
{
    /**
     * Receive the given $signal and react on it
     *
     * @param \eZ\Publish\Core\SignalSlot\Signal $signal
     *
     * @return void
     */
    public function receive( Signal $signal )
    {
        if ( !$signal instanceof Signal\ObjectStateService\SetContentStateSignal )
            return;

        $this->httpCacheClearer->purge( $this->getLocationId( $signal->contentId ) );
    }
}