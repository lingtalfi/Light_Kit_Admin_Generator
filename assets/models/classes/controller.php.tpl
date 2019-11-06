<?php


namespace TheNamespace;


use Ling\Light\Http\HttpResponseInterface;
use Ling\Light_Kit\PageConfigurationUpdator\PageConfUpdator;
//->use


/**
 * The TheController class.
 */
class TheController extends TheBaseController
{

    /**
     * Renders the {tableLabel} list page.
     *
     * @return HttpResponseInterface|string
     * @throws \Exception
     */
    public function renderList()
    {
        return $this->renderAdminPage('Light_Kit_Admin/kit/zeroadmin/generated/{table}_list', [], PageConfUpdator::create()->updateWidget("body.light_realist", [
            'vars' => [
                'request_declaration_id' => '{request_declaration_id}',
            ],
        ]));
    }


    /**
     * Renders the {tableLabel} form page.
     *
     * @return string|HttpResponseInterface
     * @throws \Exception
     */
    public function renderForm()
    {

        $table = "{table}";
        $pluginName = "Light_Kit_Admin"; // used for micro-permissions
        $identifier = "Light_Kit_Admin.generated/{table}";


        $form = $this->processForm($identifier, $table, $pluginName);


        //--------------------------------------------
        // RENDERING
        //--------------------------------------------
        return $this->renderAdminPage('Light_Kit_Admin/kit/zeroadmin/generated/{table}_form', [
            "form" => $form,
        ], PageConfUpdator::create()->updateWidget("body.chloroform", [
            'vars' => [
                'title' => "{formTitle}",
            ],
        ]));
    }
}