<?php

Use Kirby\Cms\Blueprint;
use Kirby\Cms\Api;
use Kirby\Cms\Form;

// /**
//  * Calls the API endpoint for a nested field (e.g. a structure or a fieles field)
//  */
// function fieldSetCallFieldApi($ApiInstance, $fieldPath, $context, $path) {

//   // this should be fixed..
//   $fieldPath = Str::split($fieldPath, '+');
//   $form = Form::for($context);
//   $field = fieldsetFieldFromPath($fieldPath, $context, $form->fields()->toArray());
//   $fieldApi = $ApiInstance->clone([
//     'routes' => $field->api(),
//     'data'   => array_merge($ApiInstance->data(), ['field' => $field])
//   ]);
//   dump($ApiInstance->requestData());die();
//   return $fieldApi->call($path, $ApiInstance->requestMethod(), $ApiInstance->requestData());
// }

// /**
//  * Gets a nested Field Object, also from within nested builder, by recursively iterating and extending through the configurations.
//  * 
//  * @param array $fieldPath
//  * @param \Kirby\Cms\Page $page
//  * @param array $fields
//  * @return \Kirby\Form\Field
//  */
// function fieldsetFieldFromPath($fieldPath, $page, $fields) {
//     $fieldsetFieldName = array_shift($fieldPath);
//     $fieldName = $fieldPath[0];
//     $fieldProps = $fields[$fieldsetFieldName];
//     $fieldType = $fieldProps['fieldset'][$fieldName]['type'];
//     // $field = $fieldProps['fieldset'][$fieldPath];
//     // dump($fieldName);
//     // dump($fieldsetFieldName);
//     // dump($fieldType);
//     // dump($fieldProps['fields']);die();
//     return new Field($fieldType, $fieldName, $fieldType = $fieldProps['fieldset'][$fieldName]);
//     // die();
//     // if ($fieldProps['type'] === 'builder' && count($fieldPath) > 0) {
//     //   $fieldsetKey = array_shift($fieldPath);
//     //   $fieldset = $fieldProps['fieldsets'][$fieldsetKey];
//     //   $fieldset = BuilderBlueprint::extend($fieldset);
//     //   $fieldset = extendRecursively($fieldset, $page, '__notNull');
//     //   if (array_key_exists('tabs', $fieldset) && is_array($fieldset['tabs'])) {
//     //     $fieldsetFields = [];
//     //     foreach ( $fieldset['tabs'] as $tabKey => $tab) {
//     //       $fieldsetFields = array_merge($fieldsetFields, $tab['fields']);
//     //     }
//     //   } else {
//     //     $fieldsetFields = $fieldset['fields'];
//     //   }
//     //   return fieldFromPath($fieldPath, $page, $fieldsetFields);
//     // } else if ($fieldProps['type'] === 'structure' && count($fieldPath) > 0) {
//     //   return fieldFromPath($fieldPath, $page, $fieldProps['fields']);
//     // } else {
//     //   $fieldProps['model'] = $page;
//     //   return new Field($fieldProps['type'], $fieldProps);
//     // }
// }

Kirby::plugin('reprovinci/fieldset', [
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
                'field' => function ($value) {
                    dump($this); die();
                    return new Field([
                        'fields' => $this->name,
                        'values' => $values,
                        'model'  => $this->model
                    ]);
                },
                'form' => function (array $values = []) {
                    return new Form([
                        'fields' => $this->fieldset(),
                        'values' => $values,
                        'model'  => $this->model
                    ]);
                },
            ],
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
                    $vals = Yaml::decode($this->value());
                    // foreach($vals as $key => $val) {
                    //     if (is_array($val)) {
                    //         $val = Yaml::encode($val);
                    //     }
                    //     $vals[$key] = $val;
                    // }
                    // dump($vals);die();
                    return $vals;
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
