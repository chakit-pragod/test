<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PrestaShop\PrestaShop\Adapter\CMS\PageCategory\CommandHandler;

use CMSCategory;
use PrestaShop\PrestaShop\Core\CommandBus\Attributes\AsCommandHandler;
use PrestaShop\PrestaShop\Core\Domain\CmsPageCategory\Command\DeleteCmsPageCategoryCommand;
use PrestaShop\PrestaShop\Core\Domain\CmsPageCategory\CommandHandler\DeleteCmsPageCategoryHandlerInterface;
use PrestaShop\PrestaShop\Core\Domain\CmsPageCategory\Exception\CannotDeleteCmsPageCategoryException;
use PrestaShop\PrestaShop\Core\Domain\CmsPageCategory\Exception\CmsPageCategoryException;
use PrestaShop\PrestaShop\Core\Domain\CmsPageCategory\Exception\CmsPageCategoryNotFoundException;
use PrestaShopException;

/**
 * Class DeleteCmsPageCategoryHandler is responsible for deleting cms page category.
 */
#[AsCommandHandler]
final class DeleteCmsPageCategoryHandler implements DeleteCmsPageCategoryHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws CmsPageCategoryException
     */
    public function handle(DeleteCmsPageCategoryCommand $command)
    {
        try {
            $entity = new CMSCategory($command->getCmsPageCategoryId()->getValue());

            if (0 >= $entity->id) {
                throw new CmsPageCategoryNotFoundException(sprintf('Cms category object with id "%s" has not been found for deletion.', $command->getCmsPageCategoryId()->getValue()));
            }

            if (false === $entity->delete()) {
                throw new CannotDeleteCmsPageCategoryException(sprintf('Unable to delete cms category object with id "%s"', $command->getCmsPageCategoryId()->getValue()), CannotDeleteCmsPageCategoryException::FAILED_DELETE);
            }
        } catch (PrestaShopException $exception) {
            throw new CmsPageCategoryException(sprintf('An error occurred when deleting cms category object with id "%s"', $command->getCmsPageCategoryId()->getValue()), 0, $exception);
        }
    }
}
