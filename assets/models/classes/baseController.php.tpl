<?php


namespace TheNamespace;


use Ling\Bat\UriTool;
use Ling\Chloroform\Form\Chloroform;
use Ling\Light\Http\HttpResponseInterface;
use Ling\Light_Realform\Routine\LightRealformRoutineOne;
//->use


/**
 * The TheBaseController class.
 */
class TheBaseController extends TheParentController
{


    /**
    * This property holds the iframeSignal for this instance.
    * @var string
    */
    protected $iframeSignal;


    /**
    * Builds the instance.
    */
    public function __construct()
    {
        parent::__construct();
        $this->iframeSignal = null;
    }

    /**
     * Renders a page to interact with a table data.
     *
     * @return string|HttpResponseInterface
     * @throws \Exception
     */
    public function render()
    {
        if (array_key_exists("m", $_GET) && 'f' === $_GET['m']) {
            return $this->renderForm();
        }
        return $this->renderList();
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    /**
    * Applies a standard routine to the form identified by the given realformIdentifier, and returns a chloroform instance.
    * See more details in the @page(LightRealformRoutineOne instance).
    *
    *
    * @param string $realformIdentifier
    * @param string $table
    * @param string $pluginName
    * @return Chloroform
    * @throws \Exception
    */
    protected function processForm(string $realformIdentifier, string $table, string $pluginName): Chloroform
    {

        $routineOne = new LightRealformRoutineOne();
        $routineOne->setContainer($this->getContainer());
        $options = [];
        if (null !== $this->iframeSignal) {
            $options['iframeSignal'] = $this->iframeSignal;
        } else {
            $options['onSuccess'] = function () use ($table) {
                $this->getFlasher()->addFlash($table, "Congrats, the form was successfully processed.");
                UriTool::randomize($_GET, '_r');
                $this->redirectByRoute($this->getLight()->getMatchingRoute()['name']);
            };
        }
        $form = $routineOne->processForm($realformIdentifier, $table, $pluginName, $options);
        return $form;
    }


    /**
    * Sets the iframeSignal to use in case of a valid form.
    *
    * @param string $iframeSignal
    */
    public function setOnSuccessIframeSignal(string $iframeSignal)
    {
        $this->iframeSignal = $iframeSignal;
    }

}