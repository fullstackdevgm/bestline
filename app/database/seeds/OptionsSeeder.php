<?php

use \Option\Data as OptionData;

class OptionsSeeder extends DatabaseSeeder
{
    public function run()
    {
        DB::unprepared("SET foreign_key_checks=0");
        DB::table('options')->truncate();
        OptionData::truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $this->getOptionsFromCsv();
    }

    private function getOptionsFromCsv() {
        $csv = array_map('str_getcsv', file(__DIR__ . '/csv/options_seeder.csv'));
        $header = array_flip(array_shift($csv));

        foreach($csv as $line) {
            if(count($line) != count($header)) {
                continue;
            }

            $parentName = ($line[$header['parent_name']]!='') ? $line[$header['parent_name']] : NULL;

            if($parentName){
                $parentId = Option::where('name', '=', $parentName)->firstOrFail();
            } else {
                $parentId = null;
            }

            $optionObj = Option::create(array(
                'name' => $line[$header['name']],
                'pricing_type' => $line[$header['pricing_type']],
                'min_charge' => $line[$header['min_charge']],
                'pricing_value' => $line[$header['pricing_value']],
                'price_as_fabric' => $line[$header['price_as_fabric']],
                'tier_formula' => $line[$header['tier_formula']],
                'assembler_note' => $line[$header['assembler_note']],
                'seamstress_note' => $line[$header['seamstress_note']],
                'embellisher_note' => $line[$header['embellisher_note']],
                'is_embellishment_option' => $line[$header['is_embellishment_option']],
                'is_interlining_option' => $line[$header['is_interlining_option']],
                'parent_id' => $parentId,
            ));

            $optionObj->save();

            $data = ($line[$header['data']]!='') ? $line[$header['data']] : NULL;

            if($data){
                $newData = [
                    'option_id' => $optionObj->id,
                ];
                $dataKeys = explode(';', $data);
                foreach($dataKeys as $key){
                    if($key !== ""){
                        $newData[$key] = 1;
                    }
                }
                $optionObj->data()->create($newData);
                $optionObj->data->save();
            }
        }
    }
}