<?php
namespace Market\Controller;

use Market\Form\UploadTrait;
use Market\Event\LogEvent;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
//*** CACHE LAB: add a use statement for the listener aggregate
use Market\Listener\CacheAggregate;
//*** EMAIL LAB: add "use" statement to trigger email notification event

class PostController extends AbstractActionController implements ListingsTableAwareInterface
{

    const ERROR_POST = 'ERROR: unable to validate item information';
    const ERROR_SAVE = 'ERROR: unable to save item to the database';
    const SUCCESS_POST = 'SUCCESS: item posted OK';
    const ERROR_MAX    = 'ERROR: invalid form postings';
	const MAX_INVALID = 3;
	
    use FlashTrait;
    use PostFormTrait;
    use ListingsTableTrait;
    use CityCodesTableTrait;

	protected $sessionContainer;
	
    public function indexAction()
    {

        $data = [];

        if ($this->getRequest()->isPost()) {

            //*** FILE UPLOAD LAB: merge $_POST with $_FILES
            $data = $this->params()->fromPost();
            $this->postForm->setData($data);

            if ($this->postForm->isValid()) {

                // retrieve data: due to form binding will get a Model\Entity\Listing instance
                $listing = $this->postForm->getData();

                //*** FILE UPLOAD LAB: move uploaded file from /images folder into /images/<category>
                //*** FILE UPLOAD LAB: reset $listing->photo_filename'] to final filename /images/<category>/filename

                // save data and process
                if ($this->listingsTable->save($listing)) {

                    $this->flashMessenger()->addMessage(self::SUCCESS_POST);
                    //*** EMAIL LAB: trigger an email notification of success; also, use class constant instead of hard-coded event
                    //*** EVENTMANAGER LAB: trigger a log event and pass the online market item title as a parameter
                    //*** CACHE LAB: trigger event which signals clear cache
                    $em = $this->getEventManager();
                    $em->trigger(CacheAggregate::EVENT_CLEAR_CACHE, $this);
                    return $this->redirect()->toRoute('market');

                } else {

                    $this->flashMessenger()->addMessage(self::ERROR_SAVE);

                }

            } else {

				//*** SESSIONS LAB: keep track of how many times an invalid form posting is made
				//***               if the # times exceeds a limit you set, log a message and redirect home
				if (!isset($this->sessionContainer->invalid)) {
					$this->sessionContainer->invalid = 1;
				} else {
					$this->sessionContainer->invalid++;
				}
                $this->flashMessenger()->addMessage(self::ERROR_POST);
				if ($this->sessionContainer->invalid > self::MAX_INVALID) {
					error_log(date('Y-m-d H:i:s') . ': Max invalid form postings reached');
					$this->flashMessenger()->addMessage(self::ERROR_MAX);
					$this->sessionContainer->invalid = 1;
					return $this->redirect()->toRoute('market');
				}
            }
        }

        $viewModel = new ViewModel(['postForm' => $this->postForm, 'data' => $data]);
        $viewModel->setTemplate('market/post/index');
        return $viewModel;

    }

	public function setSessionContainer($sessionContainer)
	{
		$this->sessionContainer = $sessionContainer;
	}
}
