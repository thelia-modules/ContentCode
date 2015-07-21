<?php

/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ContentCode\Event;

use Thelia\Core\Event\ActionEvent;

/**
 * Class ContentCodeEvent.
 *
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class ContentCodeEvent extends ActionEvent
{
    protected $contentId;
    protected $code;

    /**
     * ContentCodeEvent constructor.
     *
     * @param $contentId
     * @param $code
     */
    public function __construct($contentId, $code)
    {
        $this->contentId = $contentId;
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param mixed $contentId
     *
     * @return $this
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
