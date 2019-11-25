<?php


namespace TheNamespace;


use Ling\Bat\ArrayTool;
use Ling\Chloroform\Form\Chloroform;
use Ling\Chloroform\FormNotification\ErrorFormNotification;
use Ling\Light\Http\HttpResponseInterface;
use Ling\Light_DatabaseInfo\Service\LightDatabaseInfoService;
use Ling\Light_Realform\Service\LightRealformService;
use Ling\SimplePdoWrapper\SimplePdoWrapper;
use Ling\SimplePdoWrapper\SimplePdoWrapperInterface;
use Ling\WiseTool\WiseTool;
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
     * Applies a standard routine to the form identified by the given realformIdentifier,
     * and returns a chloroform instance.
     *
     * What does this method do?
     * ----------------
     *
     * It creates the form, using realform,
     * it handles both the form insert and update actions.
     * The update mode is triggered if the ric strict columns are passed in the url (i.e. $_GET).
     *
     * If the form is posted correctly, the posted data are handled using the on_success_handler (defined
     * by the realform configuration), and the page is refreshed.
     *
     * The table and pluginName arguments are used to help with default @page(micro-permissions) used
     * by this routine, which uses the @page(micro-permission recommended notation for database interaction).
     *
     *
     * Errors and success messages are handled using the @page(flash service).
     *
     *
     *
     *
     * @param string $realformIdentifier
     * @param string $table
     * @param string $pluginName
     * @throws \Exception
     * @return Chloroform
     */
    protected function processForm(string $realformIdentifier, string $table, string $pluginName): Chloroform
    {

        //--------------------------------------------
        // INSERT/UPDATE SWITCH
        //--------------------------------------------
        /**
         * For now, if ric exists in the url, then it's an update, otherwise it's an insert.
         */

        $isUpdate = false;


        /**
         * Ensure that the columns provided by the user are the ric strict columns,
         * otherwise a malicious user could access the table data in a way we might not have anticipated.
         * For instance:
         * - select * where identifier=root
         *
         *
         */

        /**
         * @var $dbInfoService LightDatabaseInfoService
         */
        $dbInfoService = $this->getContainer()->get("database_info");
        $tableInfo = $dbInfoService->getTableInfo($table);
        $ric = $tableInfo['ricStrict'];

        if (true === ArrayTool::arrayKeyExistAll($ric, $_GET)) {
            $isUpdate = true;
            $updateRic = ArrayTool::intersect($_GET, $ric);
        }


        //--------------------------------------------
        //
        //--------------------------------------------
        $container = $this->getContainer();
        $flasher = $this->getFlasher();


        //--------------------------------------------
        // FORM
        //--------------------------------------------
        /**
         * @var $rf LightRealformService
         */
        $rf = $container->get("realform");
        $rfHandler = $rf->getFormHandler($realformIdentifier);

        $form = $rfHandler->getFormHandler();

        //--------------------------------------------
        // Posting the form and validating data
        //--------------------------------------------
        if (true === $form->isPosted()) {
            if (true === $form->validates()) {
                // do something with $postedData;
                $vid = $form->getVeryImportantData();


                $form->executeDataTransformers($vid);

                $formIsHandledSuccessfully = false;

                //--------------------------------------------
                // DO SOMETHING WITH THE DATA...
                //--------------------------------------------
                try {

                    $successHandler = $rfHandler->getSuccessHandler();
                    $extraParams = [];
                    if (true === $isUpdate) {
                        $extraParams["updateRic"] = $updateRic;
                    }


                    $successHandler->processData($vid, $extraParams);
                    $formIsHandledSuccessfully = true;


                } catch (\Exception $e) {
                    $form->addNotification(ErrorFormNotification::create($e->getMessage()));
                }


                //--------------------------------------------
                // redirect
                //--------------------------------------------
                if (true === $formIsHandledSuccessfully) {
                    if (null !== $this->iframeSignal) {
                        $form->setProperty("iframe-signal", $this->iframeSignal);
                    } else {

                        /**
                        * We redirect because the user data is used in the gui (for instance in the icon menu in the header.
                        * And so if the user changed her avatar for instance, we want her to notice the changes right away.
                        * Hence we redirect to the same page
                        */


                        /**
                        * Also, if it's an update, the ric params are in the $_GET (and in the url), and so if we were just
                        * refreshing the page (which is what the redirect basically will do) we would have the old ric
                        * parameters displayed in the form, which is not what we want: we want the refreshed form to
                        * reflect the newest changes, including changes in the ric.
                        * So, we just override the ric in $_GET, so that the new page refreshes with the new rics.
                        */
                        if (true === $isUpdate) {
                            foreach ($vid as $k => $v) {
                                if (in_array($k, $ric, true) && array_key_exists($k, $_GET)) {
                                    $_GET[$k] = $v;
                                }
                            }
                        }


                        $flasher->addFlash($table, "Congrats, the user form was successfully updated.");
                        $this->redirectByRoute($this->getLight()->getMatchingRoute()['name']);
                    }
                }

            } else {
//                $form->addNotification(ErrorFormNotification::create("There was a problem."));
            }
        } else {

            $valuesFromDb = [];

            $this->checkMicroPermission("$pluginName.tables.$table.read");


            if (true === $isUpdate) {
                /**
                 * @var $db SimplePdoWrapperInterface
                 */
                $db = $this->getContainer()->get("database");
                $query = "select * from `$table`";
                $markers = [];
                SimplePdoWrapper::addWhereSubStmt($query, $markers, $updateRic);
                $row = $db->fetch($query, $markers);
                if (false !== $row) {
                    $valuesFromDb = $row;
                }
            }

            $form->injectValues($valuesFromDb);


            if ($flasher->hasFlash($table)) {
                list($message, $type) = $flasher->getFlash($table);
//                $this->getKitAdmin()->addNotification(WiseTool::wiseToLightKitAdmin($type, $message));
                $form->addNotification(WiseTool::wiseToChloroform($type, $message));
            }

        }
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