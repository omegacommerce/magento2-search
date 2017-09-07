<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use OmegaCommerce\Search\Controller\Adminhtml\Index as ParentIndex;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class Application extends Action
{

    public function __construct(
        \OmegaCommerce\Api\Config $config,
        \Magento\Framework\View\LayoutInterface $layout,
        Action\Context $context
    ) {
        $this->layout = $layout;
        $this->config = $config;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        try {
            $resultPage->getConfig()->getTitle()->prepend("Omega Instant Search");
            /** @var \OmegaCommerce\Search\Block\Adminhtml\Application $block */
            $block = $this->layout->createBlock('OmegaCommerce\Search\Block\Adminhtml\Application');
            $resultPage->addContent($block);
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);//reread session data
        }
        return $resultPage;
    }

    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OmegaCommerce_Search::search');
    }
}
