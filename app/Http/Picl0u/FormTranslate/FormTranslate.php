<?php
namespace App\Http\Picl0u\FormTranslate;

use App\Http\Picl0u\DeleteCache;
use Collective\Html\FormFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FormTranslate{

    use DeleteCache;

    private $model;

    private $modelname;

    private $translatableInputs = [];

    private $data;

    private $key;

    private $action;

    /**
     * FormTranslate constructor.
     * @param $modelName
     * @param $data
     */
    public function __construct($modelName, $data = null)
    {
        $this->model = new $modelName();
        $model = explode("\\",$modelName);
        $this->modelName = $model[count($model)-1];
        $this->data = $data;
        $this->key = config('ikCommerce.translateKey');

        if(!is_null($this->model->translatableInputs)){
            $this->translatableInputs = $this->model->translatableInputs;
        }
    }

    /**
     * @param string $route
     * @return FormTranslate
     */
    public function action(string $route): self
    {
        $this->action =  route($route,[
            'id' => $this->data->id
        ]);
        return $this;
    }
    /**
     *
     * @return string
     */
    public function render(): string
    {
        if(!empty($this->translatableInputs) && !is_null($this->data)){
            $langs = array_diff(config('ikCommerce.languages'),[config('app.locale')]);
            if(!empty($langs)) {
                $html = $this->buttons($langs);
                $html .= $this->modal();
                return $html;
            }
        }
        return '';
    }

    public function formRequest(Request $request)
    {
        $translateFields = $this->model->translatable;
        if($request->method() == 'GET') {
            $id = $request->id;
            $lang = $request->lang;
            $data = $this->model->where('id', $id)->firstorFail();
            $translate = [];
            foreach($translateFields as $field){
                $translate[] = [
                    $this->key.'_'.$field => $data->getTranslation($field, $lang)
                ];
            }
            return response()->json($translate);

        }
        if($request->method() == 'POST') {

            $id = Input::get('id');
            $lang = Input::get('lang');
            $this->flush(strtolower($this->modelName), $id);

            $content = $this->model->where('id', $id)->firstorFail();
            foreach ($translateFields as $field) {
                $key = $this->key.'_'.$field;
                if(empty($request->$key)){
                    $request->$key = null;
                }
                $content->setTranslation($field, $lang, $request->$key);
            }
            $content->update();
            return response()->json(["message" => 'La traduction a bien été sauvegardée.']);
        }

        return response("Une erreur s'est produite", 500)->header('Content-Type', 'text/plain');
    }

    /**
     * @param $key
     * @return FormTranslate
     */
    public function setKey($key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Retoune les boutons pour appeler la modal-box
     * @param array $langs
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function buttons(array $langs)
    {
        return view('formTranslate.buttons',compact('langs'));
    }

    /**
     * Retourne la modal box avec le formulaire
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function modal()
    {
        $form = '';
        $action = $this->action;
        foreach ($this->translatableInputs as $field => $input) {
            $form .= $this->renderInput($field, $input);
        }
        return view('formTranslate.modal', compact('form','action'));
    }

    /**
     * Retourne les champs pour la traduction
     * @param string $field
     * @param array $input
     * @return string
     */
    private function renderInput(string $field, array $input): string
    {
        $html = '<div class="form-item">';
            $attributes = [];
            if($input['type'] == 'editor') {
                $input['type'] = 'textarea';
                $attributes = ['class' => 'html-editor'];
            }
            $html .= FormFacade::label($this->key . "_" . $field, $input['label']);
            $html .= FormFacade::{$input['type']}(
                $this->key . "_" . $field,
                null,
                $attributes
            );
            //$html .= '<div class="desc">' . $this->data->getTranslation($field, config('app.locale')) . '</div>';
        $html .= '</div>';

        return $html;
    }

}
