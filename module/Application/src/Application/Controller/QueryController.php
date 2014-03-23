<?php
/**
 * Query controller.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class QueryController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
