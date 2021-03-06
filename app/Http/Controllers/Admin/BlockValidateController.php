<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlockValidateController extends Controller
{
    public function checkBlocks(Request $request)
    {
        $response = $request->only('model', 'fields');
        $modelName = str_replace('AppModels', 'App\Models\\', $response['model']);
        $fieldsRaw = explode(',', $response['fields']);

        $model = app($modelName);
        $rules = $model->blockEditorRules ?? null;
        $choices = array_map(function ($field) {
            return str_replace('a17-block-', '', $field);
        }, $fieldsRaw);

        $rule_set = [];
        $required_count = 0;
        foreach ($rules as $rule) {
            $clean = trim($rule);
            $rule = preg_replace('!\s+!', ' ', $clean);

            $rule = str_replace('must have ', 'req|', $rule);
            $rule = str_replace('may have ', 'opt|', $rule);
            $rule = str_replace(' of ', '|', $rule);
            $rule = str_replace(' or ', ':', $rule);

            list($type, $cnt, $fieldString) = explode('|', $rule);

            foreach (range(1, $cnt) as $iindx) {
                $required_count = $type == 'req' ? $required_count + 1 : $required_count;
                $rule_set[] = [
                    'type' => $type,
                    'fields' => explode(':', $fieldString),
                ];
            }
        }

        $choice_step = 0;
        $rule_step = 0;
        $current_req_fields = [];
        $possible_fields = [];

        $message_array = ['valid' => true];
        foreach ($rule_set as $indx) {

      $choices[$choice_step] = isset($choices[$choice_step]) ? $choices[$choice_step] : '';


            $current_type = $rule_set[$rule_step]['type'];
            $current_fields = $rule_set[$rule_step]['fields'];
            $current_block = $choices[$choice_step];

            if ($current_type == 'req' && in_array($current_block, $current_fields)) {
                $choice_step++;
                $rule_step++;
                continue;
            }

            if ($current_type == 'opt' && in_array($current_block, $current_fields)) {
                $choice_step++;
                $rule_step++;
                continue;
            }

            if ($current_type == 'opt' && !in_array($current_block, $current_fields)) {
                $rule_step++;
                $possible_fields = array_merge($possible_fields, $current_fields);
                continue;

            }

            $current_fields = array_unique(array_merge($current_fields, $possible_fields));

            $message_array = [
                'valid' => false,
                'choice_step' => $choice_step,
                'current_block' => $current_block,
                'possible_blocks' => $current_fields
            ];

            if ($message_array) {
                break;
            }
        }

        return response()->json($message_array);
    }
}
