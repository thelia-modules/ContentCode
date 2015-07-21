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

namespace ContentCode\Controller;

use ContentCode\Event\ContentCodeEvent;
use ContentCode\Event\ContentCodeEvents;
use Thelia\Controller\Admin\BaseAdminController;

/**
 * Class ContentCodeController.
 *
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class ContentCodeController extends BaseAdminController
{
    public function saveContentCodeAction()
    {
        $baseForm = $this->createForm('content-code-form');

        try {
            $form = $this->validateForm($baseForm);
            $data = $form->getData();

            $this->dispatch(ContentCodeEvents::SET_CODE, new ContentCodeEvent($data['content_id'], $data['code']));
        } catch (\Exception $e) {
            $this->setupFormErrorContext('set code', $e->getMessage(), $baseForm, $e);

            return $this->generateErrorRedirect($baseForm);
        }

        return $this->generateSuccessRedirect($baseForm);
    }
}
