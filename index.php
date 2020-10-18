<?php

Use Kirby\Cms\Blueprint;
use Kirby\Cms\Api;
use Kirby\Cms\Form;

Kirby::plugin('art-and-flywork/fieldset', [
    'fieldMethods' => [
        'fieldset' => function($data) {
            $struct = new Structure([$data->yaml()], $data->parent());
            // todo: sanity checks
            return $struct->first();
        }
    ],
    'fields' => [
        'fieldset' => [
            'methods' => [
                'form' => function (array $values = []) {
                    // needed for link field
                    return new Form([
                        'fields' => $this->fieldset(),
                        'values' => $values,
                        'model'  => $this->model
                    ]);
                },
            ],
            'props' => [
            ],
            'computed' => [
                'fieldset' => function() {
                    // load the fieldset blueprint
                    // todo: check if exists, else return info field with error msg
                    $fs = Blueprint::load($this->blueprint);
                    // if tabs exist, extract content tab
                    // this is usually the case for blueprints used in the kirby-builder plugin
                    if (!empty($fs['tabs']) && !empty($fs['tabs']['content'])) {
                        $fs = $fs['tabs']['content']['fields'];
                    // else extract the fields
                    } elseif (!empty($fs['fields'])) {
                        $fs = $fs['fields'];
                    } else {
                        // todo: build info field with error message?
                        $fs = ['error'=>[
                            'type' => 'info',
                            'label' => 'Error in fieldset fields',
                            'text' => 'fields definition could not be extracted from ' . $this->blueprint
                        ]];
                    }
                    // dump($fs);
                    // normalize all fields
                    // $fs = Blueprint::fieldsProps($fs);
                    // dump($fs);die();
                    // head back
                    return $fs;
                },
                'storedvalues' => function () {
                    // grab the data from the text file pass it as array
                    $vals = Yaml::decode($this->value());
                    $fs = $this->fieldset();
                    // dump($fs);die();
                    // foreach($vals as $key => $val) {
                    //     if (is_array($val)) {
                    //         $val = Yaml::encode($val);
                    //     }
                    //     $vals[$key] = $val;
                    // }
                    // dump($vals);die();
                    $form = new Form([
                        'fields' => $this->fieldset(),
                        'values' => $vals,
                        'model' => $this->model()
                    ]);
                    // dump($form); die();
                    return $form->values();
                }
            ],
        ],
    ],
    // 'api' => [
    //     'routes' => [
    //         [
    //             'pattern' => 'fieldset/pages/(:any)/fields/(:any)/(:all?)',
    //             'method'  => 'ALL',
    //             'action'  => function (string $id, string $fieldPath, string $path = null) {
    //                 if ($page = $this->page($id)) {
    //                     return fieldSetCallFieldApi($this, $fieldPath, $page, $path);
    //                 }
    //             }
    //         ],
    //     ]
    // ],
]);
