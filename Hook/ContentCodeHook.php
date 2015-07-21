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

namespace ContentCode\Hook;

use ContentCode\Model\ContentCodeQuery;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class ContentCodeHook.
 *
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class ContentCodeHook extends BaseHook
{
    public function onContentTabContent(HookRenderEvent $event)
    {
        $contentId = $event->getArgument('id');

        $params = [
            'content_id' => $contentId,
            'currentCode' => '',
        ];

        if (null !== $contentCode = ContentCodeQuery::create()->findOneByContentId($contentId)) {
            $params['currentCode'] = $contentCode->getCode();
        }

        $event->add($this->render('content-code/module-hook.html', $params));
    }
}
