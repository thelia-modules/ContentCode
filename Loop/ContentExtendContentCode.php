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

use ContentCode\Model\Map\ContentCodeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Content;
use Thelia\Model\Map\ContentTableMap;
use Thelia\Type\TypeCollection;
use Thelia\Type;

/**
 * Class ContentExtendContentCode
 * @package ContentCode\Loop
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class ContentExtendContentCode extends Content implements PropelSearchLoopInterface
{
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
        return parent::getArgDefinitions()
            ->addArgument(
                new Argument(
                    'order',
                    new TypeCollection(
                        new Type\EnumListType(
                            array(
                                'alpha',
                                'alpha-reverse',
                                'manual',
                                'manual_reverse',
                                'random',
                                'given_id',
                                'created',
                                'created_reverse',
                                'updated',
                                'updated_reverse',
                                'position',
                                'position_reverse',
                                'code',
                                'code_reverse'
                            )
                        )
                    ),
                    'alpha'
                )
            )
            ->addArgument(Argument::createAnyListTypeArgument("code"));
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $query = parent::buildModelCriteria();

        if (null !== $codes = $this->getCode()) {
            $join = new Join();

            $join->addExplicitCondition(
                ContentTableMap::TABLE_NAME,
                'ID',
                null,
                ContentCodeTableMap::TABLE_NAME,
                'CONTENT_ID',
                null
            );

            $join->setJoinType(Criteria::JOIN);

            $query->addJoinObject($join, 'content_extend_content_code');

            $query->addJoinCondition(
                'content_extend_content_code',
                ContentCodeTableMap::CODE . ' IN ('. self::formatParamIn($codes) .')'
            );

            if (null !== $orders = $this->getOrder()) {
                foreach ($orders as $order) {
                    switch ($order) {
                        case "code":
                            $query->addAscendingOrderByColumn(ContentCodeTableMap::CODE);
                            break;
                        case "code_reverse":
                            $query->addDescendingOrderByColumn(ContentCodeTableMap::CODE);
                            break;
                    }
                }
            }
        }

        return $query;
    }

    /**
     * @param array $values
     * @return string
     */
    protected function formatParamIn(array $values)
    {
        array_walk(
            $values,
            function(&$value) {
                $value = "'" . addslashes($value) . "'";
            }
        );

        return implode(',', $values);
    }
}
