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

namespace ContentCode\Form;

use ContentCode\ContentCode;
use ContentCode\Model\ContentCodeQuery;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Form\BaseForm;

/**
 * Class ContentCodeForm.
 *
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class ContentCodeForm extends BaseForm
{
    /**
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :.
     *
     * $this->formBuilder->add("name", "text")
     *   ->add("email", "email", array(
     *           "attr" => array(
     *               "class" => "field"
     *           ),
     *           "label" => "email",
     *           "constraints" => array(
     *               new \Symfony\Component\Validator\Constraints\NotBlank()
     *           )
     *       )
     *   )
     *   ->add('age', 'integer');
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add('content_id', 'content_id')
            ->add('code', 'text', [
                'required' => true,
                'constraints' => [
                    new Callback([
                        'methods' => [
                            [$this, 'checkExistingCode'],
                        ],
                    ]),
                ],
            ])
        ;
    }

    public function checkExistingCode($value, ExecutionContextInterface $context)
    {
        $data = $this->getForm()->getData();

        if (null !== $data['code'] && '' !== $data['code']) {
            $currentCode = ContentCodeQuery::create()->findOneByContentId($data['content_id']);
            $existingCode = ContentCodeQuery::create()->findOneByCode($data['code']);

            if (null !== $currentCode && null !== $existingCode && $currentCode->getCode() !== $existingCode->getCode()) {
                $context->addViolation(
                    $this->translator->trans(
                        "The given code '%code' is already assigned to another content",
                        ['%code' => $data['code']],
                        ContentCode::DOMAIN_NAME
                    )
                );
            }
        }
    }
}
