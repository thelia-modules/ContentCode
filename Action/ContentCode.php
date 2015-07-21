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

namespace ContentCode\Action;

use ContentCode\Event\ContentCodeEvent;
use ContentCode\Event\ContentCodeEvents;
use ContentCode\Model\ContentCodeQuery;
use Propel\Runtime\Propel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ContentCode\Model\ContentCode as ContentCodeModel;

/**
 * Class ContentCode.
 *
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class ContentCode implements EventSubscriberInterface
{
    /** @var \PDO */
    private $con;

    public function setCodeAction(ContentCodeEvent $event)
    {
        $contentCode = ContentCodeQuery::create()->findOneByContentId($event->getContentId());

        if (null === $contentCode) {
            $contentCode = new ContentCodeModel();
            $contentCode->setContentId($event->getContentId());
        }

        $code = '' === $event->getCode() ? null : $event->getCode();
        $contentCode->setCode($code);

        try {
            $contentCode->save($this->con);
        } catch (\Exception $e) {
            $this->con->rollback();

            throw $e;
        }
    }

    public function startTransaction()
    {
        $this->con = Propel::getConnection();

        $this->con->beginTransaction();
    }

    public function commit()
    {
        try {
            $this->con->commit();
        } catch (\Exception $e) {
            $this->con->rollback();

            throw $e;
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            ContentCodeEvents::SET_CODE => [
                ['startTransaction', 256],
                ['setCodeAction', 128],
                ['commit', 0],
            ],
        ];
    }
}
