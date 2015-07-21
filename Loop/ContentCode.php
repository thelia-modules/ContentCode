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

namespace ContentCode\Loop;

use ContentCode\Model\ContentCodeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class ContentCode
 * @package ContentCode\Loop
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class ContentCode extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \ContentCode\Model\ContentCode $contentCode */
        foreach ($loopResult->getResultDataCollection() as $contentCode) {
            $row = new LoopResultRow($contentCode);

            $row
                ->set("ID", $contentCode->getId())
                ->set("CONTENT_ID", $contentCode->getContentId())
                ->set("CODE", $contentCode->getCode())
            ;
        }

        return $loopResult;
    }

    /**
     * Definition of loop arguments
     *
     * example :
     *
     * public function getArgDefinitions()
     * {
     *  return new ArgumentCollection(
     *
     *       Argument::createIntListTypeArgument('id'),
     *           new Argument(
     *           'ref',
     *           new TypeCollection(
     *               new Type\AlphaNumStringListType()
     *           )
     *       ),
     *       Argument::createIntListTypeArgument('category'),
     *       Argument::createBooleanTypeArgument('new'),
     *       ...
     *   );
     * }
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument("id"),
            Argument::createIntListTypeArgument("content_id"),
            Argument::createAnyListTypeArgument("code"),
            Argument::createEnumListTypeArgument(
                "order",
                ["id", "id-reverse", "content_id", "content_id-reverse", "code", "code-reverse"],
                "id"
            )
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $query = ContentCodeQuery::create();

        if (null !== $ids = $this->gedId()) {
            $query->filterById($ids);
        }

        if (null !== $contentIds = $this->getContentId()) {
            $query->filterByContentId($contentIds);
        }

        if (null !== $codes = $this->getCode()) {
            $query->filterByCode($codes);
        }

        foreach ($this->getOrder() as $order) {
            switch ($order) {
                case "id":
                    $query->orderById();
                    break;

                case "id-reverse":
                    $query->orderById(Criteria::DESC);
                    break;

                case "content_id":
                    $query->orderByContentId();
                    break;

                case "content_id-reverse":
                    $query->orderByContentId(Criteria::DESC);
                    break;

                case "code":
                    $query->orderByCode();
                    break;

                case "code-reverse":
                    $query->orderByCode(Criteria::DESC);
                    break;

            }
        }

        return $query;
    }
}
