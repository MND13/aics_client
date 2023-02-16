<?php

namespace App\Imports;

use App\Models\AicsClient;
use App\Models\DirtyList;
use App\Models\DirtyListClients;
use App\Models\Psgc;
use App\Rules\AllowedStringName;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements WithHeadingRow, ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithValidation
{
    /**
     * @param Collection $collection
     */

     use RemembersRowNumber;
     private $dirtyList;

     public function  __construct(DirtyList $dirtyList)
     {
        $this->dirtyList = $dirtyList;
     }

    public function model(array $row)
    {
        $birth_date = null;
        if(isset($row['birth_date'])){
            switch (gettype($row['birth_date'])) {
                case 'integer':
                    $birth_date = gmdate("Y-m-d", ($row['birth_date'] - 25569) * 86400);
                    break;
                case 'string':
                    $birth_date = trim(date_format(date_create($row['birth_date']), "Y-m-d"));
                    break;
                default:
                    $birth_date = null;
                    break;
            }
        }
        $psgc_id = null;
        if(isset($row['psgc'])){
            $psgc = Psgc::where('brgy_psgc', $row['psgc'])->first();
            $psgc_id = $psgc->id;
        }
        return new AicsClient([
            'dirty_list_id' => $this->dirtyList->id,
            'first_name'    => mb_strtoupper(trim($row['first_name'] ?? null)),
            'middle_name'   => mb_strtoupper(trim($row['middle_name'] ?? null)),
            'last_name'     => mb_strtoupper(trim($row['last_name'] ?? null)),
            'ext_name'      => mb_strtoupper(trim($row['ext_name'] ?? null)),
            'street_number' => mb_strtoupper(trim($row['street_number'] ?? null)),
            'birth_date'    => $birth_date,
            'psgc_id'       => $psgc_id,
        ]);
    }

 
    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required','max:255', new AllowedStringName()],
            'middle_name' => ['max:255', new AllowedStringName()],
            'last_name' => ['required','max:255', new AllowedStringName()],
            'ext_name' => ['max:255', new AllowedStringName()],
            'street_number' => 'max:255',
            'birth_date' => 'sometimes|date',
            'psgc' => 'sometimes|exists:psgcs,brgy_psgc',
        ];
    }

    public function prepareForValidation($data, $index)
    {
        if(isset($data['birth_date'])){
            switch (gettype($data['birth_date'])) {
                case 'integer':
                    $data['birth_date'] = gmdate("Y-m-d", ($data['birth_date'] - 25569) * 86400);
                    break;
                case 'string':
                    // $data['4'] = trim(date_format(date_create($data['birth_date']), "Y-m-d"));
                    break;
                default:
                    $data['birth_date'] = null;
                    break;
            }
        }
        if(empty($data['psgc'])){
            unset($data['psgc']);
        }
        
        if(empty($data['birth_date'])){
            unset($data['birth_date']);
        }
        
        return $data;
    }
    
}
