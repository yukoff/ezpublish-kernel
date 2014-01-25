<?php
/**
 * UnassignContentTypeGroupSignal class
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Repository\SignalSlot\Signal\ContentTypeService;

use eZ\Publish\Core\Repository\SignalSlot\Signal;

/**
 * UnassignContentTypeGroupSignal class
 * @package eZ\Publish\Core\Repository\SignalSlot\Signal\ContentTypeService
 */
class UnassignContentTypeGroupSignal extends Signal
{
    /**
     * ContentTypeId
     *
     * @var mixed
     */
    public $contentTypeId;

    /**
     * ContentTypeGroupId
     *
     * @var mixed
     */
    public $contentTypeGroupId;
}