<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use League\Csv\Reader;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = $this->json();
        // dd($json);
        // return false;
        $json = json_decode($this->json(), true);
        // return var_dump($json);
        $i = 0;
        foreach ($json as $key => $psgc_data) {
            $i++;
            if($i == 1){
                // var_dump($psgc_data);
                continue;
            }
            
            $insert_data['first_name'] = $psgc_data[0];
            $insert_data['middle_name'] = $psgc_data[1];
            $insert_data['last_name']  = $psgc_data[2];
            $insert_data['ext_name'] = $psgc_data[3];
            $insert_data['gender'] = "";
            $insert_data['birth_date'] = "1900-01-1";
            //$insert_data['contact_person'] = $psgc_data[2];
            $insert_data['mobile_number'] = "09000000000";
            $insert_data['email'] = $psgc_data[5];
            $insert_data['username'] = $psgc_data[6];
            $insert_data['password'] = $psgc_data[7];
            $insert_data['psgc_id'] = $psgc_data[8];
        
            $user = User::create($insert_data);
            $user->assignRole('admin');
           
            echo "created User: $user->username \n";
        }
    }

    public function json()
    {
        $reader = Reader::createFromPath(public_path('/dataseeders/users.csv'), 'r');
        $results = $reader->getRecords();
        $data = array();
        foreach ($results as $key => $row) {
            $data[] = $row;
        }
        return $this->safe_json_encode($data);
    }

    private function safe_json_encode($value, $options = 0, $depth = 512){
		$encoded = json_encode($value, $options, $depth);
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = $this->utf8ize($value);
				return $this->safe_json_encode($clean, $options, $depth);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()

		}
    }
    
    private function utf8ize($d) {
		if (is_array($d)) {
			foreach ($d as $k => $v) {
				$d[$k] = $this->utf8ize($v);
			}
		} else if (is_string ($d)) {
			return utf8_encode($d);
		}
		return $d;
	}

}