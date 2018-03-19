<?php

use Fabric\Type;
class FabricSeeder extends DatabaseSeeder
{
    public function run()
    {
        $this->call('FabricTypesSeeder');

        $csv = array_map('str_getcsv', file(__DIR__ . '/csv/fabrics.csv'));

        $header = array_flip(array_shift($csv));

        Eloquent::unguard();

        DB::unprepared("SET foreign_key_checks=0");
        Fabric::truncate();
        DB::table('selected_fabric_types')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $lineCnt = 0;
        foreach($csv as $line) {

            if(count($line) != count($header)) {
                continue;
            }

            if(empty($line[$header['BEST-LINE_NAME']]) || empty($line[$header['BEST-LINE_COLOR']])) {
                continue;
            }

            //remove blanks from option id column
            $grade = ($line[$header['PRICE_GRADE']])? $line[$header['PRICE_GRADE']]: NULL;

            $fabObj = Fabric::create(array(
                'pattern' => $line[$header['BEST-LINE_NAME']],
                'color' => $line[$header['BEST-LINE_COLOR']],
                'width' => $this->convWidth($line[$header['WIDTH/REPEAT']]),
                'unit_price' => $this->convPrice($line[$header['WHOLESALE_PRICE']]),
                'repeat' => $this->convRepeat($line[$header['WIDTH/REPEAT']]),
                'pricing_type' => $line[$header['PRICING_TYPE']],
                'grade' => $grade,
                'image' => 'id'.($lineCnt+1).'.jpg',
            ));

            $fabObj->save();

            $typeTypes = explode(';',preg_replace('/\s+/', '', $line[$header['FABRIC_TYPE']]));
            $types = DB::table('fabric_types')->whereIn('type', $typeTypes)->lists('id');
            $fabObj->types()->sync($types);
            $lineCnt++;
        }

        $this->addMoreFabrics();
        echo $this->addImages();
    }

    protected function convWidth($input)
    {
        $input = trim($input);

        if(empty($input)) {
            return 0;
        }

        list($width, $repeat) = explode('/', $input);

        return (int)$width;
    }

    protected function convRepeat($input)
    {
        $input = trim($input);

        if(empty($input)) {
            return 0;
        }

        list($width, $repeat) = explode('/', $input);

        if(ctype_digit($repeat)) {
            return (int)$repeat;
        }

        return 0;
    }

    protected function convPrice($input)
    {
        return (float)str_replace('$', '', $input);
    }

    protected function addMoreFabrics(){
        $moreFabrics = [];

        //setup acme fabrics
        $acme = Company::where('name', '=', 'Acme, Inc.')->firstOrFail();
        $moreFabrics[] = array(
            'sidemark' => 'Acme Fabric',
            'pattern' => 'Geometric',
            'color' => 'Grey',
            'width' => 57,
            'unit_price' => 0,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'owner_company_id' => $acme->id,
            'image' => 'acmeGeometricGrey.jpg',
        );
        $moreFabrics[] = array(
            'sidemark' => 'Acme Embellishment',
            'pattern' => 'Geometric',
            'color' => 'Blue',
            'width' => 7,
            'unit_price' => 0,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'owner_company_id' => $acme->id,
            'image' => 'acmeGeometricBlue.jpg',
        );

        //setup two embellishment fabrics for bestline
        $moreFabrics[] = array(
            'pattern' => 'Ocean',
            'color' => 'Blue',
            'width' => 7,
            'unit_price' => 22,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('embellishment', 'embellishment-valance'),
            'image' => 'oceanBlue.jpg',
        );
        $moreFabrics[] = array(
            'pattern' => 'Ocean',
            'color' => 'Green',
            'width' => 5,
            'unit_price' => 22,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('embellishment', 'embellishment-valance'),
            'image' => 'oceanGreen.jpg',
        );

        //setup Lam's fabrics
        $lams = Company::where('name', '=', 'Lam\'s Custom Draperies')->firstOrFail();
        $moreFabrics[] = array(
            'width' => 54,
            'repeat' => 22,
            'sidemark' => 'Ratna/Girls',
            'minimum_qty' => 50,
            'owner_company_id' => $lams->id,
            'pattern' => 'Mussa',
            'color' => 'Blue',
            'pricing_type' => 'flat',
            'image' => 'ratnaGirlsMussaBlue.jpg',
        );
        $moreFabrics[] = array(
            'width' => 54,
            'repeat' => 0,
            'sidemark' => 'Ratna/Girls',
            'minimum_qty' => 50,
            'owner_company_id' => $lams->id,
            'pattern' => 'Plain',
            'color' => 'White',
            'pricing_type' => 'flat',
            'image' => 'ratnaGirlsPlainWhite.jpg',
        );
        $moreFabrics[] = array(
            'width' => 59,
            'repeat' => 0,
            'sidemark' => 'Alice/Tracy Rm 1',
            'minimum_qty' => 50,
            'owner_company_id' => $lams->id,
            'pattern' => 'Dulce Ensign',
            'color' => 'Blue',
            'pricing_type' => 'flat',
            'image' => 'dulceBlue.jpg',
        );

        //setup stock, blackout, and thermal fabrics
        $moreFabrics[] = array(
            'pattern' => 'White Lining',
            'color' => 'White',
            'width' => 54,
            'unit_price' => 54.25,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('lining', 'lining-valance', 'interlining', 'interlining-valance'),
            'image' => 'whiteLiningWhite.jpg',
        );
        $moreFabrics[] = array(
            'pattern' => 'Stock',
            'color' => 'White',
            'width' => 54,
            'unit_price' => 6,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('lining', 'lining-valance', 'interlining', 'interlining-valance'),
            'image' => 'stockWhite.jpg',
        );
        $moreFabrics[] = array(
            'pattern' => 'Stock',
            'color' => 'Ivory',
            'width' => 54,
            'unit_price' => 6,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('lining', 'lining-valance', 'interlining', 'interlining-valance'),
            'image' => 'stockIvory.jpg',
        );
        $moreFabrics[] = array(
            'pattern' => 'Blackout',
            'color' => 'White',
            'width' => 54,
            'unit_price' => 0,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('lining', 'lining-valance', 'interlining', 'interlining-valance'),
            'image' => 'blackoutWhite.jpg',
            'related_option_id' => Option::where('name', '=', 'Blackout')->firstOrFail()->id,
        );
        $moreFabrics[] = array(
            'pattern' => 'Blackout',
            'color' => 'Ivory',
            'width' => 54,
            'unit_price' => 0,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('lining', 'lining-valance', 'interlining', 'interlining-valance'),
            'image' => 'blackoutIvory.jpg',
            'related_option_id' => Option::where('name', '=', 'Blackout')->firstOrFail()->id,
        );
        $moreFabrics[] = array(
            'pattern' => 'Thermal',
            'color' => 'White',
            'width' => 54,
            'unit_price' => 54.25,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('lining', 'lining-valance', 'interlining', 'interlining-valance'),
            'image' => 'thermalWhite.jpg',
        );
        $moreFabrics[] = array(
            'pattern' => 'Thermal',
            'color' => 'Ivory',
            'width' => 54,
            'unit_price' => 54.25,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('lining', 'lining-valance', 'interlining', 'interlining-valance'),
            'image' => 'thermalIvory.jpg',
        );
        $moreFabrics[] = array(
            'pattern' => 'Interlining',
            'color' => 'White',
            'width' => 54,
            'unit_price' => 0,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('interlining', 'interlining-valance'),
            'image' => 'thermalIvory.jpg',
        );
        $moreFabrics[] = array(
            'pattern' => 'Interlining',
            'color' => 'Ivory',
            'width' => 54,
            'unit_price' => 0,
            'repeat' => 0,
            'pricing_type' => 'yard',
            'types' => array('interlining', 'interlining-valance'),
            'image' => 'thermalIvory.jpg',
        );

        foreach($moreFabrics as $fabric){

            $hasType = (isset($fabric['types']) && count($fabric['types']) > 0);
            $typeSlugs = ($hasType)? $fabric['types'] : [];
            if($hasType){
                unset($fabric['types']);
            }

            $fabObj = Fabric::create($fabric);
            $fabObj->save();
            if(count($typeSlugs) > 0){
                $types = DB::table('fabric_types')->whereIn('type', $typeSlugs)->lists('id');
                $fabObj->types()->sync($types);
            }
        }
    }

    protected function addImages() {
        $source = dirname(__FILE__) . "/../../../sources/fabric_images/";
        $target = dirname(__FILE__) . "/../../../public/uploads/fabrics/";
        $dir = scandir($source);

        foreach ($dir as $key => $value) {
            if($value == "." || $value == ".."){
                continue;
            }

            $src  = $source . $value;
            $tgt = $target . $value;

            if( !copy($src, $tgt) ){
                echo "Error copying fabric: $src ---- $tgt";
                exit();
            }
        }
    }
}
