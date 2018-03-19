<?php

use Fabric\Type;
class FabricTypesSeeder extends DatabaseSeeder
{
    public function run()
    {
        DB::unprepared("SET foreign_key_checks=0");
        Type::truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $types = [
            'face' => 'Face',
            'interlining' => 'Interlining',
            'embellishment' => 'Embellishment',
            'lining' => 'Lining',
            'face-valance' => 'Face (valance)',
            'interlining-valance' => 'Interlining (valance)',
            'embellishment-valance' => 'Embellishment (valance)',
            'lining-valance' => 'Lining (valance)',
        ];

        foreach($types as $typeId => $name) {
            $type = new Type();
            $type->type = $typeId;
            $type->name = $name;
            $type->save();
        }
    }
}