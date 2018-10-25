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
	
    use PostFormTrait;
    use ListingsTableTrait;
    use CityCodesTableTrait;
    use SessionTrait;
	use UploadConfigTrait;
	
    public function indexAction()
    {

        $data = [];

        if ($this->getRequest()->isPost()) {

            //*** FILE UPLOAD LAB: merge $_POST with $_FILES
            // combine $_POST with $_FILES
            $data = array_merge($this->params()->fromPost(), $this->params()->fromFiles());
            $this->postForm->setData($data);

            if ($this->postForm->isValid()) {

                // retrieve data: due to form binding will get a Model\Entity\Listing instance
                $listing = $this->postForm->getData();
				$listing = $this->processFileUpload($listing);
				
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
				if ($this->redirectIfInvalidPost()) {
					return $this->redirect()->toRoute('market');
				}

            }
        }

        $viewModel = new ViewModel(['postForm' => $this->postForm, 'data' => $data]);
        $viewModel->setTemplate('market/post/index');
        return $viewModel;

    }
    
    protected function processFileUpload($listing)
    {
		//*** FILE UPLOAD LAB: move uploaded file from /images folder into /images/<category>
		$tmpFn     = $listing->photo_filename['tmp_name'];
		$tmpDir    = dirname($tmpFn);
		$partialFn = '/' . $listing->category . '/' . basename($tmpFn);
		$finalFn   = str_replace('//', '/', $tmpDir . $partialFn);
		rename($tmpFn, $finalFn);

		//*** FILE UPLOAD LAB: reset $listing->photo_filename'] to final filename /images/<category>/filename
		$listing->photo_filename = str_replace('//', '/', $this->uploadConfig['img_url'] . $partialFn);
		return $listing;
	}

	//*** SESSIONS LAB: keep track of how many times an invalid form posting is made
	//***               if the # times exceeds a limit you set, log a message and redirect home
	protected function redirectIfInvalidPost()
	{
		$redirect = FALSE;
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
			$redirect = TRUE;
		}
		return $redirect;
	}
	
}
