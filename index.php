<?php

Use Kirby\Cms\Blueprint;
use Kirby\Cms\Api;
use Kirby\Cms\Form;

/**
 * Calls the API endpoint for a nested field (e.g. a structure or a fieles field)
 */
function fieldSetCallFieldApi($ApiInstance, $context, $path) {

  // this should be fixed..
  return 'This is where it goes wrong. This part should be fixed.';

  // $fieldpath = $path;
  // $path = Str::split($path, '+');
  // $form = Form::for($context);
  // // dump($form);die();
  // $field = fieldFromPath($path, $context, $form->fields()->toArray());
  // $fieldApi = $ApiInstance->clone([
  //   'routes' => $field->api(),
  //   'data'   => array_merge($ApiInstance->data(), ['field' => $field])
  // ]);
  // return $fieldApi->call('files', $ApiInstance->requestMethod(), $ApiInstance->requestData());
}

Kirby::plugin('reprovinci/fieldset', [
    'fieldMethods' => [
        'fieldset' => function($data) {
            $struct = new Structure([$data->yaml()], $data->parent());
            return $struct->first();
            // // todo: sanity checks
            // foreach($data->yaml() as $field => $var) {
            //     dump($field);
            // }
            // die();
        }
    ],
    'fields' => [
        'fieldset' => [
            'props' => [
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
                    }
                    // normalize all fields
                    $fs = Blueprint::fieldsProps($fs);
                    // head back
                    return $fs;
                }
            ],
            'computed' => [
                'storedvalues' => function () {
                    // grab the data from the text file pass it as array
                    return Yaml::decode($this->value());
                }
            ],
        ],
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'fieldset/pages/(:any)/fields/(:all)',
                'method'  => 'ALL',
                'action'  => function (string $id, string $path = null) {            
                    if ($page = $this->page($id)) {
                        return fieldSetCallFieldApi($this, $page, $path);
                    }
                }
            ],
        ]
    ],
]);
